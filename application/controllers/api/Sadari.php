<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Sadari extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){

  }

  public function question_get(){
    $query = $this->db->get('question');

    if($query){
        $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    }else{
        $this->response(['status' => FALSE, 'message' => "Data tidak ditemukan"], REST_Controller::HTTP_OK);
    }
  }

  public function index_post(){
      $email        = $this->post('email');
      $dateSadari   = $this->post('dateSadari');

      if($email != '' && $dateSadari != ''){ //check param valid
          $dataSadari = array(
              'EMAIL'       => $email,
              'DATE_SADARI'  => $dateSadari,
              'created_at'  => date('Y-m-d H:i:s')
          );

          $this->db->insert('sadari', $dataSadari);
          $resIdSadari["ID_SADARI"] = $this->db->insert_id();

          $this->response(['status' => TRUE, 'data' => $resIdSadari]);
      }else{
          $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
      }
  }

  public function detail_post(){
      $idSadari     = $this->post('idSadari');
      $idQuestion   = $this->post('idQuestion');
      $answer       = $this->post('answer');
    
      if($idSadari != '' && $idQuestion != '' && $answer != ''){ // check param valid
        
        $queryCheckDataSadari   = $this->db->where('ID_SADARI', $idSadari)->get('sadari')->row();
        $queryCheckDataQuestion = $this->db->where('ID_QUESTION', $idQuestion)->get('question')->row();

        if($queryCheckDataSadari != null && $queryCheckDataQuestion != null){ // check data idSadari & idQuestion is found
            $dataSadariDetail = array(
                'ID_SADARI'     => $idSadari,
                'ID_QUESTION'   => $idQuestion,
                'ANSWER'        => $answer,
                'created_at'    => date('Y-m-d H:i:s')
            );
    
            $this->db->insert('sadari_detail', $dataSadariDetail);
    
            $this->response(['status' => TRUE, 'message' => 'Data detail sadari berhasil disimpan'], REST_Controller::HTTP_OK);
        }else{
            $this->response(['status' => FALSE, 'message' => 'Data sadari atau question tidak ditemukan'], REST_Controller::HTTP_OK);  
        }
      }else{
          $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
      }
  }

  public function result_put(){
      $idSadari     = $this->put('idSadari');
      $isIndicated  = $this->put('isIndicated');

      if($idSadari != '' && $isIndicated != ''){ // check param valid
        $queryCheckDataSadari = $this->db->where('ID_SADARI', $idSadari)->get('sadari')->row();

        if($queryCheckDataSadari != null){ // check data idSadari is found
            $dataResultSadari = array(
                'IS_INDICATED'  => $isIndicated,
                'updated_at'    => date('Y-m-d H:i:s')
            );
    
            $this->db->where('ID_SADARI', $idSadari)->update('sadari', $dataResultSadari);
    
            $this->response(['status' => TRUE, 'message' => 'Data result sadari berhasil diubah'], REST_Controller::HTTP_OK);
        }else{
            $this->response(['status' => FALSE, 'message' => 'Data sadari tidak ditemukan'], REST_Controller::HTTP_OK);
        }
      }else{
        $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
      }
  }
  
}
?>