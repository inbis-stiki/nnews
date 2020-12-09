<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Dummy extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Mnews");
    $this->load->helper("url");
  }

  function index_get() {
    $id = $this->get('q');
    $trending = $this->get('trend');
    $this->db->distinct()->select('view_news.*')->from('view_news');
    $this->db->join('news_tags', 'news_tags."ID_NEWS" = view_news."ID_NEWS"', 'inner');
    $this->db->join('tags', 'news_tags."ID_TAGS" = tags."ID_TAGS"', 'inner');
    $this->db->where('view_news."STATUS"', 'published');
    if ($id != ''){
      $this->db->where('view_news."TITLE_NEWS" ILIKE', '%' . $id . '%')->or_where('tags."TAGS" ILIKE', '%' . $id . '%');
    }
    if ($trending != ''){
      $this->db->order_by('view_news."VIEWS_COUNT"','desc');
    }
    $query = $this->db->order_by('view_news."DATE_NEWS"', 'desc')->get();
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
      $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }

  function news_get($id) {
    $query = $this->db->where('view_news."ID_NEWS"', $id)->get('view_news')->row();
    if($query) {
      if ($query->NAME_CATEGORY == 'Galeri'){
        $images = [];
        $news_image = $this->db->where('ID_NEWS', $query->ID_NEWS)->get('galeri')->result();
        foreach ($news_image as $ni){
          array_push($images, $ni->IMAGE_FILE);
        }
        $query->NEWS_IMAGE = $images;
      } else {
        if (isset($query->NEWS_IMAGE)){
          $query->NEWS_IMAGE = [$query->NEWS_IMAGE];
        }
      }
      $this->response(['status' => TRUE, 'data' => isset($query) ? $query : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }
  
  function click_post() {
    $idnews = $this->post('id_news');
    $email = $this->post('email');
    $data = $this->Mnews->clickViews($idnews, $email);
    if ($data) {
      $this->response(['status' => TRUE, 'message' => "Clicked"], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "Failed"], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  function share_post(){
    $idnews = $this->post('id_news');
    $email = $this->post('email');
    $data = $this->Mnews->clickShare($idnews, $email);
    if ($data) {
      $this->response(['status' => TRUE, 'message' => "Shared"], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "Failed"], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
?>