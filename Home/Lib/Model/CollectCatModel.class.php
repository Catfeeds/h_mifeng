<?php
class CollectCatModel extends Model {
  public function lists($user_id,$visible=''){
    //列表
    $where = "user_id=$user_id";
    if($visible){
      $where .= " AND visible=1";
    }
    $list = M("CollectCat")->where($where)->order("collect_id desc")->select();
    return $list;
  }
}
?>