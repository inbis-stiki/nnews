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
    $queryCheckDataSadari = $this->db->where('ID_SADARI', $idSadari)->get('sadari')->row();
    if($queryCheckDataSadari != null){ // check data sadari is found
        $this->db->select('DOCTOR_NAME, DOCTOR_EMAIL, IMG1_SADARI_RESULT, IMG2_SADARI_RESULT, CONTENT_SADARI_RESULT, DATE_SADARI_RESULT');
        $queryGetDataSadariResult = $this->db->where('ID_SADARI', $idSadari)->get('view_sadari_result')->row();
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
                $dataSadariResult = array (
                    'ID_SADARI'             => $idSadari,
                    'EMAIL'                 => $email,
                    'CONTENT_SADARI_RESULT' => $contentResult,
                    'DATE_SADARI_RESULT'    => $dateResult,
                    'created_at'            => date("Y-m-d H:i:s")
                );

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
    
    public function uploadImage_put(){
        $idSadariResult = $this->post('idSadariResult');
        $image1 = $_FILES['image1'];
        $image2 = $_FILES['image2'];
        
        if($idSadariResult != '' && ($image1 != '' || $image2 != '')){
            
            $this->response(['status' => TRUE, 'message' => 'Kusam'], REST_Controller::HTTP_OK);
        }else{
            $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
        }
        // $config = ['upload_path' => './images/sadariResult/', 'allowed_types' => 'jpg|png|jpeg', 'max_size' => 1024];
        
        // $this->upload->initialize($config);
        // // list($width, $height, $type, $attr) = getimagesize($_FILES['image1']['tmp_name']);
        // // if ($width != $height){
        // //     $config['source_image'] = $_FILES['image1']['tmp_name'];
        // //     $config['x_axis'] = ($width-min($width, $height))/2;
        // //     $config['y_axis'] = ($height-min($width, $height))/2;
        // //     $config['maintain_ratio'] = FALSE;
        // //     $config['width'] = min($width, $height);
        // //     $config['height'] = min($width, $height);
        // //     $this->image_lib->initialize($config);
        // //     $this->image_lib->crop();
        // // }
        // // $check = $this->db->select('PROFILEPIC_URL')->where('EMAIL', $email)->get('user')->row();
        // // if (isset($check->PROFILEPIC_URL)){
        // //   if (strpos($check->PROFILEPIC_URL, 'http://') !== false){
        // //     unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[5]);
        // //   } else {
        // //     unlink('./images/users/' . explode('/', $check->PROFILEPIC_URL)[3]);
        // //   }
        // // }
        // if($this->upload->do_upload('image1')){
        //     $upload = $this->upload->data();
        //     $this->response(['status' => FALSE, 'message' => $_FILES['image1'], 'final' => $upload], REST_Controller::HTTP_OK);
        // }else{
        //     $this->response(['status' => FALSE, 'message' => strip_tags($this->upload->display_errors())], 404);
        // }
        // if ($this->upload->do_upload('picture')){
        //   $upload = $this->upload->data();
        //   $this->db->where('EMAIL', $email)->update('user', ['PROFILEPIC_URL' => base_url('images/users/' . $upload['file_name'])]);
        //   $this->response(['status' => TRUE, 'message' => base_url('images/users/' . $upload['file_name'])], 200);
        // } else {
        //   $this->response(['status' => FALSE, 'message' => strip_tags($this->upload->display_errors())], 404);
        // }
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