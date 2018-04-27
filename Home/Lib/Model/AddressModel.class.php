<?php
class AddressModel extends Model {
  //获取用户地址信息
  public function address_list($user_id){
    $list = M('Address')->alias("a")->where("user_id=$user_id")->select();
    foreach($list as &$v){
        $v['province'] = $this->region_name($v['province']);
        $v['city'] = $this->region_name($v['city']);
        $v['district'] = $this->region_name($v['district']);
    }
    return $list;
  }
  public function region_name($region_id){
    $info = M("Region")->field('region_name')->where("region_id=$region_id")->find();
    return $info['region_name'];
  }
  public function address_info($address_id){
    //单个地址详情
    $info = M('Address')->alias("a")->where("address_id=$address_id")->find();
    $info['province'] = $this->region_name($info['province']);
    $info['city'] = $this->region_name($info['city']);
    $info['district'] = $this->region_name($info['district']);
    return $info;
  }
  public function region_all($filter = array()){
    $where = 1;
    if($filter['region_type']){
      $where .= " AND region_type<=".$filter['region_type'];
    }
    if($filter['parent_id']){
      $where .= " AND parent_id=".$filter['parent_id'];
    }
    $list = M("region")->field("region_id,parent_id,region_name")->where($where)->select();
    if($filter['parent_id']){
      return $list;
    }
    $list = getTree($list,"region_id");
    return $list;
  }
}
?>
