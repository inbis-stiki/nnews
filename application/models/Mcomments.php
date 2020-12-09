<?php
class Mcomments extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

  public function loadCommentStats(){
    $query = 'SELECT "public".news."ID_NEWS", "public".news."TITLE_NEWS",
    Count("public".comments."ID_COMMENT") AS "COMMENTS_TOTAL",
    pending."COMMENTS_PENDING", approved."COMMENTS_APPROVED", pending."NEWEST_PENDING", approved."NEWEST_APPROVED"
    FROM "public".news
    LEFT JOIN "public".comments ON "public".comments."ID_NEWS" = "public".news."ID_NEWS"
    LEFT JOIN (SELECT "public".news."ID_NEWS", "public".news."TITLE_NEWS",
    COALESCE(Count("public".comments."ID_COMMENT"), 0) AS "COMMENTS_PENDING", MAX("public".comments."DATE_COMMENT") AS "NEWEST_PENDING"
    FROM "public".news
    LEFT JOIN "public".comments ON "public".comments."ID_NEWS" = "public".news."ID_NEWS"
    WHERE "public".comments."IS_APPROVED" = \'f\'
    GROUP BY "public".news."ID_NEWS") AS pending ON pending."ID_NEWS" = "public".news."ID_NEWS"
		LEFT JOIN (SELECT "public".news."ID_NEWS", "public".news."TITLE_NEWS",
    COALESCE(Count("public".comments."ID_COMMENT"), 0) AS "COMMENTS_APPROVED", MAX("public".comments."DATE_COMMENT") AS "NEWEST_APPROVED"
    FROM "public".news
    LEFT JOIN "public".comments ON "public".comments."ID_NEWS" = "public".news."ID_NEWS"
    WHERE "public".comments."IS_APPROVED" = \'t\'
    GROUP BY "public".news."ID_NEWS") AS approved ON approved."ID_NEWS" = "public".news."ID_NEWS"
    GROUP BY "public".news."ID_NEWS", pending."COMMENTS_PENDING", approved."COMMENTS_APPROVED", pending."NEWEST_PENDING", approved."NEWEST_APPROVED"
    ORDER BY pending."NEWEST_PENDING" ASC NULLS LAST, approved."NEWEST_APPROVED" DESC NULLS LAST, "public".news."DATE_NEWS" DESC';
    return $this->db->query($query)->result_array();
  }

  public function loadComments($id_news){
    return $this->db->where('ID_NEWS', $id_news)->get('view_news_comments')->result_array();
  }
}
?>