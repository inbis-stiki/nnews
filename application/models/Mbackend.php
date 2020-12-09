<?php 
class Mbackend extends CI_Model {
  public function __construct() {
		parent::__construct();
		$this->load->database();
  }

  public function getAllUsers(){
    return $this->db->get('backend_user')->result();
  }

  public function getUser($username){
    return $this->db->where('USERNAME', $username)->get('backend_user')->row();
  }

  public function insert($data){
    $this->db->insert('backend_user', $data);
  }

  public function update($username, $data){
    $this->db->where('USERNAME', $username)->update('backend_user', $data);
    return $this->db->affected_rows();
  }

  public function delete($username){
    $this->db->where('USERNAME', $username)->delete('backend_user');
    return $this->db->affected_rows();
  }
}
?>