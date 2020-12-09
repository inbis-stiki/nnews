<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Story extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $q = $this->get('id');
    if ($q != ''){
      $this->db->where('ID_COVERSTORY', $q);
    }
    $data = $this->db->order_by('DATE_COVERSTORY', 'desc')->get('cover_story');
    if($data) {
      $this->response(['status' => TRUE, 'data' => $data->num_rows() > 0 ? $data->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE,'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }
}
?>