<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(array('notification', 'upload', 'curl'));
    $this->load->model(array('Mnews', 'Mnotifications', 'Mimage', 'Mtags'));
    $this->load->helper('curl_helper');
  }

  public function gallery(){
    $url_galeri = 'http://servicewebnx.ptpn10.co.id/galleries.json';
    $galeri = $this->curl->get($url_galeri);
    foreach ($galeri as $b){
      $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
      if ($check == 0){
        $id = $this->db->select_max('ID_NEWS', 'id')->get('news')->row()->id + 1;
        $this->Mnews->createNews([
          'ID_NEWS' => $id,
          'ID_CATEGORY' => 'G',
          'TITLE_NEWS' => $b->title,
          'VIEWS_COUNT' => 0,
          'SHARES_COUNT' => 0,
          'DATE_NEWS' => date('Y-m-d H:i:s'),
          'USER_EDITOR' => 'admin@ptpn10.co.id',
          'USER_VERIFICATOR' => 'admin@ptpn10.co.id',
          'STATUS' => 'published'
        ]);
        $folder_path = 'images/gallery/' . $id;
        if(!is_dir($folder_path)) {
          $oldmask = umask(0);
          mkdir($folder_path, 0777, TRUE);
          umask($oldmask);
        } 
        $urls = $b->url;
        for ($i = 0; $i < count($urls); $i++){
          $data = download_image($urls[$i]);
          $urls[$i] = str_replace('http://ptpn10.co.id/img/galeri/', '', rawurldecode($urls[$i]));
          $file_path = $folder_path . '/' . $urls[$i];
          $file = fopen($file_path, 'w+');
          fputs($file, $data);
          fclose($file);
          $this->db->insert('galeri', ['IMAGE_FILE' => rawurldecode($urls[$i]), 'ID_NEWS' => $id]);
        }
        $this->Mtags->insertTags(['PTPN X'], $id);
        $this->notify($b->title, $id);
      }
    }
    $this->session->set_flashdata('success_message', 'Sinkronisasi berhasil');
    redirect('gallery');
  }
    
  public function berita(){
    $url_berita = 'http://servicewebnx.ptpn10.co.id/news.json';
    $url_artikel = 'http://servicewebnx.ptpn10.co.id/articles.json';
    $berita = $this->curl->get($url_berita);
    $news_image_path = 'images/news';
    foreach ($berita as $b){
      $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
      if ($check == 0){
        $doc = new DOMDocument();
        $doc->loadHTML($b->body);
        $imageTags = $doc->getElementsByTagName('img');
        $image = $imageTags->item(0)->getAttribute('src');
        $imageURL = "http://ptpn10.co.id" . $imageTags->item(0)->getAttribute('src');
        $explode = explode('/', $image)[2];
        $data = download_image($imageURL);
        $file_path = $news_image_path . '/' . $explode;
        $file = fopen($file_path, 'w+');
        fputs($file, $data);
        fclose($file);
        $id = $this->db->select_max('ID_NEWS', 'id')->get('news')->row()->id + 1;
        $this->Mnews->createNews([
          'ID_NEWS' => $id,
          'ID_CATEGORY' => 'B',
          'TITLE_NEWS' => $b->title,
          'CONTENT_NEWS' => preg_replace("/<img[^>]+\>/i", "", $b->body),
          'VIEWS_COUNT' => 0,
          'SHARES_COUNT' => 0,
          'NEWS_IMAGE' => $explode,
          'DATE_NEWS' => date('Y-m-d H:i:s', strtotime($b->created) + 25200),
          'USER_EDITOR' => 'admin@ptpn10.co.id',
          'USER_VERIFICATOR' => 'admin@ptpn10.co.id',
          'STATUS' => 'published',
          'URL' => $b->link,
        ]);
        $this->Mtags->insertTags(['PTPN X'], $id);
        $this->notify($b->title, $id);
      }
    }
    $artikel = $this->curl->get($url_artikel);
    foreach ($artikel as $b){
      $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
      if ($check == 0){
        $doc = new DOMDocument();
        $b->body = str_replace('<o:p></o:p>', '', $b->body);
        $doc->loadHTML($b->body);
        $imageTags = $doc->getElementsByTagName('img');
        $image = $imageTags->item(0)->getAttribute('src');
        $imageURL = "http://ptpn10.co.id" . $imageTags->item(0)->getAttribute('src');
        $explode = explode('/', $image)[2];
        $data = download_image($imageURL);
        $file_path = $news_image_path . '/' . $explode;
        $file = fopen($file_path, 'w+');
        fputs($file, $data);
        fclose($file);
        $id = $this->db->select_max('ID_NEWS', 'id')->get('news')->row()->id + 1;
        $this->Mnews->createNews([
          'ID_NEWS' => $id,
          'ID_CATEGORY' => 'A',
          'TITLE_NEWS' => $b->title,
          'CONTENT_NEWS' => preg_replace("/<img[^>]+\>/i", "", $b->body),
          'VIEWS_COUNT' => 0,
          'SHARES_COUNT' => 0,
          'NEWS_IMAGE' => $explode,
          'DATE_NEWS' => date('Y-m-d H:i:s', strtotime($b->created) + 25200),
          'USER_EDITOR' => 'admin@ptpn10.co.id',
          'USER_VERIFICATOR' => 'admin@ptpn10.co.id',
          'STATUS' => 'published',
          'URL' => $b->link,
        ]);
        $this->Mtags->insertTags(['PTPN X'], $id);
        $this->notify($b->title, $id);
      }
    }
    $this->session->set_flashdata('success_message', 'Sinkronisasi berhasil');
    redirect('news');
  }

  public function notify($title_news, $id_news){
    $applications = $this->Mnotifications->getAllDevice();
    foreach ($applications as $app){
      $this->notification->setTitle($title_news);
      $this->notification->setMessage("Digimagz PTPN X");
      $this->notification->setId_news($id_news);
      $request_data = $this->notification->getNotifications();
      $this->notification->pushNotification($app->TOKEN, $request_data);
    }
  }
}
?>