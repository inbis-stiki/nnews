<?php
class Mtags extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
  }
  
  public function getAllTags(){
    return $this->db->order_by('DATE_CREATED', 'DESC')->get('tags')->result_array();
  }

  public function getNewsTags($id_news){
    $news_tags = array();
    $this->db->select('tags.TAGS');
    $this->db->from('tags');
    $this->db->join('news_tags', 'news_tags.ID_TAGS = tags.ID_TAGS');
    $this->db->where('news_tags.ID_NEWS', $id_news);
    $result = $this->db->get()->result();
    foreach ($result as $r){
      array_push($news_tags, $r->TAGS);
    }
    return $news_tags;
  }

	public function insertTags($tags, $id_news){
    $id_tags = array();
    for ($i = 0; $i < count($tags); $i++){
      $check_tag = $this->db->where('TAGS', $tags[$i])->get('tags');
      if ($check_tag->num_rows() > 0){
        $tag_id = $check_tag->row_array();
        array_push($id_tags, $tag_id['ID_TAGS']);
      } else {
        $data = array('ID_TAGS' => $this->db->select_max('ID_TAGS', 'id')->get('tags')->row()->id + 1, 'TAGS' => $tags[$i], 'DATE_CREATED' => date('Y-m-d H:i:s'));
        $this->db->insert('tags', $data);
        $insert = $this->db->where('TAGS', $tags[$i])->get('tags')->row_array();
        array_push($id_tags, $insert['ID_TAGS']);
      }
    }
    for ($i = 0; $i < count($id_tags); $i++){
      $data = array('ID_TAGS' => $id_tags[$i], 'ID_NEWS' => $id_news);
      $this->db->insert('news_tags', $data);
    }
    return $this->db->affected_rows();
  }

  public function updateTags($tags, $id_news){
    $this->db->where('ID_NEWS', $id_news)->delete('news_tags');
    $id_tags = array();
    for ($i = 0; $i < count($tags); $i++){
      $check_tag = $this->db->where('TAGS', $tags[$i])->get('tags');
      if ($check_tag->num_rows() > 0){
        $tag_id = $check_tag->row_array();
        array_push($id_tags, $tag_id['ID_TAGS']);
      } else {
        $data = array('ID_TAGS' => $this->db->select_max('ID_TAGS', 'id')->get('tags')->row()->id + 1, 'TAGS' => $tags[$i], 'DATE_CREATED' => date('Y-m-d H:i:s'));
        $this->db->insert('tags', $data);
        $insert = $this->db->where('TAGS', $tags[$i])->get('tags')->row_array();
        array_push($id_tags, $insert['ID_TAGS']);
      }
    }
    for ($i = 0; $i < count($id_tags); $i++){
      $data = array('ID_TAGS' => $id_tags[$i], 'ID_NEWS' => $id_news);
      $this->db->insert('news_tags', $data);
    }
    return $this->db->affected_rows();
  }
  
  public function deleteTags($id_tags){
    $this->db->where('ID_TAGS', $id_tags)->delete('tags');
  }
}
?>