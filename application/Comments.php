<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Comments extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $id_news = $this->get('id_news');
    $this->db->where('ID_NEWS', $id_news)->where('IS_APPROVED', TRUE);
    $query = $this->db->order_by('DATE_COMMENT', 'desc')->get('view_news_comments');
    if($query) {
      $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE,'message' => "data tidak ditemukan"], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function index_post(){
    $id_news = $this->post('id_news');
    $email = $this->post('email');
    $comments = $this->post('comments');
    $data = [
      'ID_COMMENT' => $this->db->select_max('ID_COMMENT', 'ID')->get('comments')->row()->ID + 1,
      'ID_NEWS' => $id_news, 'EMAIL' => $email, 'COMMENT_TEXT' => $comments,
      'IS_APPROVED' => FALSE, 'DATE_COMMENT' => date('Y-m-d H:i:s'), 'DATE_APPROVED' => date('Y-m-d H:i:s')
    ];
    $this->db->insert('comments', $data);
    if ($this->db->affected_rows() > 0){
      $this->response(['status' => TRUE, 'message' => 'Komentar berhasil ditambahkan'], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
    }
  }

  public function index_delete(){
    $id_comment = $this->delete('id');
    $this->db->where('ID_COMMENT', $id_comment)->delete('comments');
    if ($this->db->affected_rows() > 0){
        $this->response(['status' => TRUE, 'message' => 'Komentar berhasil dihapus'], 200);
    } else {
        $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
    }
  }
}
?>