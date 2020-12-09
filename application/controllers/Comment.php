<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('Mcomments', 'Mnews'));
	}

	public function index(){
    $param['main_content'] = 'comment/commentslist';
		$param['page_title'] = 'Komentar';
		$param['comments_list'] = $this->Mcomments->loadCommentStats();
		$this->load->view('dashboard', $param);
	}

	public function manage($id_news){
		$param['main_content'] = 'comment/manage_comments';
		$param['page_title'] = 'Kelola Komentar';
		$param['news'] = $this->Mnews->getNews($id_news);
		$param['komentar'] = $this->Mcomments->loadComments($id_news);
		$this->load->view('dashboard', $param);
	}

	public function approve($id_comments, $status){
		$data = array('IS_APPROVED' => $status);
    $this->db->where('ID_COMMENT', $id_comments)->update('comments', $data);
    $id = $this->db->where('ID_COMMENT', $id_comments)->get('comments')->row()->ID_NEWS;
    $this->session->set_flashdata('success_message', 'Status komentar berhasil diubah');
		redirect('comment/manage/' . $id);
  }
  
  public function reply(){
    $id_comments = $this->input->post('id_comments');
    $reply = $this->input->post('reply');
    $this->db->where('ID_COMMENT', $id_comments)->update('comments', ['ADMIN_REPLY' => $reply]);
    $this->session->set_flashdata('success_message', 'Komentar berhasil dibalas');
    $id = $this->db->where('ID_COMMENT', $id_comments)->get('comments')->row()->ID_NEWS;
		redirect('comment/manage/' . $id);
  }

  public function delete_reply($id_comments){
    $this->db->where('ID_COMMENT', $id_comments)->update('comments', ['ADMIN_REPLY' => NULL]);
    $this->session->set_flashdata('success_message', 'Balasan komentar berhasil dihapus');
    $id = $this->db->where('ID_COMMENT', $id_comments)->get('comments')->row()->ID_NEWS;
		redirect('comment/manage/' . $id);
  }
}
?>