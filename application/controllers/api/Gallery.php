<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Gallery extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Mnews");
    $this->load->helper("url");
  }

  function index_get() {
    $id = $this->get('q');
    $this->db->distinct()->select('view_news.*')->from('view_news');
    $this->db->join('news_tags', 'news_tags."ID_NEWS" = view_news."ID_NEWS"', 'inner');
    $this->db->join('tags', 'news_tags."ID_TAGS" = tags."ID_TAGS"', 'inner');
    $this->db->where('view_news."STATUS"', 'published')->where('view_news.NAME_CATEGORY', 'Galeri');
    if ($id != ''){
      $this->db->where('view_news."TITLE_NEWS" ILIKE', '%' . $id . '%')->or_where('tags."TAGS" ILIKE', '%' . $id . '%');
    }
    $query = $this->db->order_by('view_news.DATE_NEWS','desc')->get();
    if($query) {
      if ($query->num_rows() > 0){
        $query = $query->result();
        foreach ($query as $q) {
          $q->IMAGES = $this->db->where('ID_NEWS', $q->ID_NEWS)->get('galeri')->result();
        }
        $this->response(['status' => TRUE,'data' => $query], REST_Controller::HTTP_OK);
      } else {
        $this->response(['status' => TRUE,'data' => []], REST_Controller::HTTP_OK);
      }
    } else {
      $this->response([
        'status' => FALSE,
        'message' => "data tidak ditemukan"
      ], REST_Controller::HTTP_OK);
    }
  }
}
?>