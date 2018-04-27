<?php
class IntegralModel extends Model {
  public function integral($integral='',$user_id='',$message='',$type=''){
    //积分操作
    $saveId = D("user")->where("user_id=$user_id")->setInc('integral',$integral);
    if($saveId){
      $data = array();
      $data['integral'] = $integral;
      $data['user_id']  = $user_id;
      $data['message']  = $message;
      $data['type']     = $type;
      $data['add_time'] = time();
      M("IntegralLog")->data($data)->add();
      return true;
    }else{
      return false;
    }
  }
  public function integral_zs($user_id,$type){
    //积分赠送
    $integral_type = M("IntegralType")->where("type=$type")->find();
    if($integral_type['upper_limit']>0){
      //判断每天次数上限
      $time = strtotime(date("Y-m-d"));
      $count = $this->integral_log(array('user_id'=>$user_id,'type'=>$type,'start_time'=>$time,'end_time'=>$time+86399));
      if($integral_type['upper_limit']<=$count){
        return false;
      }
    }
    $saveId = D("user")->where("user_id=$user_id")->setInc('integral',$integral_type['integral']);
    if($saveId){
      $data = array();
      $data['integral'] = $integral_type['integral'];
      $data['user_id']  = $user_id;
      $data['message']  = $integral_type['message'];
      $data['type']     = $type;
      $data['add_time'] = time();
      M("IntegralLog")->data($data)->add();
      return true;
    }else{
      return false;
    }
  }

  public function integral_log($filter = array()){
    //查询是否有相应记录
    $where = 1;
    if($filter['user_id']){
      $where .= " AND user_id=".$filter['user_id'];
    }
    if($filter['type']){
      $where .= " AND type=".$filter['type'];
    }
    if($filter['start_time']){
      $where .= " AND add_time>=".$filter['start_time'];
    }
    if($filter['end_time']){
      $where .= " AND add_time<=".$filter['end_time'];
    }
    $count = M('IntegralLog')->where($where)->count();
    return $count;
  }

  public function count($filter = array()){
    //获取总数
    $where = 1;
    if($filter['user_id']){
      $where .= " AND user_id=".$filter['user_id'];
    }
    $count = M("IntegralLog")->where($where)->count();
    return $count;
  }

  public function lists($firstRow=0,$listRows=20,$filter = array()){
    $where = 1;
    if($filter['user_id']){
      $where .= " AND user_id=".$filter['user_id'];
    }
    $result = M("IntegralLog")->where($where)->limit($firstRow,$listRows)->order("add_time DESC")->select();
    return $result;
  }
}
?>