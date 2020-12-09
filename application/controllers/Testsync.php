<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testsync extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(array('notification', 'upload', 'curl'));
    $this->load->model(array('Mnews', 'Mnotifications', 'Mimage', 'Mtags'));
  }

  public function gallery(){
    $url_galeri = 'http://servicewebnx.ptpn10.co.id/galleries.json';
    $galeri = $this->curl->get($url_galeri);
    foreach ($galeri as $b){
      $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
      if ($check == 0){
        echo $b->title . ' not exist. <br>';
      } else {
        echo $b->title . ' already exist. <br>';
      }
    }
    // $this->session->set_flashdata('success_message', 'Sinkronisasi berhasil');
    // redirect('gallery');
  }
    
  public function berita(){
    $url_berita = 'http://servicewebnx.ptpn10.co.id/news.json';
    $url_artikel = 'http://servicewebnx.ptpn10.co.id/articles.json';
    $berita = $this->curl->get($url_berita);
    // $news_image_path = 'images/news';
    // foreach ($berita as $b){
    //   $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
    //   if ($check == 0){
    //     echo $b->title . ' not exist. <br>';
    //   } else {
    //     echo $b->title . ' already exist. <br>';
    //   }
    // }
    echo '<pre>';
    print_r($berita);
    echo '</pre>';
    $artikel = $this->curl->get($url_artikel);
    echo '<pre>';
    print_r($artikel);
    echo '</pre>';
    // foreach ($artikel as $b){
    //   $check = $this->db->where('TITLE_NEWS', $b->title)->get('news')->num_rows();
    //   if ($check == 0){
    //     echo $b->title . ' not exist. <br>';
    //   } else {
    //     echo $b->title . ' already exist. <br>';
    //   }
    // }
    // $this->session->set_flashdata('success_message', 'Sinkronisasi berhasil');
    // redirect('news');
  }
}
?>