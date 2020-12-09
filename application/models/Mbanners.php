<?php
class Mbanners extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

    public function getAllBanners(){
        return $this->db->get('view_news_banner')->result_array();
    }
}
?>