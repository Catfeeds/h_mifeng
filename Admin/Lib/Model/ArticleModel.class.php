<?php
class ArticleModel extends Model{
	/**
   * 文章列表
   */
  public function listArticle($firstRow = 0, $listRows = 20 , $filter = array()) {
  	$where = ' 1 ';
  	if (!empty($filter['keyword'])){
  		$where .= " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
  	}
  	if ($filter['cat_id']){
  		$where .= " AND a." . get_article_children($filter['cat_id']);
  	}
    $result = M("Article")->alias("a")->field("a.article_id,a.cat_id,a.title,a.add_time,a.is_open,a.is_recommend, a.sort_order, ac.cat_name ,a.original_img ,a.thumb_img")->join("left join ".table("articlecat")." as ac on a.cat_id=ac.cat_id")->where($where)->limit($firstRow,$listRows)->order($filter['sort_by']." ".$filter['sort_order'].",add_time DESC")->select();
    return $result;
  }
	
	/**
   * 文章总数
   */
  public function listArticleCount($filter = array()) {
  	$where = ' 1 ';
    if (!empty($filter['keyword'])){
      $where .= " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
    }
    if ($filter['cat_id']){
      $where .= " AND a." . get_article_children($filter['cat_id']);
    }
    $count = M("Article")->alias("a")->where($where)->count();
  	return $count;
  }	
}

?>
