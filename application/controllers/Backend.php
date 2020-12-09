<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mbackend');
	}

	public function index(){
		$data['users'] = $this->Mbackend->getAllUsers();
		$data['main_content'] = 'backend/user_view';
		$data['page_title'] = 'Manajemen Backend User';
		$this->load->view('dashboard', $data);
  }
  
  public function add_user(){
    $data['main_content'] = 'backend/add_user';
		$data['page_title'] = 'Tambahkan User';
		$this->load->view('dashboard', $data);
  }

  public function create(){
    $username = $this->input->post('username');
    $name = $this->input->post('nama');
    $use_password = $this->input->post('use_password');
    $role = $this->input->post('jabatan');
    $password = $use_password == 'on' ? 'digimagz' : $this->input->post('password');
    if (empty($username) || empty($name) || empty($role) || ($use_password == 'off' && empty($password))){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect('backend/add_user');
    } else {
      $data = [
        'USERNAME' => $username, 
        'NAME' => $name, 
        'PASSWORD' => md5($password),
        'ROLE' => $role,
        'DEFAULT_PASSWORD' => ($use_password == 'off' ? false : true),
      ];
      $this->Mbackend->insert($data);
      $this->session->set_flashdata('success_message', 'Data backend user berhasil ditambahkan');
      redirect('backend');
    }
  }

  public function edit_user($username){
    $data['main_content'] = 'backend/edit_user';
    $data['page_title'] = 'Ubah Data User';
    $data['user'] = $this->Mbackend->getUser(str_replace('_', '@', $username));
		$this->load->view('dashboard', $data);
  }

  public function update(){
    $username = $this->input->post('username');
    $name = $this->input->post('nama');
    $use_password = $this->input->post('use_password');
    $role = $this->input->post('jabatan');
    $password = $use_password == 'on' ? 'digimagz' : $this->input->post('password');
    if (empty($username) || empty($name) || empty($role) || ($use_password == 'off' && empty($password))){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect('backend/edit_user/' . str_replace('@', '_', $username));
    } else {
      $data = [
        'NAME' => $name, 
        'PASSWORD' => md5($password),
        'ROLE' => $role,
        'DEFAULT_PASSWORD' => ($use_password == 'off' ? false : true),
      ];
      $this->Mbackend->update($username, $data);
      $this->session->set_flashdata('success_message', 'Data backend user berhasil diubah');
      redirect('backend');
    }
  }

  public function delete($username){
    $this->Mbackend->delete(str_replace('_', '@', $username));
    $this->session->set_flashdata('success_message', 'Data backend user berhasil dihapus');
    redirect('backend');
  }
}
?>