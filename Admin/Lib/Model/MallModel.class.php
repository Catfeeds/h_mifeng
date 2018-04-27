<?php

class MallModel extends Model {
  public function count($filter = array()){
    //课堂总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND m.title LIKE '%".$filter['keyword']."%' ";
    }

    $count = M("Mall")->alias("m")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //课堂列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND m.title LIKE '%".$filter['keyword']."%' ";
    }
    $result = M("Mall")->alias("m")->where($where)->limit($firstRow,$listRows)->order("m.is_recommend DESC,m.sort_order ASC,m.add_time DESC")->select();
    return $result;
  }
  public function order_count($filter = array()){
    $where = "1";
    if($filter['mall_id']){
      $where .= " AND o.mall_id=".$filter['mall_id'];
    }
    if($filter['mall_type']){
      $where .= " AND o.mall_type=".$filter['mall_type'];
    }
    if($filter['type']){
      $where .= " AND o.type=".$filter['type'];
    }
    if($filter['strat_time']){
      $where .= " AND o.add_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND o.add_time<=".(strtotime($filter['end_time'])+86399);
    }
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $count = M("MallOrder")->alias("o")->join("left join ".table("user")." as u on u.user_id=o.user_id")->where($where)->count();
    return $count;
  }
  public function order_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['mall_id']){
      $where .= " AND o.mall_id=".$filter['mall_id'];
    }
    if($filter['mall_type']){
      $where .= " AND o.mall_type=".$filter['mall_type'];
    }
    if($filter['type']){
      $where .= " AND o.type=".$filter['type'];
    }
    if($filter['strat_time']){
      $where .= " AND o.add_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND o.add_time<=".(strtotime($filter['end_time'])+86399);
    }
    if($filter['keyword']){
        // $where .= " AND u.user_name LIKE '%".$filter['keyword']."%' ";
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    $result = M("MallOrder")->alias("o")->field("o.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=o.user_id")->where($where)->order("o.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    foreach($result as &$v){
      $v['province'] = D('Address')->region_name($v['province']);
      $v['city'] = D('Address')->region_name($v['city']);
      $v['district'] = D('Address')->region_name($v['district']);
    }
    return $result;
  }
  public function order_info($id){
    $info = M("MallOrder")->where("id=$id")->find();
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
    if($filter['lottery_id']){
        $where .= " AND l.lottery_id =".$filter['lottery_id'];
    }
    if($filter['strat_time']){
      $where .= " AND l.add_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND l.add_time<=".(strtotime($filter['end_time'])+86399);
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
    if($filter['lottery_id']){
        $where .= " AND l.lottery_id =".$filter['lottery_id'];
    }
    if($filter['strat_time']){
      $where .= " AND l.add_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND l.add_time<=".(strtotime($filter['end_time'])+86399);
    }
    $result = M("LotteryLog")->alias("l")->field("l.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where($where)->order("l.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
}
?>
