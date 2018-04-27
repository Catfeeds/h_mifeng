<?php

class ArticleModel extends Model {
  /**
   * 文章列表
   */
  public function listArticle($firstRow = 0, $listRows = 20 , $filter = array()) {
    $where = ' a.is_open=1 ';
    if(isset($filter['cat_id'])){
      $where .= " AND a." . get_article_children($filter['cat_id']);
    }
    if(isset($filter['is_recommend'])){
      $where .= " AND a.is_recommend=".$filter['is_recommend'];
    }
    $result = M("Article")->alias("a")->field("a.article_id,a.title,a.thumb_img,a.short")->where($where)->limit($firstRow,$listRows)->order(SO.",add_time DESC")->select();
    return $result;
  }
  
  /**
   * 文章总数
   */
  public function listArticleCount($filter = array()) {
    $where = ' 1 ';
    if (!empty($filter['keyword'])){
      $where = " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
    }
    if ($filter['cat_id']){
      $where .= " AND a." . get_article_children($filter['cat_id']);
    }
    $count = M("Article")->alias("a")->where($where)->count();
    return $count;
  } 
}

?>
