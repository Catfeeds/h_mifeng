<?php

class InformationCatModel extends Model {
  /**
  * 文章分类列表
  */
  public function lists() {
    $list = M("InformationCat")->order(SO)->select();
    // $list = getTree($list);
		return $list;               //获取分类结构
  }
}
?>
