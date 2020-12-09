<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coverstory extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mcoverstory');
		$this->load->library('upload');
	}

	public function index(){
    $param['main_content'] = 'coverstory/coverslist';
		$param['page_title'] = 'Cover Story';
		$param['covers_list'] = $this->Mcoverstory->getAllCoverStories();
		$this->load->view('dashboard', $param);
	}

	public function add(){
		$param['main_content'] = 'coverstory/add_coverstory';
		$param['page_title'] = 'Tambahkan Cover Story';
		$this->load->view('dashboard', $param);
	}

	public function create(){
    $id = $this->db->select_max('ID_COVERSTORY', 'id')->get('cover_story')->row()->id;
		$judul = $this->input->post('judul');
		$ringkasan = $this->input->post('ringkasan');
		$config = array(
			'upload_path' => 'images/coverstory',
			'allowed_types' => 'jpg|jpeg|png|gif'
		);
		$this->upload->initialize($config);
		if (empty($judul) || empty($ringkasan) || !$this->upload->do_upload('image')){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('coverstory/add');
		} else {
			$fileData = $this->upload->data();
			$data = array(
        'ID_COVERSTORY' => $id + 1,
				'TITLE_COVERSTORY' => $judul,
				'SUMMARY' => $ringkasan,
				'DATE_COVERSTORY' => date('Y-m-d H:i:s'),
				'IMAGE_COVERSTORY' => $fileData['file_name']
			);
			$insert = $this->Mcoverstory->addCoverStory($data);
			$this->session->set_flashdata('success_message', 'Coverstory berhasil ditambahkan');
			redirect('coverstory');
		}
	}

	public function edit($id_coverstory){
		$param['main_content'] = 'coverstory/edit_coverstory';
		$param['page_title'] = 'Edit Cover Story';
		$param['cover'] = $this->Mcoverstory->getCoverStory($id_coverstory);
		$this->load->view('dashboard', $param);
	}

	public function update(){
		$id_coverstory = $this->input->post('id_coverstory');
		$judul = $this->input->post('judul');
		$ringkasan = $this->input->post('ringkasan');
		$config = array(
			'upload_path' => 'images/coverstory',
			'allowed_types' => 'jpg|jpeg|png|gif'
		);
		$this->upload->initialize($config);
		if (empty($judul) || empty($ringkasan) || empty($old_files) && !$this->upload->do_upload('image')){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('coverstory/edit/' . $id_coverstory);
		} else {
			$fileData = $this->upload->data();
			$data = array(
				'TITLE_COVERSTORY' => $judul,
				'SUMMARY' => $ringkasan,
				'IMAGE_COVERSTORY' => $fileData['file_name']
			);
			$this->Mcoverstory->updateCoverStory($id_coverstory, $data);
			$this->session->set_flashdata('success_message', 'Coverstory berhasil ditambahkan');
			redirect('coverstory');
		}
	}

	public function delete($id){
		$news = $this->Mcoverstory->getCoverStory($id);
		$path = './images/coverstory/' . $news->IMAGE_COVERSTORY;
		unlink($path);
		$this->Mcoverstory->deleteCoverStory($id);
		redirect('coverstory');
	}
}
?>