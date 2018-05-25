<?php

class UserModel extends Model {
  public function count($filter = array()){
    //课堂总数
    $where = ' 1 ';
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['black']){
        $where .= " AND u.black=".$filter['black']." ";
    }
    $count = M("User")->alias("u")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //课堂列表
    $where = ' 1 ';
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['black']){
        $where .= " AND u.black=".$filter['black']." ";
    }
    $result = M("User")->alias("u")->where($where)->order("reg_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
  
  public function order_count($filter = array()){
    $where = "1";
    if($filter['User_id']){
      $where .= " AND o.User_id=".$filter['User_id'];
    }
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $count = M("UserOrder")->alias("o")->join("left join ".table("user")." as u on u.user_id=o.user_id")->where($where)->count();
    return $count;
  }
  public function order_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['User_id']){
      $where .= " AND o.User_id=".$filter['User_id'];
    }
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $result = M("UserOrder")->alias("o")->field("o.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=o.user_id")->where($where)->limit($firstRow,$listRows)->order("o.add_time DESC")->select();
    return $result;
  }
  public function order_info($id){
    $info = M("UserOrder")->where("id=$id")->find();
    $info['user'] = M('User')->where("user_id=".$info['user_id'])->find();
    $info['province'] = D('Address')->region_name($info['province']);
    $info['city'] = D('Address')->region_name($info['city']);
    $info['district'] = D('Address')->region_name($info['district']);
    return $info;
  }
  public function lottery_log_count($filter = array()){
    $where = "1";
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $count = M("LotteryLog")->alias("l")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where($where)->count();
    return $count;
  }
  public function lottery_log_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $result = M("LotteryLog")->alias("l")->field("l.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where($where)->limit($firstRow,$listRows)->order("l.add_time DESC")->select();
    return $result;
  }
}
?>
