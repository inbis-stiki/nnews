<?php 
class Mcaptcha extends CI_Model {
  function buat_captcha(){
    $this->load->helper('captcha');
    $captcha = array(
      'img_path' => './captcha/',
      'img_url' => base_url() . 'captcha/',
      'img_width' => '150',
      'img_height' => '50',
      'word_length' => 4,
      'font_size' => 16,
      'expiration' => 3600);
    $capt = create_captcha($captcha);
    $data = array(
      'CAPTCHA_TIME' => $capt['time'],
      'IP_ADDRESS' => $this->input->ip_address(),
      'WORD' => $capt['word']
    );
    $query = $this->db->insert_string('captcha', $data);
    $this->db->query($query);
    return $capt;
  }

  function validate($captcha){
    $expiration = time() - 3600;
    $this->db->where('CAPTCHA_TIME <', $expiration)->delete('captcha');
    $this->db->where('WORD', $captcha)->where('IP_ADDRESS', $this->input->ip_address())->where('CAPTCHA_TIME >', $expiration);
    $row = $this->db->get('captcha')->num_rows();
    if ($row > 0){
      return true;
    } else {
      return false;
    }
  }
}
?>