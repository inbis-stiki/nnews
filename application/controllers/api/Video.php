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

  public function detail_get($idVideo){
    if($idVideo != ''){
      $queryCheckDataVideo = $this->db->where('ID_VIDEO', $idVideo)->get('video')->row();

      if($queryCheckDataVideo != null){
        $this->response(['status' => TRUE, 'data' => $queryCheckDataVideo], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => 'Data video tidak ditemukan'], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
    }
  }
}
?>