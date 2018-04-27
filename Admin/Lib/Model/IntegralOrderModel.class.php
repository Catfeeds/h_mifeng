<?php
class IntegralOrderModel extends Model {
  public function count($filter = array()){
    $where = " pay_status=1 ";
    if($filter['keyword']){
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['strat_time']){
      $where .= " AND l.pay_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND l.pay_time<=".(strtotime($filter['end_time'])+86399);
    }
    $count = M("IntegralOrder")->alias("l")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = " pay_status=1 ";
    if($filter['keyword']){
        $where .= " AND (u.user_name LIKE '%".$filter['keyword']."%' OR u.phone LIKE '%".$filter['keyword']."%') ";
    }
    if($filter['strat_time']){
      $where .= " AND l.pay_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND l.pay_time<=".(strtotime($filter['end_time'])+86399);
    }
    $result = M("IntegralOrder")->alias("l")->field("l.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where($where)->order("l.id DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
}
?>