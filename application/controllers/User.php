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
		$data['main_content'] = 'user_view';
		$data['page_title'] = 'Manajemen Pengguna';
		$this->load->view('dashboard', $data);
	}

	public function loadAllUsers(){
		$users = $this->auth->listUsers();
		$this->Muser->loadAllUserDatas($users);
	}
}
?>