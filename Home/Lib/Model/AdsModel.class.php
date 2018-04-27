<?php
class AdsModel extends Model {
  public function lists($cat_id,$num){
    //列表
    $list = M("Ads")->where("cat_id=$cat_id")->order(SO.",ads_id DESC")->limit($num)->select();
    return $list;
  }
}
?>