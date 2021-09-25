<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class News extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Mnews");
    $this->load->helper("url");
  }

  function index_get() {
    $search = $this->get('search');
    $limit = $this->get('limit');
    
    $this->db->select('ID_NEWS, NAME_CATEGORY, TITLE_NEWS, NEWS_IMAGE, DATE_NEWS, EDITOR, VERIFICATOR');
    $this->db->where('STATUS', 'published');
    if($search != ''){ //condition with search title news
      $this->db->like('LOWER("TITLE_NEWS")', strtolower($search));
    }
    if($limit != ''){ // condition with limit data
      $this->db->limit($limit);
    }
    $this->db->order_by('DATE_NEWS', 'DESC');

    $query = $this->db->get('view_news')->result();
    
    if($query != null){
      // foreach ($query->result() as $q){
      //   if ($q->NAME_CATEGORY == 'Galeri'){
      //     $images = [];
      //     $news_image = $this->db->where('ID_NEWS', $q->ID_NEWS)->get('galeri')->result();
      //     foreach ($news_image as $ni){
      //       array_push($images, $ni->IMAGE_FILE);
      //     }
      //     $q->NEWS_IMAGE = $images;
      //   } else {
      //     if (isset($q->NEWS_IMAGE)){
      //       $q->NEWS_IMAGE = [$q->NEWS_IMAGE];
      //     }
      //   }
      // }
      $this->response(['status' => TRUE, 'data' => $query], REST_Controller::HTTP_OK);
    }else{
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }

    // $id = $this->get('q');
    // $trending = $this->get('trend');
    // $this->db->distinct()->select('view_news.*')->from('view_news');
    // $this->db->join('news_tags', 'news_tags."ID_NEWS" = view_news."ID_NEWS"', 'inner');
    // $this->db->join('tags', 'news_tags."ID_TAGS" = tags."ID_TAGS"', 'inner');
    // $this->db->where('view_news."STATUS"', 'published');
    // if ($id != ''){
    //   $this->db->where('view_news."TITLE_NEWS" ILIKE', '%' . $id . '%')->or_where('tags."TAGS" ILIKE', '%' . $id . '%');
    // }
    // if ($trending != ''){
    //   $this->db->order_by('view_news."VIEWS_COUNT"','desc');
    // }
    // $query = $this->db->order_by('view_news."DATE_NEWS"', 'desc')->get();
    // if($query) {
    //   foreach ($query->result() as $q){
    //     if ($q->NAME_CATEGORY == 'Galeri'){
    //       $images = [];
    //       $news_image = $this->db->where('ID_NEWS', $q->ID_NEWS)->get('galeri')->result();
    //       foreach ($news_image as $ni){
    //         array_push($images, $ni->IMAGE_FILE);
    //       }
    //       $q->NEWS_IMAGE = $images;
    //     } else {
    //       if (isset($q->NEWS_IMAGE)){
    //         $q->NEWS_IMAGE = [$q->NEWS_IMAGE];
    //       }
    //     }
    //   }
    //   $this->response(['status' => TRUE, 'data' => $query->num_rows() > 0 ? $query->result() : []], REST_Controller::HTTP_OK);
    // } else {
    //   $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    // }
  }

  function detail_get($idNews) {
    $this->db->select('ID_NEWS, NAME_CATEGORY, TITLE_NEWS, CONTENT_NEWS, VIEWS_COUNT, SHARES_COUNT, DATE_NEWS, NEWS_IMAGE, EDITOR, VERIFICATOR');
    $condition = array('STATUS' => 'published', 'ID_NEWS' => $idNews);
    $this->db->where($condition);
    $this->db->order_by('DATE_NEWS', 'DESC');

    $query = $this->db->get('view_news')->row();
    if($query){
      // if ($query->NAME_CATEGORY == 'Galeri'){
      //   $images = [];
      //   $news_image = $this->db->where('ID_NEWS', $query->ID_NEWS)->get('galeri')->result();
      //   foreach ($news_image as $ni){
      //     array_push($images, $ni->IMAGE_FILE);
      //   }
      //   $query->NEWS_IMAGE = $images;
      // } else {
      //   if (isset($query->NEWS_IMAGE)){
      //     $query->NEWS_IMAGE = [$query->NEWS_IMAGE];
      //   }
      // }
      $this->response(['status' => TRUE, 'data' => isset($query) ? $query : []]);
    }else{
      $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    }

    // $query = $this->db->where('view_news."ID_NEWS"', $id)->get('view_news')->row();
    // if($query) {
    //   if ($query->NAME_CATEGORY == 'Galeri'){
    //     $images = [];
    //     $news_image = $this->db->where('ID_NEWS', $query->ID_NEWS)->get('galeri')->result();
    //     foreach ($news_image as $ni){
    //       array_push($images, $ni->IMAGE_FILE);
    //     }
    //     $query->NEWS_IMAGE = $images;
    //   } else {
    //     if (isset($query->NEWS_IMAGE)){
    //       $query->NEWS_IMAGE = [$query->NEWS_IMAGE];
    //     }
    //   }
    //   $this->response(['status' => TRUE, 'data' => isset($query) ? $query : []], REST_Controller::HTTP_OK);
    // } else {
    //   $this->response(['status' => FALSE, 'message' => "data tidak ditemukan"], REST_Controller::HTTP_OK);
    // }
  }

  function trending_get(){
    $limit    = $this->get('limit');
    if($limit != ''){ // condition with limit data result
      $this->db->limit($limit);
    }

    $queryListNewsTrending = $this->db->get('view_news_trending')->result();

    if($queryListNewsTrending != null){
      $this->response(['status' => TRUE, 'data' => $queryListNewsTrending], REST_Controller::HTTP_OK);
    }else{
      $this->response(['status' => FALSE, 'message' => 'Data trending news tidak ditemukan'], REST_Controller::HTTP_OK);
    }

  }

  function click_post() {
    $email  = $this->post('email');
    $idNews = $this->post('idNews');

    if($email != '' && $idNews != ''){
      $queryCheckDataUser = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
      $queryCheckDataNews = $this->db->where('ID_NEWS', $idNews)->get('news')->row();

      if($queryCheckDataNews != null && $queryCheckDataUser != null){ //check data email & idNews is found
        $dataNewsView = array(
          'EMAIL'       => $email,
          'ID_NEWS'     => $idNews,
          'created_at'  => date('Y-m-d H:i:s')
        );

        $this->db->insert('news_view', $dataNewsView);
        
        $this->db->query('UPDATE news SET VIEWS_COUNT = VIEWS_COUNT + 1 WHERE ID_NEWS = '.$idNews); // update news view count on table news

        $this->response(['status' => TRUE, 'message' => 'Data news view berhasil disimpan'], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => 'Data user atau news tidak ditemukan'], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
    }
    
  }

  function share_post(){
    $email  = $this->post('email');
    $idNews = $this->post('idNews');

    if($email != '' && $idNews != ''){
      $queryCheckDataUser = $this->db->where('EMAIL', $email)->get('mobile_user')->row();
      $queryCheckDataNews = $this->db->where('ID_NEWS', $idNews)->get('news')->row();

      if($queryCheckDataNews != null && $queryCheckDataUser != null){ //check data email & idNews is found
        $dataNewsShare = array(
          'EMAIL'       => $email,
          'ID_NEWS'     => $idNews,
          'created_at'  => date('Y-m-d H:i:s')
        );

        $this->db->insert('news_share', $dataNewsShare);
        
        $this->db->query('UPDATE news SET SHARES_COUNT = SHARES_COUNT + 1 WHERE ID_NEWS = '.$idNews); // update news share count on table news

        $this->response(['status' => TRUE, 'message' => 'Data news share berhasil disimpan'], REST_Controller::HTTP_OK);
      }else{
        $this->response(['status' => FALSE, 'message' => 'Data user atau news tidak ditemukan'], REST_Controller::HTTP_OK);
      }
    }else{
      $this->response(['status' => FALSE, 'message' => 'Parameter tidak cocok'], REST_Controller::HTTP_OK);
    }
  }
}
?>