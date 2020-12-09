<?php
class Memagz extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
  }
    
  public function getAll(){
    return $this->db->get('emagz')->result();
  }

  public function get($id){
    return $this->db->where('ID_EMAGZ', $id)->get('emagz')->row();
  }

  public function insert($data){
    $this->db->insert('emagz', $data);
    return $this->db->where('NAME', $data['NAME'])->get('emagz')->row()->ID_EMAGZ;
  }

  public function update($id, $data){
    $this->db->where('ID_EMAGZ', $id)->update('emagz', $data);
    return $this->db->affected_rows();
  }

  public function delete($id){
    $this->db->where('ID_EMAGZ', $id)->delete('emagz');
    return $this->db->affected_rows();
  }
}
?>