<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(array('notification', 'upload'));
    $this->load->model(array('Mnews', 'Mtags', 'Mnotifications', 'Mcoverstory', 'Mgallery'));
  }
    
  public function index(){
		$param['main_content'] = 'news/newslist';
		$param['page_title'] = 'Daftar Berita';
		$param['news_list'] = $this->Mnews->getAllNews();
		$this->load->view('dashboard', $param);
  }

	public function add_news(){
		$param['main_content'] = 'news/add_news';
		$param['page_title'] = 'Tambahkan Berita';
		$param['category'] = $this->db->where('ID_CATEGORY <>', 'G')->get('category')->result();
		$param['cover_story'] = $this->Mcoverstory->getAllCoverStories();
		$param['tags'] = $this->Mtags->getAllTags();
		$this->load->view('dashboard', $param);
	}

	public function view($id_news){
		$beritaku = $this->Mnews->viewNews($id_news);
		$param['page_title'] = 'Berita - ' . $beritaku->TITLE_NEWS;
		$param['berita'] = $beritaku;
    $param['tags'] = $this->Mtags->getNewsTags($id_news);
    if ($beritaku->NAME_CATEGORY == 'Galeri'){
      $param['gallery'] = $this->Mgallery->getAllPictures($id_news);
      $this->load->view('view_gallery', $param);
    } else {
      $this->load->view('view_news', $param);
    }

  }
  
  public function detail($id_news){
    $beritaku = $this->Mnews->viewNews($id_news);
    $param['main_content'] = 'news/detail';
		$param['page_title'] = 'Berita - ' . $beritaku->TITLE_NEWS;
		$param['berita'] = $beritaku;
		$param['tags'] = $this->Mtags->getNewsTags($id_news);
		$this->load->view('dashboard', $param);
	}

	public function edit_news($id_news){
		$param['main_content'] = 'news/edit_news';
		$param['page_title'] = 'Edit Berita';
		$param['news'] = $this->Mnews->getNews($id_news);
		$param['tags'] = $this->Mtags->getNewsTags($id_news);
		$param['category'] = $this->db->where('ID_CATEGORY <>', 'G')->get('category')->result();
		$param['cover_story'] = $this->Mcoverstory->getAllCoverStories();
		$param['tags'] = $this->Mtags->getAllTags();
		$param['news_tags'] = $this->Mtags->getNewsTags($id_news);
		$this->load->view('dashboard', $param);
	}

	public function create(){
    $id = $this->db->select_max('ID_NEWS', 'id')->get('news')->row()->id;
		$title = $this->input->post('judul');
		$content = $this->input->post('isi');
		$category = $this->input->post('kategori');
		$coverstory = $this->input->post('coverstory');
		$tags = $this->input->post('tag');
		$config = array(
			'upload_path' => 'images/news',
			'allowed_types' => 'jpg|jpeg|png|gif'
		);
		$this->upload->initialize($config);
		if (empty($title) || empty($content) || empty($category) || empty($tags) || 
			(($category == 'B' || $category == 'A') && !$this->upload->do_upload('files'))){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('news/add_news');
		} else {
      $fileData = $this->upload->data();
      $this->image_resize($fileData);
			$data = array(
        'ID_NEWS' => $id + 1,
				'TITLE_NEWS' => $title,
				'CONTENT_NEWS' => $content,
				'ID_CATEGORY' => $category,
				'VIEWS_COUNT' => 0,
				'SHARES_COUNT' => 0,
        'DATE_NEWS' => date('Y-m-d H:i:s'),
        'USER_EDITOR' => $this->session->userdata('username'),
        'STATUS' => 'draft',
				'NEWS_IMAGE' => $fileData['file_name']
			);
			$this->Mnews->createNews($data);
			if ($coverstory){
				$data = array('ID_COVERSTORY' => $coverstory, 'ID_NEWS' => $id);
				$this->Mcoverstory->addNewsToCoverStory($data);
			}
			$tag = explode(', ', $tags);
			$this->Mtags->insertTags($tag, $id);
			$this->session->set_flashdata('success_message', 'Berita/Artikel berhasil ditambahkan');
			redirect('news');
		}
	}

	public function edit(){
		$id_news = $this->input->post('id_news');
		$title = $this->input->post('judul');
		$content = $this->input->post('isi');
		$category = $this->input->post('kategori');
		$coverstory = $this->input->post('coverstory');
		$tags = $this->input->post('tag');
		$old_files = $this->input->post('old_files');
		$config = array(
			'upload_path' => 'images/news',
			'allowed_types' => 'jpg|jpeg|png|gif'
		);
		$this->upload->initialize($config);
		if (empty($title) || empty($content) || empty($category) || empty($tags)){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('news/edit_news/' . $id_news);
		} else {
			if ($this->upload->do_upload('files')){
        $fileData = $this->upload->data();
        $this->image_resize($fileData);
				$old = $this->db->where('ID_NEWS', $id_news)->get('news')->row();
				$old_files = isset($old->NEWS_IMAGE) ? $old->NEWS_IMAGE : NULL;
				unlink('./images/news/'.$old_files);
			} else {
				$fileData['file_name'] = $old_files;
			}
			$data = array(
				'TITLE_NEWS' => $title,
				'CONTENT_NEWS' => $content,
				'ID_CATEGORY' => $category,
				'NEWS_IMAGE' => $fileData['file_name']
			);
			$this->Mnews->updateNews($id_news, $data);
			if ($coverstory){
				$data = array('ID_COVERSTORY' => $coverstory, 'ID_NEWS' => $id_news);
        if ($this->db->where('ID_NEWS', $id_news)->get('news_cover')->num_rows() == 0){
          $this->Mcoverstory->addNewsToCoverStory($data);
        } else {
          $this->Mcoverstory->updateNewsInCoverStory($id_news, $data);
        }
			} else {
        $this->db->where('ID_NEWS', $id_news)->delete('news_cover');
      }
			$tag = explode(', ', $tags);
			$this->Mtags->updateTags($tag, $id_news);
			$this->session->set_flashdata('success_message', 'Berita/Artikel berhasil diubah');
			redirect('news');
		}
  }
  
  public function verify(){
    $id_news = $this->input->post('id_news');
    $status = $this->input->post('status');
    $data = array(
      'USER_VERIFICATOR' => $this->session->userdata('username'),
      'STATUS' => $status
    );
    $this->Mnews->updateNews($id_news, $data);
    $this->session->set_flashdata('success_message', 'Berita/Artikel telah diverifikasi');
    $news = $this->Mnews->getNews($id_news);
    if ($status == 'published'){
      $applications = $this->Mnotifications->getAllDevice();
			foreach ($applications as $app){
				$this->notification->setTitle($news->TITLE_NEWS);
				$this->notification->setMessage("Digimagz PTPN X");
				$this->notification->setId_news($id_news);
				$request_data = $this->notification->getNotifications();
				$this->notification->pushNotification($app->TOKEN, $request_data);
			}
    }
		redirect('news');
  }
	
	public function delete($id){
		$news = $this->Mnews->getNews($id);
		if (!empty($news->NEWS_IMAGE)){
			$path = './images/news/' . $news->NEWS_IMAGE;
			unlink($path);
		}
		$this->Mnews->deleteNews($id);
		redirect('news');
  }
  
  function image_resize($image_data){
    $this->load->library('image_lib');
    $w = $image_data['image_width'];
    $n_w = 2048; 
    if ($w > 2048){
      $config['image_library'] = 'gd2';
      $config['source_image'] = './images/news/' . $image_data['file_name'];
      $config['maintain_ratio'] = TRUE;
      $config['width'] = $n_w;
      $this->image_lib->initialize($config);
      if (!$this->image_lib->resize()){
          echo $this->image_lib->display_errors();
      } else {
          echo "done";
      }
    }
  }
}
?>