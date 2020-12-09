<?php 
class Mvideo extends CI_Model {
  public function __construct() {
		parent::__construct();
		$this->load->database();
  }

  public function getAllVideo(){
    return $this->db->order_by('DATE_PUBLISHED', 'desc')->get('video')->result();
  }

  public function check($id_video){
    return $this->db->where('ID_VIDEO', $id_video)->get('video')->num_rows();
  }

  public function insert($data){
    $this->db->insert('video', $data);
  }

  public function update($id_video, $data){
    $this->db->where('ID_VIDEO', $id_video)->update('video', $data);
    return $this->db->affected_rows();
  }
}
?>