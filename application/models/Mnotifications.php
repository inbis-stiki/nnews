<?php
class Mnotifications extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function insertToken($token){
		$this->db->insert('firebase_token', $token);
		return TRUE;
	}

	function getAllDevice(){
		$query = $this->db->get('firebase_token');
		return $query->result();
	}

	function checkTokenExists($data){
		return $this->db->where($data)->get('firebase_token')->num_rows();
	}
}
?>