<?php
class Muser extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
  }
  
  public function getAllUsers(){
    return $this->db->get_where('mobile_user', ['ID_ROLE' => '1'])->result();
  }
	
  public function getAllDoctors(){
    return $this->db->get_where('mobile_user', ['ID_ROLE' => '2'])->result();
  }
  public function getDoctor($email){
  	return $this->db->get_where('mobile_user', ['EMAIL' => $email])->row();
  }
  public function getDoctorProfile($email){
  	return $this->db->get_where('profile_user', ['EMAIL' => $email])->row();
  }
  
  public function insertDoctor($param){
  	$this->db->insert('mobile_user', $param['user']);
	$this->db->insert('profile_user', $param['profile']);
  }
	
  public function updateDoctor($param){
	$this->db->where('EMAIL', $param['profile']['EMAIL'])->update('profile_user', $param['profile']);
  	$this->db->where('EMAIL', $param['user']['EMAIL'])->update('mobile_user', $param['user']);
  }
	
//   public function deleteDoctor($param){
//   	$this->db->delete('mobile_user', ['EMAIL']$param['user']);
// 	$this->db->delete('profile_user', $param['profile']);
//   }
	
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
