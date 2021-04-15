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

  public function login_post(){
    $email    = $this->post('email');
    $password = $this->post('password');
    if($email != '' && $password != ''){ // check param valid
      $condition            = array('EMAIL' => $email, 'PASSWORD' => md5($password));
      $this->db->where($condition);
      $queryCheckValidUser  = $this->db->get('mobile_user')->row();
      
      if($queryCheckValidUser != null){
        $this->db->select('EMAIL, NAME, NAME_ROLE, PROFILEPIC_USER, PHONE, DATE_BIRTH');
        $condition = array('EMAIL' => $email, 'PASSWORD' => md5($password));
        $this->db->where($condition);
        $queryGetDataUser = $this->db->get('view_mobile_user')->row();

        $this->response(['status' => TRUE, 'data' => $queryGetDataUser], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => "Data user tidak ditemukan"], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => "Parameter tidak cocok"], REST_Controller::HTTP_OK);
    }
  }

  public function register_post(){
    $email      = $this->post('email');
    $idRole     = $this->post('idRole');
    $name       = $this->post('name');
    $dateBirth  = $this->post('dateBirth');
    $password   = md5($this->post('password'));
    $phone      = $this->post('phone');

    if($email != '' && $idRole != '' && $name != '' && $password != '' && $phone != '' && $dateBirth != ''){ //check param valid
      $queryCheckUserIsFound = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
      
      if($queryCheckUserIsFound == null){ // check data if found
        $dataMobileUser = array(
          'EMAIL'       => $email,
          'ID_ROLE'     => $idRole,
          'NAME'        => $name,
          'PASSWORD'    => $password,
          'created_at'  => date('Y-m-d H:i:s')
        );
  
        $dataProfileUser = array(
          'EMAIL'       => $email,
          'PHONE'       => $phone,
          'DATE_BIRTH'  => $dateBirth,
          'created_at'  => date('Y-m-d H:i:s')
        );
  
        $this->db->insert('mobile_user', $dataMobileUser);
        $this->db->insert('profile_user', $dataProfileUser);
  
        $this->response(['status' => TRUE, 'message' => "Data user berhasil disimpan"], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => "Data user sudah terdaftar"], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => "Parameter tidak cocok"], REST_Controller::HTTP_OK);
    }
  }

  public function avatar_post(){
    $email = $this->post('email');
    if($email != '' && !empty($_FILES['avatar'])){
      $queryCheckUserIsFound = $this->db->where('EMAIL', $email)->get('profile_user')->row();
      if($queryCheckUserIsFound != null){
        $config = ['upload_path' => './images/users/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];            
          $this->upload->initialize($config);

          if(!empty($_FILES['avatar']) && $_FILES['avatar']['name'] != ''){ // check if data image1 is not null
              $check = $this->db->select('PROFILEPIC_USER')->where('EMAIL', $email)->get('profile_user')->row();
              if (isset($check->PROFILEPIC_USER)){ // check if image is found then unlink or remove
                  unlink('./images/users/' . explode('/', $check->PROFILEPIC_USER)[5]);
              }
              if($this->upload->do_upload('avatar')){
                  $dataUpload                   = $this->upload->data();
                  $upload['avatar']['status']   = TRUE;
                  $upload['avatar']['message']  = 'Data avatar berhasil diupload';
                  $this->db->where('EMAIL', $email)->update('profile_user', ['PROFILEPIC_USER' => base_url('images/users/' . $dataUpload['file_name'])]);
              }else{
                  $upload['avatar']['status']  = FALSE;
                  $upload['avatar']['message'] = strip_tags($this->upload->display_errors());
              }
          }else{
              $upload['avatar']['status']   = TRUE;
              $upload['avatar']['message']  = "Data avatar tidak ada yang diupdate / diupload";
          }

          $this->response($upload, REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => "Data user tidak ditemukan"], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => "Parameter tidak cocok"], REST_Controller::HTTP_OK);
    }
    // $config = ['upload_path' => './images/users/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];
    // $this->upload->initialize($config);
    // list($width, $height, $type, $attr) = getimagesize($_FILES['picture']['tmp_name']);
    // if ($width != $height){
    //   $config['source_image'] = $_FILES['picture']['tmp_name'];
    //   $config['x_axis'] = ($width-min($width, $height))/2;
    //   $config['y_axis'] = ($height-min($width, $height))/2;
    //   $config['maintain_ratio'] = FALSE;
    //   $config['width'] = min($width, $height);
    //   $config['height'] = min($width, $height);
    //   $this->image_lib->initialize($config);
    //   $this->image_lib->crop();
    // }
    // $check = $this->db->select('PROFILEPIC_URL')->where('EMAIL', $email)->get('user')->row();
    // if (isset($check->PROFILEPIC_URL)){
    //   if (strpos($check->PROFILEPIC_URL, 'http://') !== false){
    //     unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[5]);
    //   } else {
    //     unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[3]);
    //   }
    // }
    // if ($this->upload->do_upload('picture')){
    //   $upload = $this->upload->data();
    //   $this->db->where('EMAIL', $email)->update('user', ['PROFILEPIC_URL' => base_url('images/users/' . $upload['file_name'])]);
    //   $this->response(['status' => TRUE, 'message' => base_url('images/users/' . $upload['file_name'])], 200);
    // } else {
    //   $this->response(['status' => FALSE, 'message' => strip_tags($this->upload->display_errors())], 404);
    // }
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
  
  public function profile_put() {
    $email      = $this->put('email');
    $name       = $this->put('name');
    $dateBirth  = $this->put('dateBirth');
    $phone      = $this->put('phone');
    
    if($email != '' && $name != '' && $dateBirth != '' && $phone != ''){
      $queryCheckUserIsFound = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
      if($queryCheckUserIsFound != null){ // check data user is found
        $dataProfileUser = array(
          'DATE_BIRTH' => $dateBirth,
          'PHONE'      => $phone,
          'updated_at'  => date('Y-m-d H:i:s')
        );
        $dataUser = array(
          'NAME'       => $name,
          'updated_at'  => date('Y-m-d H:i:s')
        );
        
        $this->db->where('EMAIL', $email)->update('mobile_user', $dataUser);
        $this->db->where('EMAIL', $email)->update('profile_user', $dataProfileUser);
        
        $this->response(['status' => TRUE, 'message' => "Data profile user berhasil diubah"], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => "Data user tidak ditemukan"], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => "Parameter tidak cocok"], REST_Controller::HTTP_OK);
    }
  }
  
  public function password_put(){
    $email    = $this->put('email');
    $password = $this->put('password');
    if($email != '' && $password != ''){
      $queryCheckUserIsFound = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
      if($queryCheckUserIsFound != null){ // check data user is found
        $dataUser = array(
          'PASSWORD'    => md5($password),
          'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->db->where('EMAIL', $email)->update('mobile_user', $dataUser);

        $this->response(['status' => TRUE, 'message' => "Password user berhasil diubah"], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => "Data user tidak ditemukan"], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => "Parameter tidak cocok"], REST_Controller::HTTP_OK);
    }
  }
  public function registerToken_post(){
    $this->db->insert('firebase_token', ['TOKEN' => $this->post('token')]);
    $this->response(['status' => TRUE, 'message' => "Berhasil mendaftarkan token"], REST_Controller::HTTP_OK);
  }
}
?>