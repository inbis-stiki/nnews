<?php
class Mcoverstory extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

    public function getAllCoverStories(){
        return $this->db->order_by('DATE_COVERSTORY', 'DESC')->get('cover_story')->result_array();
    }

    public function getCoverStory($id_cover){
        return $this->db->where('ID_COVERSTORY', $id_cover)->get('cover_story')->row_array();
    }

    public function addNewsToCoverStory($data){
        $this->db->insert('news_cover', $data);
        return $this->db->affected_rows();
    }

    public function addCoverStory($data){
        $this->db->insert('cover_story', $data);
        $insert = $this->db->where('TITLE_COVERSTORY', $data['TITLE_COVERSTORY'])->get('cover_story')->row_array();
        return $insert['ID_COVERSTORY'];
    }

    public function updateCoverStory($id_coverstory, $data){
        $this->db->where('ID_COVERSTORY', $id_coverstory)->update('cover_story', $data);
        return $this->db->affected_rows();
    }

    public function updateNewsInCoverStory($id_news, $data){
        $this->db->where('ID_NEWS', $id_news)->update('news_cover', $data);
        return $this->db->affected_rows();
    }

    public function deleteCoverStory($id){
        $this->db->where('ID_COVERSTORY', $id)->delete('cover_story');
        return $this->db->affected_rows();
    }
}
?>