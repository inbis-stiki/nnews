<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Related extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $q = $this->get('id');
    $this->db->select('tags.TAGS')->from('news');
    $this->db->join('news_tags', 'news_tags.ID_NEWS = news.ID_NEWS', 'inner');
    $this->db->join('tags', 'news_tags.ID_TAGS = tags.ID_TAGS', 'inner');
    $tags = $this->db->where('news.ID_NEWS', $q)->get()->result();
    $count_tags = 1;
    $query = 'SELECT "public".view_news.* FROM "public".view_news
    INNER JOIN "public".news_tags ON "public".news_tags."ID_NEWS" = "public".view_news."ID_NEWS"
    INNER JOIN "public".tags ON "public".news_tags."ID_TAGS" = "public".tags."ID_TAGS" WHERE (';
    foreach ($tags as $t){
      if ($count_tags == 1){
        $query = $query . ' "public".tags."TAGS" = \'' . $t->TAGS . '\'';
      } else {
        $where = $query . ' "OR public".tags."TAGS" = \'' . $t->TAGS . '\'';
      }
      $count_tags++;
    }
    $query = $query . ') AND "public".view_news."ID_NEWS" <> ' . $q . ' 
    AND "public".view_news."STATUS" = \'published\' ORDER BY "public".view_news."DATE_NEWS" DESC';
    $query_res = $this->db->query($query);
    if($query_res) {
      foreach ($query_res->result() as $q){
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
    if($query_res) {
      $this->response(['status' => TRUE, 'data' => $query_res->num_rows() > 0 ? $query_res->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }
}
?>