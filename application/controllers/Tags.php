<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mtags');
	}

	public function index(){
    $param['main_content'] = 'tags/tagslist';
		$param['page_title'] = 'Daftar Tag';
		$param['tags_list'] = $this->Mtags->getAllTags();
		$this->load->view('dashboard', $param);
  }

  public function add_tag(){
    $param['main_content'] = 'tags/add_tag';
		$param['page_title'] = 'Tambah Tag Baru';
		$this->load->view('dashboard', $param);
  }
  
  public function insert(){
    $tag = $this->input->post('tag');
    if (empty($tag)){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect('tags/add_tag');
    } else {
      $check = $this->db->where('TAGS', $tag)->get('tags');
      if ($check->num_rows() > 0){
        $this->session->set_flashdata('error_message', 'Tag yang dimasukkan sudah ada');
        redirect('tags/add_tag');
      } else {
        $this->db->insert('tags', [
          'ID_TAGS' => $this->db->select_max('ID_TAGS', 'id')->get('tags')->row()->id + 1,
          'TAGS' => $tag, 
          'DATE_CREATED' => date('Y-m-d H:i:s')
        ]);
        $this->session->set_flashdata('success_message', 'Tag berhasil ditambahkan');
        redirect('tags');
      }
    }
  }

  public function edit_tag($id){
    $param['main_content'] = 'tags/edit_tag';
    $param['page_title'] = 'Edit Tag';
    $param['tag'] = $this->db->where('ID_TAGS', $id)->get('tags')->row();
		$this->load->view('dashboard', $param);
  }

  public function update(){
    $id = $this->input->post('id');
    $tag = $this->input->post('tag');
    if (empty($tag)){
      $this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
      redirect('tags/edit_tag/' . $id);
    } else {
      $check = $this->db->where('TAGS', $tag)->get('tags');
      if ($check->num_rows() > 0){
        $this->session->set_flashdata('error_message', 'Tag yang dimasukkan sudah ada');
        redirect('tags/edit_tag/' . $id);
      } else {
        $this->db->where('ID_TAGS', $id)->update('tags', ['TAGS' => $tag]);
        $this->session->set_flashdata('success_message', 'Tag berhasil diubah');
        redirect('tags');
      }
    }
  }

	public function delete($id_tags){
    $this->Mtags->deleteTags($id_tags);
    $this->session->set_flashdata('success_message', 'Tag berhasil dihapus');
    redirect('tags');
	}
}
?>