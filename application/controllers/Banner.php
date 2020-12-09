<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('Mbanners', 'Mnews'));
	}

	public function index(){
    $param['main_content'] = 'banner/bannerlist';
		$param['page_title'] = 'Banner';
		$param['banners_list'] = $this->Mbanners->getAllBanners();
		$this->load->view('dashboard', $param);
	}

	public function add_banner(){
		$param['main_content'] = 'banner/add_banner';
		$param['page_title'] = 'Tambahkan Banner';
		$param['news'] = $this->Mnews->getAllNews();
		$this->load->view('dashboard', $param);
	}
}
?>