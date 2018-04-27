<?php

class MallModel extends Model {
  public function count($filter = array()){
    //总数
    $where = ' m.is_open=1 ';
    if($filter['my_id']){
      $where .= " AND m.user_id=".$filter['my_id'];
    }
    if($filter['type']){
      $where .= " AND m.type=".$filter['type'];
    }
    $count = M("Mall")->alias("c")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' m.is_open=1 ';
    if($filter['is_recommend']){
      $where .= " AND m.is_recommend=".$filter['is_recommend'];
    }
    if($filter['type']){
      $where .= " AND m.type=".$filter['type'];
    }
    $result = M("Mall")->alias("m")->field("m.id,m.title,m.integral,m.thumb_img,m.price")->where($where)->limit($firstRow,$listRows)->order("m.sort_order ASC ,m.add_time DESC")->select();
    return $result;
  }
  public function info($id){
    //详情

    $info = M("Mall")->where("id=$id")->find();

    return $info;
  }
  public function is_pay($id,$user_id){
    //兑换
    $info = M("Mall_order")->where("Mall_id=$id AND user_id=$user_id")->find();
    return $info;
  }
  public function order_count($filter = array()){
    //订单总数
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND o.user_id=".$filter['user_id'];
    }
    if($filter['type']){
      $where .= " AND o.type=".$filter['type'];
    }
    $count = M("MallOrder")->alias("o")->where($where)->count();
    return $count;
  }
  public function order_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //订单列表
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND o.user_id=".$filter['user_id'];
    }
    if($filter['type']){
      $where .= " AND o.type=".$filter['type'];
    }
    $count = M("MallOrder")->alias("o")->field("id,title,integral,thumb_img")->where($where)->limit($firstRow,$listRows)->order("add_time DESC")->select();
    return $count;
  }
  public function order_info($id,$user_id){
    $info = M("MallOrder")->where("id=$id AND user_id=$user_id")->find();
    $info['province'] = D('Address')->region_name($info['province']);
    $info['city'] = D('Address')->region_name($info['city']);
    $info['district'] = D('Address')->region_name($info['district']);
    return $info;
  }
  public function is_buy($filter = array()){
    //查看用户是否兑换过
    $where = 1;
    //判断是否收藏
    if($filter['user_id']){
      $where .= " AND user_id=".$filter['user_id'];
    }
    if($filter['mall_id']){
      $where .= " AND mall_id=".$filter['mall_id'];
    }
    $find = M("MallOrder")->where($where)->find();
    return $find;
  }
}

?>
