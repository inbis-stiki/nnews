<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Sadari extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
  }

  public function index_get(){
    $limit = $this->get('limit');
    $search = $this->get('search');
    if($limit != ""){ //condition with limit data 
        $this->db->limit($limit);
    }
    if($search != ''){ //condition with search name 
        $this->db->like('LOWER("NAME")', strtolower($search));
      }
    $this->db->select('ID_SADARI, NAME, EMAIL, DATE_SADARI, IS_CHECKED, IS_INDICATED');
    $query = $this->db->get('view_sadari')->result();
    if($query){
        $this->response(['status' => TRUE, 'data' => $query], REST_Controller::HTTP_OK);
    }else{
        $this->response(['status' => FALSE, 'message' => "Data sadari tidak ditemukan"], REST_Controller::HTTP_OK);
    }
}

public function detail_get($idSadari){
    $queryCheckDataSadari = $this->db->where("ID_SADARI", $idSadari)->get("sadari")->row();
    if($queryCheckDataSadari != null){
        $queryGetDataSadari     = $this->db->where("ID_SADARI", $idSadari)->get('view_sadari')->result();

        $this->db->select("CONTENT_QUESTION, ANSWER");
        $queryGetDetailSadari   = $this->db->where("ID_SADARI", $idSadari)->order_by("ID_QUESTION", 'ASC')->get('view_sadari_detail')->result();
        $resDetailSadari = array(
            "data_sadari"        => $queryGetDataSadari,
            "data_sadari_detail" => $queryGetDetailSadari
        );
        
        // $resDetailSadari = $queryCheckDataSadari;
        // $resDetailSadari['detail_get'] = $queryGetDetailSadari;
        // $resDetailSadari['detail_sadari'] = $queryGetDetailSadari;
        // $tes['detail_get'] = $resDetailSadari;
        $this->response(['status' => TRUE, 'data' => $resDetailSadari], REST_Controller::HTTP_OK);
    }else{
        $this->response(['status' => FALSE, 'message' => "Data sadari tidak ditemukan"], REST_Controller::HTTP_OK);
    }
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