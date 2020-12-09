<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mcaptcha');
	}

	public function index(){
		$data['captcha'] = $this->Mcaptcha->buat_captcha();
		$this->load->view('login', $data);
	}

	public function auth(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
    $captcha = $this->input->post('captcha');
    $check = $this->db->where('USERNAME', $username)->where('PASSWORD', md5($password))->get('backend_user');
		if($check->num_rows() > 0 && $this->Mcaptcha->validate($captcha)){
      $data = $check->row();
			$session = array(
        'username' => $data->USERNAME,
        'nama' => $data->NAME,
        'role' => $data->ROLE,
        'isLogin' => true
      );
			$this->session->set_userdata($session);
			redirect(base_url());
		} else {
			$this->session->set_flashdata('error_login', 'Login Gagal');
			redirect('login');
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('login');
	}
}