<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(array('notification', 'upload'));
		$this->load->model(array('Mgallery', 'Mnews', 'Mtags', 'Mnotifications', 'Mimage', 'Mcoverstory'));
  }
    
  public function index(){
		$param['main_content'] = 'gallery/view_gallery';
		$param['page_title'] = 'Galeri';
		$param['news_list'] = $this->Mgallery->getAllGallery();
		$this->load->view('dashboard', $param);
	}

	public function add_gallery(){
		$param['main_content'] = 'gallery/add_gallery';
		$param['page_title'] = 'Tambahkan Galeri';
		$param['cover_story'] = $this->Mcoverstory->getAllCoverStories();
		$param['tags'] = $this->Mtags->getAllTags();
		$this->load->view('dashboard', $param);
	}

	public function edit($id_news){
		$param['main_content'] = 'gallery/edit_gallery';
		$param['page_title'] = 'Tambahkan Galeri';
		$param['cover_story'] = $this->Mcoverstory->getAllCoverStories();
		$param['gallery'] = $this->Mgallery->getGallery($id_news);
		$param['tags'] = $this->Mtags->getAllTags();
		$param['news_tags'] = $this->Mtags->getNewsTags($id_news);
		$this->load->view('dashboard', $param);
	}

	public function create(){
    $id = $this->db->select_max('ID_NEWS', 'id')->get('news')->row()->id + 1;
		$title = $this->input->post('judul');
		$content = $this->input->post('isi');
		$coverstory = $this->input->post('coverstory');
		$tags = $this->input->post('tag');
		if (empty($title) || empty($content) || empty($tags)){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('gallery/add_gallery');
		} else {
			$data = array(
        'ID_NEWS' => $id,
				'TITLE_NEWS' => $title,
				'CONTENT_NEWS' => $content,
				'ID_CATEGORY' => 'G',
				'VIEWS_COUNT' => 0,
				'SHARES_COUNT' => 0,
        'DATE_NEWS' => date('Y-m-d H:i:s'),
        'USER_EDITOR' => $this->session->userdata('username'),
        'STATUS' => 'draft'
			);
			$this->Mnews->createNews($data);
			if ($coverstory){
				$data = array('ID_COVERSTORY' => $coverstory, 'ID_NEWS' => $id);
				$this->Mcoverstory->addNewsToCoverStory($data);
			}
			$tag = explode(', ', $tags);
			$this->Mtags->insertTags($tag, $id);
			$images = $this->Mimage->getCurrentTemp();
      if(!is_dir('images/gallery/' . $id)) {
        $oldmask = umask(0);
        mkdir('images/gallery/' . $id, 0777, TRUE);
        umask($oldmask);
      }
			foreach ($images as $i){
				copy('images/temp/' . $i->IMAGE_FILE, 'images/gallery/' . $id . '/' . $i->IMAGE_FILE);
				unlink('images/temp/' . $i->IMAGE_FILE);
				$this->Mimage->insertImage($i->IMAGE_FILE, $id);
			}
			$this->session->set_flashdata('success_message', 'Galeri sukses ditambahkan');
			redirect('gallery');
		}
  }

  public function detail($id_news){
    $beritaku = $this->Mnews->viewNews($id_news);
    $param['main_content'] = 'gallery/detail';
    $param['page_title'] = 'Berita - ' . $beritaku->TITLE_NEWS;
    $param['galeri'] = $this->Mgallery->getAllPictures($id_news);
		$param['berita'] = $beritaku;
		$param['tags'] = $this->Mtags->getNewsTags($id_news);
		$this->load->view('dashboard', $param);
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
		redirect('gallery');
  }

	public function edit_gallery(){
		$id_news = $this->input->post('id_news');
		$title = $this->input->post('judul');
		$content = $this->input->post('isi');
		$coverstory = $this->input->post('coverstory');
		$tags = $this->input->post('tag');
		if (empty($title) || empty($content) || empty($tags)){
			$this->session->set_flashdata('error_message', 'Harap masukkan data dengan benar!');
			redirect('gallery/edit/' . $id_news);
		} else {
			$data = array(
				'TITLE_NEWS' => $title,
				'CONTENT_NEWS' => $content
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
      $images = $this->Mimage->getCurrentTemp();
			foreach ($images as $i){
				copy('images/temp/' . $i->IMAGE_FILE, 'images/gallery/' . $id_news . '/' . $i->IMAGE_FILE);
				unlink('images/temp/' . $i->IMAGE_FILE);
				$this->Mimage->insertImage($i->IMAGE_FILE, $id_news);
			}
			$this->session->set_flashdata('success_message', 'Galeri berhasil diubah');
			redirect('gallery');
		}
	}

	public function upload_image($id_news = ''){
		$countfiles = count($_FILES['files']['name']);
		for ($i = 0; $i < $countfiles; $i++){
			$_FILES['file']['name'] = $_FILES['files']['name'][$i];
			$_FILES['file']['type'] = $_FILES['files']['type'][$i];
			$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
			$_FILES['file']['error'] = $_FILES['files']['error'][$i];
			$_FILES['file']['size'] = $_FILES['files']['size'][$i];
			$config['upload_path'] = 'images/temp';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$this->upload->initialize($config);
			if ($this->upload->do_upload('file')){
        $fileData = $this->upload->data();
        $this->image_resize($fileData);
				$uploadData[$i] = $fileData['file_name'];
			} else {
				echo $this->upload->display_errors();
			}
		}
		if (!empty($uploadData)){
			for ($i = 0; $i < count($uploadData); $i++){
				if ($id_news == ''){
					$this->Mimage->insertImage($uploadData[$i]);
				} else {
					$this->Mimage->insertImage($uploadData[$i], $id_news);
				}
			}
		}
		exit;
	}

	public function getCurrentImage(){
		$file_list = array();
		$id_news = $this->input->post('id_news');
		$dir = './images/gallery/' . $id_news . '/';
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while(($file = readdir($dh)) !== false){
					if ($file != '' && $file != '.' && $file != '..'){
						$file_path = $dir . $file;
						if(!is_dir($file_path)){
							$size = filesize($file_path);
							$file_list[] = array(
								'name' => $file,
								'size' => $size,
								'path' => base_url('images/gallery/' . $id_news . '/' . $file)
							);
						}
					}
				}
				closedir($dh);
			}
			echo json_encode($file_list);
		} else {
			echo "Is not a directory";
		}
		exit;
	}
	
	public function delete($id){
		$path = './images/gallery/' . $id;
		delete_files($path, true, false, 1);
    $this->Mnews->deleteNews($id);
    $this->session->set_flashdata('success_message', 'Galeri berita berhasil dihapus');
		redirect('gallery');
	}

	public function deleteTemp(){
		$id = $this->input->post('image_file');
		$path = 'images/temp/' . $id;
		unlink($path);
		$this->Mimage->deleteImageByName($id);
		exit;
  }
  
  function image_resize($image_data){
    $this->load->library('image_lib');
    $w = $image_data['image_width'];
    $n_w = 2048; 
    if ($w > 2048){
      $config['image_library'] = 'gd2';
      $config['source_image'] = './images/temp/' . $image_data['file_name'];
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