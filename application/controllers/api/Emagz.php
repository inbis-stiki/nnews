<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Emagz extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Mnews");
    $this->load->helper(array("url", "file", "download"));
  }

  function index_get() {
    $id = $this->get('q');
    if ($id != '') $this->db->where('NAME ILIKE', '%' . $id . '%');
    $query = $this->db->order_by('DATE_UPLOADED','desc')->get('emagz');
    if ($query) {
      $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }

  function download_get($id){
    $query = $this->db->where('ID_EMAGZ', $id)->get('emagz')->row()->FILE;
    force_download('emagazine/files/' . $query, NULL);
  }
}
?>