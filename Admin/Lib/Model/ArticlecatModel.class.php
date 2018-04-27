<?php

class ArticlecatModel extends Model {
  /**
  * 文章分类列表
  */
  public function listArticlecat() {
    $list = M("articlecat")->field('cat_id,cat_name,parent_id')->order(SO)->select();
    // $list = getTree($list);
		return $list;               //获取分类结构
  }
}
?>
