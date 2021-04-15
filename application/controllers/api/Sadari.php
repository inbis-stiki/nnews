<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Sadari extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
    $this->load->library(array('upload', 'image_lib'));
  }

  public function index_get(){
    $email      = $this->get('email');
    $limit      = $this->get('limit');
    $search     = $this->get('search');
    $orderBy    = $this->get('orderBy');
    
    if($email != ''){
        $queryCheckDataUser = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
        if($queryCheckDataUser != null){ // check data user is found
            if($queryCheckDataUser->ID_ROLE == '1'){  // if role is user then show sadari by email user
                if($limit != ""){ //condition with limit data 
                    $this->db->limit($limit);
                }

                if($orderBy != ''){ //condition with order_by
                    $this->db->order_by('DATE_SADARI', $orderBy);
                }else{
                    $this->db->order_by('DATE_SADARI', 'desc');
                }
                
                $this->db->select('ID_SADARI, NAME, EMAIL, DATE_SADARI, IS_CHECKED, IS_INDICATED');
                $this->db->where('EMAIL', $email);
                $query = $this->db->get('view_sadari')->result();
                if($query){
                    $this->response(['status' => TRUE, 'data' => $query], REST_Controller::HTTP_OK);
                }else{
                    $this->response(['status' => FALSE, 'message' => "Data sadari tidak ditemukan"], REST_Controller::HTTP_OK);
                }
            }else if($queryCheckDataUser->ID_ROLE == '2'){ // if role is doctor then show sadari is indicated
                if($limit != ""){ //condition with limit data 
                    $this->db->limit($limit);
                }

                if($search != ''){ //condition with search name 
                    $this->db->like('LOWER("NAME")', strtolower($search));
                }
                
                if($orderBy != ''){ //condition with order_by
                    $this->db->order_by('DATE_SADARI', $orderBy);
                }else{
                    $this->db->order_by('DATE_SADARI', 'desc');
                }

                $this->db->select('ID_SADARI, NAME, EMAIL, DATE_SADARI, IS_CHECKED, IS_INDICATED');
                $this->db->where('IS_INDICATED', true);
                $query = $this->db->get('view_sadari')->result();
                if($query){
                    $this->response(['status' => TRUE, 'data' => $query], REST_Controller::HTTP_OK);
                }else{
                    $this->response(['status' => FALSE, 'message' => "Data sadari tidak ditemukan"], REST_Controller::HTTP_OK);
                }

            }
        }else{
            $this->response(['status' => FALSE, 'message' => "Data user tidak ditemukan"], REST_Controller::HTTP_OK);
        }
    }else{
        $this->response(['status' => FALSE, 'message' => "Paramter tidak cocok"], REST_Controller::HTTP_OK);
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

public function resultDetail_get($idSadari){
    $queryCheckDataSadari = $this->db->where('ID_SADARI', $idSadari)->get('sadari_result')->row();
    if($queryCheckDataSadari != null){ // check data sadari is found
        $this->db->select('DOCTOR_NAME, DOCTOR_EMAIL, IMG1_SADARI_RESULT, IMG2_SADARI_RESULT, CONTENT_SADARI_RESULT, DATE_SADARI_RESULT');
        $queryGetDataSadariResult = $this->db->where('ID_SADARI', $idSadari)->order_by('DATE_SADARI_RESULT', 'desc')->get('view_sadari_result')->row();
        if($queryGetDataSadariResult != null){ // check data sadari result is found
            $this->response(['status' => TRUE, 'data' => $queryGetDataSadariResult], REST_Controller::HTTP_OK);
        }else{
            $this->response(['status' => FALSE, 'message' => "Data sadari result tidak ditemukan"], REST_Controller::HTTP_OK);
        }
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
    
    public function resultDetail_post(){
        $idSadari       = $this->post('idSadari');
        $email          = $this->post('email');
        $contentResult  = $this->post('contentResult');
        $dateResult     = $this->post('dateResult');

        if($idSadari != '' && $email != '' && $contentResult != '' && $dateResult != ''){
            $queryCheckDataSadari   = $this->db->where('ID_SADARI', $idSadari)->get('sadari')->row();
            $queryCheckDataUser     = $this->db->where('EMAIL', $email)->get('mobile_user')->row();

            if($queryCheckDataSadari != null && $queryCheckDataUser != null){ // check data user or sadari is found
                $dataSadari = array(
                    'IS_CHECKED'  => true,
                    'updated_at'  => date('Y-m-d H:i:s')
                );
                
                $dataSadariResult = array (
                    'ID_SADARI'             => $idSadari,
                    'EMAIL'                 => $email,
                    'CONTENT_SADARI_RESULT' => $contentResult,
                    'DATE_SADARI_RESULT'    => $dateResult,
                    'created_at'            => date("Y-m-d H:i:s")
                );
                
                $this->db->where('ID_SADARI', $idSadari)->update('sadari', $dataSadari); // update is_indicated to true on sadari table

                $this->db->insert('sadari_result', $dataSadariResult);
                $resIdSadariResult["ID_SADARI_RESULT"] = $this->db->insert_id();

                $this->response(['status' => TRUE, 'data' => $resIdSadariResult], REST_Controller::HTTP_OK);
            }else{
                $this->response(['status' => FALSE, 'message' => 'Data sadari atau user tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
        }
        
    }
    
    public function imageSadari_post(){
        $idSadari = $this->post('idSadari');
        
        if($idSadari != '' && (!empty($_FILES['img1']) || !empty($_FILES['img2']))){
            $queryCheckDataSadari = $this->db->where('ID_SADARI', $idSadari)->get('sadari')->row();
            if($queryCheckDataSadari != null){ // check data sadari result is found
                $config = ['upload_path' => './images/sadari/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];            
                $this->upload->initialize($config);

                if(!empty($_FILES['img1']) && $_FILES['img1']['name'] != ''){ // check if data image1 is not null
                    $check = $this->db->select('IMG1_SADARI')->where('ID_SADARI', $idSadari)->get('sadari')->row();
                    if (isset($check->IMG1_SADARI)){ // check if image is found then unlink or remove
                        unlink('./images/sadari/' . explode('/', $check->IMG1_SADARI)[5]);
                    }
                    if($this->upload->do_upload('img1')){
                        $dataUpload                 = $this->upload->data();
                        $upload['img1']['status']   = TRUE;
                        $upload['img1']['message']  = 'Data image berhasil diupload';
                        $this->db->where('ID_SADARI', $idSadari)->update('sadari', ['IMG1_SADARI' => base_url('images/sadari/' . $dataUpload['file_name'])]);
                    }else{
                        $upload['img1']['status']  = FALSE;
                        $upload['img1']['message'] = strip_tags($this->upload->display_errors());
                    }
                }else{
                    $upload['img1']['status']   = TRUE;
                    $upload['img1']['message']  = "Data tidak ada yang diupdate / diupload";
                }
                
                if(!empty($_FILES['img2']) && $_FILES['img2']['name'] != ''){ // check if data image2 is not null
                    $check = $this->db->select('IMG2_SADARI')->where('ID_SADARI', $idSadari)->get('sadari')->row();
                    if (isset($check->IMG2_SADARI)){ // check if image is found then unlink or remove
                        unlink('./images/sadari/' . explode('/', $check->IMG2_SADARI)[5]);
                    }
                    if($this->upload->do_upload('img2')){
                        $dataUpload                 = $this->upload->data();
                        $upload['img2']['status']   = TRUE;
                        $upload['img2']['message']  = "Data image berhasil diupload";
                        $this->db->where('ID_SADARI', $idSadari)->update('sadari', ['IMG2_SADARI' => base_url('images/sadari/' . $dataUpload['file_name'])]);
                    }else{
                        $upload['img2']['status']   = FALSE;
                        $upload['img2']['message']  = strip_tags($this->upload->display_errors());
                    }
                }else{
                    $upload['img2']['status']   = TRUE;
                    $upload['img2']['message']  = "Data tidak ada yang diupdate / diupload";
                }

                $this->response($upload, REST_Controller::HTTP_OK);
            }else{
                $this->response(['status' => FALSE, 'message' => 'Data sadari tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
        }
      } 
    public function imageSadariResult_post(){
        $idSadariResult = $this->post('idSadariResult');
        
        if($idSadariResult != '' && (!empty($_FILES['img1']) || !empty($_FILES['img2']))){
            $queryCheckDataSadariResult = $this->db->where('ID_SADARI_RESULT', $idSadariResult)->get('sadari_result')->row();
            if($queryCheckDataSadariResult != null){ // check data sadari result is found
                $config = ['upload_path' => './images/sadariResult/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];            
                $this->upload->initialize($config);

                if(!empty($_FILES['img1']) && $_FILES['img1']['name'] != ''){ // check if data image1 is not null
                    $check = $this->db->select('IMG1_SADARI_RESULT')->where('ID_SADARI_RESULT', $idSadariResult)->get('sadari_result')->row();
                    if (isset($check->IMG1_SADARI_RESULT)){ // check if image is found then unlink or remove
                        unlink('./images/sadariResult/' . explode('/', $check->IMG1_SADARI_RESULT)[5]);
                    }
                    if($this->upload->do_upload('img1')){
                        $dataUpload                 = $this->upload->data();
                        $upload['img1']['status']   = TRUE;
                        $upload['img1']['message']  = 'Data image berhasil diupload';
                        $this->db->where('ID_SADARI_RESULT', $idSadariResult)->update('sadari_result', ['IMG1_SADARI_RESULT' => base_url('images/sadariResult/' . $dataUpload['file_name'])]);
                    }else{
                        $upload['img1']['status']  = FALSE;
                        $upload['img1']['message'] = strip_tags($this->upload->display_errors());
                    }
                }else{
                    $upload['img1']['status']   = TRUE;
                    $upload['img1']['message']  = "Data tidak ada yang diupdate / diupload";
                }
                
                if(!empty($_FILES['img2']) && $_FILES['img2']['name'] != ''){ // check if data image2 is not null
                    $check = $this->db->select('IMG2_SADARI_RESULT')->where('ID_SADARI_RESULT', $idSadariResult)->get('sadari_result')->row();
                    if (isset($check->IMG2_SADARI_RESULT)){ // check if image is found then unlink or remove
                        unlink('./images/sadariResult/' . explode('/', $check->IMG2_SADARI_RESULT)[5]);
                    }
                    if($this->upload->do_upload('img2')){
                        $dataUpload                 = $this->upload->data();
                        $upload['img2']['status']   = TRUE;
                        $upload['img2']['message']  = "Data image berhasil diupload";
                        $this->db->where('ID_SADARI_RESULT', $idSadariResult)->update('sadari_result', ['IMG2_SADARI_RESULT' => base_url('images/sadariResult/' . $dataUpload['file_name'])]);
                    }else{
                        $upload['img2']['status']   = FALSE;
                        $upload['img2']['message']  = strip_tags($this->upload->display_errors());
                    }
                }else{
                    $upload['img2']['status']   = TRUE;
                    $upload['img2']['message']  = "Data tidak ada yang diupdate / diupload";
                }

                $this->response($upload, REST_Controller::HTTP_OK);
            }else{
                $this->response(['status' => FALSE, 'message' => 'Data sadari result tidak ditemukan'], REST_Controller::HTTP_OK);
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