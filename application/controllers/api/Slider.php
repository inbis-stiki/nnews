<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Slider extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $query = $this->db->where('STATUS', 'published')->order_by('DATE_NEWS', 'desc')->limit(5, 0)->get('view_news');
    if($query) {
      foreach ($query->result() as $q){
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
    if($query) {
      $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }
}
?>