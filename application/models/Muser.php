<?php
class Muser extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
  }
  
  public function getAllUsers(){
    return $this->db->get('user')->result();
  }

  public function loadAllUserDatas($users){
    foreach ($users as $u){
      $email = $u->email;
      $check_mail = $this->db->where('EMAIL', $email)->get('user')->num_rows();
      if ($check_mail > 0){
        $data = [
          'LAST_LOGIN' => $u->metadata->lastLoginAt
        ];
        $this->db->where('EMAIL', $email)->update('user', $data);
      } else {
        $data = [
          'EMAIL' => $email,
          'USER_NAME' => $u->displayName,
          'PROFILEPIC_URL' => $u->photoURL,
          'LAST_LOGIN' => $u->metadata->lastLoginAt,
          'UID' => $u->uid
        ];
        $this->db->insert('user', $data);
      }
    }
  }
}
?>