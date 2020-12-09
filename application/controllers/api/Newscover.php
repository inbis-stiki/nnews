<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Newscover extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $q = $this->get('id');
    if ($q != ''){
      $this->db->where('ID_COVERSTORY', $q);
    }
    $data = $this->db->where('STATUS', 'published')->order_by('DATE_NEWS', 'desc')->get('view_news_cover');
    if($data) {
      foreach ($data->result() as $q){
        if ($q->NAME_CATEGORY == 'Galeri'){
          $images = [];
          $news_image = $this->db->where('ID_NEWS', $q->ID_NEWS)->get('galeri')->result();
          foreach ($news_image as $ni){
            array_push($images, $ni->IMAGE_FILE);
          }
          $q->NEWS_IMAGE = $images;
        } else {
          if (isset($q->NEWS_IMAGE)){
            $q->NEWS_IMAGE = [$q->NEWS_IMAGE];
          }
        }
      }
    }
    if($data) {
      $this->response(['status' => TRUE, 'data' => $data->num_rows() > 0 ? $data->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE,'message' => "data tidak ditemukan"], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
} ?>