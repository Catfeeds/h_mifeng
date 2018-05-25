<?php

class ReelModel extends Model {
  public function count($filter = array()){
    $where = "1";
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['type']){
        $where .= " AND r.type = ".$filter['type'];
    }
    $count = M("Reel")->alias("r")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['type']){
        $where .= " AND r.type = ".$filter['type'];
    }
    $result = M("Reel")->alias("r")->field("r.*,u.user_name,a.title")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("article")." as a on a.article_id=r.template")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
    return $result;
  }
  public function info($id){
    $info = M("Reel")->alias("r")->field("r.*,u.user_name,a.title")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("article")." as a on a.article_id=r.template")->where("id=$id")->find();
    return $info;
  }

  public function count2($filter = array()){
    $where = "1";
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['type']){
        $where .= " AND r.type = ".$filter['type'];
    }
    $count = M("ReelList")->alias("r")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->count();
    return $count;
  }
  public function lists2($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['type']){
        $where .= " AND r.type = ".$filter['type'];
    }
    $result = M("ReelList")->alias("r")->field("r.*,u.user_name,a.title")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("article")." as a on a.article_id=r.template")->where($where)->order("r.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
  public function info2($id){
    $info = M("ReelList")->alias("r")->field("r.*,u.user_name,a.title")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("article")." as a on a.article_id=r.template")->where("id=$id")->find();

    $info['province'] = D('Address')->region_name($info['province']);
    $info['city'] = D('Address')->region_name($info['city']);
    $info['district'] = D('Address')->region_name($info['district']);
    
    return $info;
  }
}
?>
