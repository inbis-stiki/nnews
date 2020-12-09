<?php
class Mgallery extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getAllGallery(){
    $this->db->order_by('DATE_NEWS','desc');
    $this->db->where('NAME_CATEGORY', 'Galeri');
		$query = $this->db->get('view_news');
		return $query->result();
	}

	public function getGallery($id_news){
    $this->db->select('news.*, news_cover.ID_COVERSTORY');
    $this->db->join('news_cover', 'news_cover.ID_NEWS = news.ID_NEWS', 'left');
		return $this->db->where('news.ID_NEWS', $id_news)->get('news')->row();
  }
  
  public function getAllPictures($id_news){
    return $this->db->where('ID_NEWS', $id_news)->get('galeri')->result();
  }
}
?>