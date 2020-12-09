<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emagz extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->model('Memagz');
    $this->load->library('upload');
    $this->load->helper('download');
	}

	public function index(){
    $param['main_content'] = 'emagz/emagz_list';
		$param['page_title'] = 'E-Magazine';
		$param['emagz'] = $this->Memagz->getAll();
		$this->load->view('dashboard', $param);
  }

  public function add(){
    $param['main_content'] = 'emagz/add_emagz';
		$param['page_title'] = 'Tambah E-Magazine Baru';
		$this->load->view('dashboard', $param);
  }
  
  public function insert(){
    $judul = $this->input->post('judul');
    $config_emagz = ['upload_path' => './emagazine/files/', 'allowed_types' => 'pdf'];
    $config_thumbnail = ['upload_path' => './emagazine/thumbnail/', 'allowed_types' => 'jpg|jpeg|png|gif'];
    $this->upload->initialize($config_emagz);
    if (empty($judul) || !$this->upload->do_upload('emagz')){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect(base_url('emagz/add'));
    } else {
      $emagz = $this->upload->data();
      $this->upload->initialize($config_thumbnail);
      if ($this->upload->do_upload('files')){
        $thumbnail = $this->upload->data();
        $this->Memagz->insert([
          'NAME' => $judul,
          'THUMBNAIL' => $thumbnail['file_name'],
          'FILE' => $emagz['file_name'],
          'DATE_UPLOADED' => date('Y-m-d H:i:s')
        ]);
        $this->session->set_flashdata('success_message', 'E-Magazine berhasil ditambahkan');
        redirect(base_url('emagz'));
      } else {
        $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
        redirect(base_url('emagz/add'));
      }
    }
  }

  public function edit($id){
    $param['main_content'] = 'emagz/edit_emagz';
    $param['page_title'] = 'Edit E-Magazine';
    $param['emagz'] = $this->Memagz->get($id);
		$this->load->view('dashboard', $param);
  }

  public function update(){
    $id_emagz = $this->input->post('id_emagz');
    $judul = $this->input->post('judul');
    $config_thumbnail = ['upload_path' => './emagazine/thumbnail/', 'allowed_types' => 'jpg|jpeg|png|gif'];
    $this->upload->initialize($config_thumbnail);
    if (empty($judul) || (empty($old_files) && !$this->upload->do_upload('files'))){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect(base_url('emagz/edit/' . $id_emagz));
    } else {
      if ($this->upload->do_upload('files')){
				$fileData = $this->upload->data();
				$old_files = $this->db->where('ID_EMAGZ', $id_news)->get('emagz')->THUMBNAIL;
				unlink('./emagazine/thumbnail/'.$old_files);
			} else {
				$fileData['file_name'] = $old_files;
			}
      $this->Memagz->update($id_emagz, ['NAME' => $judul, 'THUMBNAIL' => $fileData['file_name']]);
      $this->session->set_flashdata('success_message', 'E-Magazine berhasil diubah');
      redirect(base_url('emagz'));
    }
  }

  public function download($id){
    $files = $this->db->where('ID_EMAGZ', $id)->get('emagz')->row()->FILE;
    force_download('./emagazine/files/'.$files, NULL);
  }

  public function delete_files($id){
    $old_files = $this->db->where('ID_EMAGZ', $id)->get('emagz')->row()->FILE;
    unlink('./emagazine/files/'.$old_files);
    $this->Memagz->update($id, ['FILE' => NULL]);
    $this->session->set_flashdata('success_message', 'File berhasil dihapus');
    redirect(base_url('emagz/edit/' . $id));
  }

  public function update_with_emagz(){
    $id_emagz = $this->input->post('id_emagz');
    $judul = $this->input->post('judul');
    $old_files = $this->input->post('old_files');
    $config_emagz = ['upload_path' => './emagazine/files/', 'allowed_types' => 'pdf'];
    $config_thumbnail = ['upload_path' => './emagazine/thumbnail/', 'allowed_types' => 'jpg|jpeg|png|gif'];
    $this->upload->initialize($config_emagz);
    if (empty($judul) || !$this->upload->do_upload('emagz')){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect(base_url('emagz/add'));
    } else {
      $emagz = $this->upload->data();
      $this->upload->initialize($config_thumbnail);
      if (empty($old_files) && !$this->upload->do_upload('files')){
        $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
        redirect(base_url('emagz/edit/' . $id_emagz));
      } else {
        if ($this->upload->do_upload('files')){
          $fileData = $this->upload->data();
          $old_files = $this->db->where('ID_EMAGZ', $id_news)->get('emagz')->THUMBNAIL;
          unlink('./emagazine/thumbnail/'.$old_files);
        } else {
          $fileData['file_name'] = $old_files;
        }
        $this->Memagz->update($id_emagz, ['NAME' => $judul, 'FILE' => $emagz['file_name'], 'THUMBNAIL' => $fileData['file_name']]);
        $this->session->set_flashdata('success_message', 'E-Magazine berhasil diubah');
        redirect(base_url('emagz'));
      }
    }
  }

	public function delete($id){
    $emagz = $this->Memagz->get($id);
    unlink('./emagazine/thumbnail/' . $emagz->THUMBNAIL);
    unlink('./emagazine/files/' . $emagz->FILE);
    $this->Memagz->delete($id);
    $this->session->set_flashdata('success_message', 'E-Magazine berhasil dihapus.');
    redirect(base_url('emagz'));
	}
}
?>