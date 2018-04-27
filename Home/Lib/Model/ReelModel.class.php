<?php

class ReelModel extends Model {
  public function count($filter = array()){
    //总数
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND r.user_id=".$filter['user_id'];
    }
    if($filter['type']){
      $where .= " AND r.type=".$filter['type'];
    }
    if($filter['is_use']){
      $where .= " AND r.is_use=".$filter['is_use'];
    }
    if($filter['end_time']){
      $where .= " AND r.end_time>".$filter['end_time'];
    }
    $count = M("ReelList")->alias("r")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND r.user_id=".$filter['user_id'];
    }
    if($filter['type']){
      $where .= " AND r.type=".$filter['type'];
    }
    if(isset($filter['is_use'])){
      $where .= " AND r.is_use=".$filter['is_use'];
    }
    if($filter['end_time']){
      $where .= " AND r.end_time>".$filter['end_time'];
    }
    $result = M("ReelList")->alias("r")->join("left join ".table("article")." as a on a.article_id=r.template")->field("r.id,r.end_time,r.price,r.type,r.is_use,a.title")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC,r.id DESC")->select();
    return $result;
  }
  public function info($id,$user_id){
    //详情
    
    $info = M("ReelList")->alias("r")->join("left join ".table("article")." as a on a.article_id=r.template")->field("r.*,a.title,a.content")->where("r.id=$id AND r.user_id=$user_id")->find();
    return $info;
  }
  
}

?>
