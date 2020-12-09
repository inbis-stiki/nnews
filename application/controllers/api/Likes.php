<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Likes extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_post(){
    $id_news = $this->post('id_news');
    $email = $this->post('email');
    $data = ['ID_NEWS' => $id_news, 'EMAIL' => $email];
    $this->db->insert('likes', $data);
    if ($this->db->affected_rows() > 0){
      $this->response(['status' => TRUE, 'data' => $data], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
    }
  }

  public function index_get(){
    $id_news = $this->get('id_news');
    $email = $this->get('email');
    $this->db->where('ID_NEWS', $id_news)->like('EMAIL', $email);
    if ($this->db->get('likes')->num_rows() > 0){
      $this->response(['status' => TRUE, 'data' => "Yes"], 200);
    } else {
      $this->response(['status' => FALSE, 'data' => "No"], 200);
    }
  }

  public function index_delete(){
    $id_news = $this->delete('id_news');
    $email = $this->delete('email');
    $data = ['ID_NEWS' => $id_news, 'EMAIL' => $email];
    $this->db->where($data)->delete('likes');
    if ($this->db->affected_rows() > 0){
      $this->response(['status' => TRUE, 'data' => $data], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
    }
  }
}
?>