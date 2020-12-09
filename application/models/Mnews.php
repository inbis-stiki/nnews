<?php
class Mnews extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function createNews($data){
		$this->db->insert('news', $data);
		$insert = $this->db->where('TITLE_NEWS', $data['TITLE_NEWS'])->get('news')->row_array();
		return $insert['ID_NEWS'];
	}

	function viewNews($id){
		return $this->db->where('ID_NEWS', $id)->get('view_news')->row();
	}

	function updateNews($id_news, $data){
		$this->db->where('ID_NEWS', $id_news)->update('news', $data);
		return $this->db->affected_rows();
	}

	function deleteNews($id){
    $this->db->where('ID_NEWS', $id)->delete('news_view');
    $this->db->where('ID_NEWS', $id)->delete('news_share');
    $this->db->where('ID_NEWS', $id)->delete('news');
		return $this->db->affected_rows();
	}

	function getAllNews($with_gallery = false){
    $this->db->order_by('DATE_NEWS','desc');
    if (!$with_gallery){
      $this->db->where('NAME_CATEGORY <>', 'Galeri');
    }
		$query = $this->db->get('view_news');
		return $query->result();
	}

	function getNews($id){
    $this->db->select('news.*, news_cover.ID_COVERSTORY');
    $this->db->join('news_cover', 'news_cover.ID_NEWS = news.ID_NEWS', 'left');
		return $this->db->where('news.ID_NEWS', $id)->get('news')->row();
	}

	function clickViews($idne, $email){
    $num_rows = $this->db->select_max('ID', 'id')->get('news_view')->row()->id + 1;
    $this->db->insert('news_view', ['ID' => $num_rows + 1, 'EMAIL' => $email, 'ID_NEWS' => $idne]);
    $click = $this->db->where('ID_NEWS', $idne)->get('news')->row();
    $this->db->where('ID_NEWS', $idne)->update('news', ['VIEWS_COUNT' => ($click->VIEWS_COUNT + 1)]);
    return $click;
  }
  
  function clickShare($idne, $email){
    $num_rows = $this->db->select_max('ID', 'id')->get('news_view')->row()->id + 1;
    $this->db->insert('news_share', ['ID' => $num_rows + 1, 'EMAIL' => $email, 'ID_NEWS' => $idne]);
    $share = $this->db->where('ID_NEWS', $idne)->get('news')->row();
    $this->db->where('ID_NEWS', $idne)->update('news', ['SHARES_COUNT' => ($share->SHARES_COUNT + 1)]);
    return $share;
	}
}
?>