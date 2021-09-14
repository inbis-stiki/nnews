<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class User extends CI_Controller {

	var $firebase;
	var $auth;

	function __construct(){
		parent::__construct();
		$this->load->model('Muser');
		$this->firebase = (new Factory)
			->withServiceAccount('digimagz-fccc4-firebase-adminsdk-p33y5-8c3d95b194.json')
			->create();
		$this->auth = $this->firebase->getAuth();
	}

	public function index(){
		$data['users'] = $this->Muser->getAllUsers();
		$data['doctors'] = $this->Muser->getAllDoctors();
		$data['main_content'] = 'user_view';
		$data['page_title'] = 'Manajemen Pengguna';
		$this->load->view('dashboard', $data);
	}

	public function loadAllUsers(){
		$users = $this->auth->listUsers();
		$this->Muser->loadAllUserDatas($users);
	}
  	public function add_doctor(){
    		$data['main_content'] = 'add_doctor';
		$data['page_title'] = 'Tambahkan Dokter';
		$this->load->view('dashboard', $data);
  	}
  	public function edit_doctor($email){
    		$data['main_content'] = 'edit_doctor';
    		$data['page_title'] = 'Ubah Data Dokter';
    		$data['doctor'] = $this->Muser->getDoctor($email);
		$data['doctorProfile'] = $this->Muser->getDoctorProfile($email);
		
		if(!empty($data['doctor']->ID_ROLE) && $data['doctor']->ID_ROLE == '1'){
			$this->session->set_flashdata('error_message', 'Anda tidak diizinkan mengakses data user!');
			redirect('user');
		}else if(empty($data['doctor']->ID_ROLE)){
			$this->session->set_flashdata('error_message', 'Data dokter tidak ditemukan!');
			redirect('user');
		}
		
		$this->load->view('dashboard', $data);
  	}
		
	public function storeDoctor(){
		$param = $_POST;
		
		$store['user']['EMAIL'] = $param['EMAIL'];
		$store['user']['ID_ROLE'] = '2';
		$store['user']['NAME'] = $param['NAME'];
		$store['user']['PASSWORD'] = $param['use_password'] == 'on' ? md5('dokterMamo') : md5($param['PASSWORD']);
		
		$store['profile']['EMAIL'] = $param['EMAIL'];
		$store['profile']['PHONE'] = $param['PHONE'];
		$store['profile']['DATE_BIRTH'] = $param['DATE_BIRTH'];
		
		$this->Muser->insertDoctor($store);
		$this->session->set_flashdata('success', 'Berhasil menambahkan dokter baru!');
		redirect('user');
	}
	public function editDoctor(){
		$param = $_POST;
		
		$update['user']['EMAIL'] = $param['EMAIL'];
		$update['user']['ID_ROLE'] = '2';
		$update['user']['NAME'] = $param['NAME'];
		if($param['user_password'] == 'on'){
			$update['user']['PASSWORD'] = md5('dokterMamo');
		}
		
		$update['profile']['EMAIL'] = $param['EMAIL'];
		$update['profile']['PHONE'] = $param['PHONE'];
		$update['profile']['DATE_BIRTH'] = $param['DATE_BIRTH'];
		
		$this->Muser->updateDoctor($update);
		$this->session->set_flashdata('success', 'Berhasil mengubah data dokter!');
		redirect('user');
	}
// 	public function destroyDoctor(){
// 		$param = $_POST;
// 		$this->Muser->deleteDoctor($param);
// 		$this->session->flashdata('success', 'Berhasil menghapus data dokter!');
// 		redirect('user');
// 	}
}
?>
