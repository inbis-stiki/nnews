<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

	function __construct(){
    parent::__construct();
    $this->load->library('youtube');
    $this->load->model('Mvideo');
	}

	public function index(){
    $param['main_content'] = 'videoslist';
		$param['page_title'] = 'Videos List';
		$param['videos_list'] = $this->Mvideo->getAllVideo();
		$this->load->view('dashboard', $param);
  }

  public function change($id_video, $status){
    $status = $status == 0 ? FALSE : TRUE;
    $this->Mvideo->update($id_video, ['STATUS_PUBLISHED' => $status]);
    $this->session->set_flashdata('success_message', 'Status tampil video berhasil diubah');
    redirect('video');
  }
  
  public function updateVideos(){
    $i = 0;
    $decoded = $this->youtube->getVideos();
    $count = count($decoded['items']);
    foreach ($decoded['items'] as $v){
      if ($i < $count - 1){
        $date_pub = explode('T', $v['snippet']['publishedAt']);
        $data = [
          'ID_VIDEO' => $v['id']['videoId'],
          'TITLE' => htmlspecialchars_decode($v['snippet']['title']),
          'DESCRIPTION' => $v['snippet']['description'],
          'DATE_PUBLISHED' => $date_pub[0] . ' ' . substr($date_pub[1], 0, 8),
          'URL_DEFAULT_THUMBNAIL' => $v['snippet']['thumbnails']['default']['url'],
          'URL_MEDIUM_THUMBNAIL' => $v['snippet']['thumbnails']['medium']['url'],
          'URL_HIGH_THUMBNAIL' => $v['snippet']['thumbnails']['high']['url']
        ];
        if ($this->Mvideo->check($v['id']['videoId']) > 0){
          $this->Mvideo->update($v['id']['videoId'], $data);
        } else {
          $this->Mvideo->insert($data);
        }
      }
      $i++;
    }
  }
}
?>