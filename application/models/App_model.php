<?php 
class App_model extends CI_Model{
  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('youtube');
  }

  function loadStatistics(){
    $query = "SELECT
    Sum(news.VIEWS_COUNT) AS views,
    Sum(news.SHARES_COUNT) AS shares
    FROM news";
    $news_stats_count = $this->db->query($query)->row_array();
    $news_count = $this->db->where('STATUS', 'published')->where_in('ID_CATEGORY', ['B', 'A'])->get('news')->num_rows();
    $gallery_count = $this->db->where('STATUS', 'published')->where('ID_CATEGORY', 'G')->get('news')->num_rows();
    $videos = $this->db->where('STATUS_PUBLISHED', TRUE)->get('video')->num_rows();
    $users = $this->db->get('mobile_user')->num_rows();
    // $emagz = $this->db->get('emagz')->num_rows();
    return array(
      'views' => $news_stats_count['views'],
      'news' => $news_count,
      'videos' => $videos,
      'shares' => $news_stats_count['shares'],
      'users' => $users,
      // 'emagz' => $emagz,
      'gallery' => $gallery_count
    );
  }

  function loadMostTrending(){
    $this->db->order_by('TRENDING', 'desc')->limit(5, 0);
    return $this->db->where('STATUS', 'published')->get('view_news_trending')->result();
  }

  function loadMostLiked(){
    $query = "SELECT view_likes.ID_NEWS,
      view_likes.ID_CATEGORY, view_likes.TITLE_NEWS,
      view_likes.LIKES, news.STATUS
      FROM view_likes
      INNER JOIN news ON view_likes.ID_NEWS = news.ID_NEWS
      WHERE news.STATUS = 'published'
      ORDER BY view_likes.LIKES DESC LIMIT 5 OFFSET 0";
    return $this->db->query($query)->result();        
  }
}
?>