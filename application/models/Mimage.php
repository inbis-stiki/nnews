<?php
class Mimage extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
    }
    
    public function getCurrentTemp(){
        $this->db->where('ID_NEWS', NULL);
        return $this->db->get('galeri')->result();
    }

    public function insertImage($image_file, $id_news = ''){
        if ($id_news == ''){
            $data = array('IMAGE_FILE' => $image_file);
            $this->db->insert('galeri', $data);
        } else {
            if ($this->db->where('IMAGE_FILE', $image_file)->get('galeri')->num_rows() > 0){
              $data = array('ID_NEWS' => $id_news);
              $this->db->where('IMAGE_FILE', $image_file)->update('galeri', $data);
            } else {
              $data = array('IMAGE_FILE' => $image_file, 'ID_NEWS' => $id_news);
              $this->db->insert('galeri', $data);
            }
        }
    }

    public function deleteImageByName($image_name){
        $this->db->where('IMAGE_FILE', $image_name)->delete('galeri');
        return $this->db->affected_rows();
    }

    public function deleteImage($id_news){
        $this->db->where('ID_NEWS', $id_news)->delete('galeri');
        return $this->db->affected_rows();
    }
}
?>