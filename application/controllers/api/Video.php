<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Video extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $query = $this->db->where('STATUS_PUBLISHED', TRUE)->order_by('DATE_PUBLISHED', 'desc')->get('video');
    if($query) {
      $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE,'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }
}
?>