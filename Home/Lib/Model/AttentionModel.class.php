<?php

class AttentionModel extends Model {
  function fans_count($filter = array()){
    $where = 1;
    //粉丝数量
    if($filter['fans_id']){
      $where .= " AND a.attention_id=".$filter['fans_id'];
    }
    $count = M("Attention")->alias("a")->where($where)->count();
    return $count;
  }
  function attention_count($filter = array()){
    $where = 1;
    //关注数量
    if($filter['attention_id']){
      $where .= " AND a.user_id=".$filter['attention_id'];
    }
    $count = M("Attention")->alias("a")->where($where)->count();
    return $count;
  }
  function is_attention($filter = array()){
    $where = 1;
    //是否已经关注
    if($filter['user_id']){
      $where .= " AND a.user_id=".$filter['user_id'];
    }
    if($filter['attention_id']){
      $where .= " AND a.attention_id=".$filter['attention_id'];
    }
    $find = M("Attention")->alias("a")->where($where)->find();
    return $find;
  }
  function attention_lists($firstRow=0,$listRows=20,$filter = array()){
    $where = 1;
    if($filter['attention_id']){
      //他的关注
      $where .= " AND a.user_id=".$filter['attention_id'];
      //select user_id=user_id,我的关注
      //关注人发布数     information.user_id = attention_id

      $list = M("Attention")->alias("a")->field("u.user_id,u.pic,u.user_name")->join("left join ".table("user")." as u on a.attention_id=u.user_id")->where($where)->group("a.attention_id")->limit($firstRow,$listRows)->order("a.add_time DESC")->select();
      if($filter['user_id']){
        foreach($list as &$v){
          //粉丝数
          $v['fans_count'] = M("Attention")->where("attention_id=".$v['user_id'])->count();
          //发布数
          $v['i_count'] = M("information")->where("user_id=".$v['user_id'])->count();
          //筛选那些已经关注的
          $v['is_attention'] = $this->is_attention(array('user_id'=>$filter['user_id'],'attention_id'=>$v['user_id']));
        }
      }
    }
    return $list;
  }
  function fans_lists($firstRow=0,$listRows=20,$filter = array()){
    $where = 1;
    if($filter['fans_id']){
      //他的粉丝
      $where .= " AND a.attention_id=".$filter['fans_id'];
      $list = M("Attention")->alias("a")->field("u.user_id,u.pic,u.user_name")->join("left join ".table("user")." as u on a.user_id=u.user_id")->where($where)->group("a.user_id")->limit($firstRow,$listRows)->order("a.add_time DESC")->select();
      if($filter['user_id']){
        foreach($list as &$v){
          //粉丝数
          $v['fans_count'] = M("Attention")->where("attention_id=".$v['user_id'])->count();
          //发布数
          $v['i_count'] = M("information")->where("user_id=".$v['user_id'])->count();
          //筛选那些已经关注的
          $v['is_attention'] = $this->is_attention(array('user_id'=>$filter['user_id'],'attention_id'=>$v['user_id']));
        }
      }
    }
    return $list;
  }
}

?>
