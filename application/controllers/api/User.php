<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class User extends REST_Controller {

  var $firebase;
	var $auth;

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
    $this->load->library(array('upload', 'image_lib'));
    $this->firebase = (new Factory)
			->withServiceAccount('digimagz-fccc4-firebase-adminsdk-p33y5-8c3d95b194.json')
			->create();
		$this->auth = $this->firebase->getAuth();
  }

  public function index_get() {
    if ($this->get('email') != ''){
      $this->db->where('EMAIL', $this->get('email'));
    }
    $data = $this->db->get('user');
    if ($data){
      $this->response(['status' => TRUE, 'data' => $data->num_rows() > 0 ? $data->result() : []], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => "Data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }

  public function index_post() {
    $email = $this->post('email');
    $name = $this->post('name');
    $gender = $this->post('gender');
    $date_birth = $this->post('date_birth');
    $check = $this->db->where('EMAIL', $email)->get('user')->num_rows();
    if ($check == 0){
      $usertype = substr_compare($email, "ptpn10.co.id", -strlen("ptpn10.co.id")) === 0 ? 'Internal' : 'External';
      $data = array(
        'EMAIL' => $email, 
        'USER_NAME' => $name,
        'GENDER' => $gender,
        'DATE_BIRTH' => $date_birth,
        'LAST_LOGIN' => date('Y-m-d H:i:s'),
        'USER_TYPE' => $usertype
      );
      $this->db->insert('user', $data);
      if ($this->db->affected_rows() > 0){
        $this->response(['status' => TRUE, 'data' => $data], 200);
      } else {
        $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
      }
    } else {
      $this->response(['status' => FALSE, 'message' => "User sudah terdaftar"], 502);
    }
  }

  public function avatar_post(){
    $email = $this->post('email');
    $config = ['upload_path' => './images/users/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];
    $this->upload->initialize($config);
    list($width, $height, $type, $attr) = getimagesize($_FILES['picture']['tmp_name']);
    if ($width != $height){
      $config['source_image'] = $_FILES['picture']['tmp_name'];
      $config['x_axis'] = ($width-min($width, $height))/2;
      $config['y_axis'] = ($height-min($width, $height))/2;
      $config['maintain_ratio'] = FALSE;
      $config['width'] = min($width, $height);
      $config['height'] = min($width, $height);
      $this->image_lib->initialize($config);
      $this->image_lib->crop();
    }
    $check = $this->db->select('PROFILEPIC_URL')->where('EMAIL', $email)->get('user')->row();
    if (isset($check->PROFILEPIC_URL)){
      if (strpos($check->PROFILEPIC_URL, 'http://') !== false){
        unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[5]);
      } else {
        unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[3]);
      }
    }
    if ($this->upload->do_upload('picture')){
      $upload = $this->upload->data();
      $this->db->where('EMAIL', $email)->update('user', ['PROFILEPIC_URL' => base_url('images/users/' . $upload['file_name'])]);
      $this->response(['status' => TRUE, 'message' => base_url('images/users/' . $upload['file_name'])], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => strip_tags($this->upload->display_errors())], 404);
    }
  }

  public function index_put() {
    $email = $this->put('email');
    $name = $this->put('name');
    $gender = $this->put('gender');
    $date_birth = $this->put('date_birth');
    $data = ['USER_NAME' => $name, 'GENDER' => $gender, 'DATE_BIRTH' => $date_birth];
    $this->db->where('EMAIL', $email)->update('user', $data);
    if ($this->db->affected_rows() > 0){
      $query = $this->db->where('EMAIL', $email)->get('user');
      $this->response(['status' => TRUE, 'data' => $query->result()], 200);
    } else {
      $this->response(['status' => FALSE, 'message' => "Gagal"], 502);
    }
  }
}
?>