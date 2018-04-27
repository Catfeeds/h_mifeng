<?php

class RecruitmentModel extends Model {
  public function count($filter = array()){
    //岗位总数
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = ' 1 ';
    }else{
      $where = ' r.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND r.city=$city";
      }
    }
    
    if($filter['user_id']){
      $where .= " AND r.user_id=".$filter['user_id'];
    }
    if($filter['keyword']){
      $where .= " AND r.position LIKE '%".$filter['keyword']."%'";
    }
    $count = M("Recruitment")->alias("r")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //岗位列表
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = " r.user_id=".$filter['user_id'];
    }else{
      $where = ' r.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND FIND_IN_SET($city,r.city) ";
      }
    }
    if($filter['keyword']){
      $where .= " AND r.position LIKE '%".$filter['keyword']."%'";
    }
    if($filter['cat_id']){
      $where .= " AND r.cat_id =".$filter['cat_id'];
    }
    if($filter['district']){
      $where .= " AND FIND_IN_SET(".$filter['district'].",r.district) ";
    }
    $result = M("Recruitment")->alias("r")->field("r.id,r.position,r.status,r.is_open,r.add_time,r.city,r.district,c.company_name")->join("left join ".table("company")." as c on c.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.status DESC,r.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['cast_count'] = M("ResumeCast")->where("recruitment_id=".$v['id'])->count();
      $v['city'] = explode(",",$v['city']);
      $v['district'] = explode(",",$v['district']);
      foreach($v['city'] as $k2=>$v2){
        $v['city_name'][] = D('Address')->region_name($v2);
        $v['district_name'][] = D('Address')->region_name($v['district'][$k2]);
      }
    }
    return $result;
  }
  public function info($id,$user_id=''){
    //岗位详情
    if(!empty($user_id)){
      $where .= " id=$id AND user_id=$user_id";
    }else{
      $where = "id=$id AND is_open=1";
    }
    $info = M("Recruitment")->where($where)->find();

    $info['cat_name'] = $this->cat_name($info['cat_id']);

    $info['province'] = explode(",",$info['province']);
    $info['city'] = explode(",",$info['city']);
    $info['district'] = explode(",",$info['district']);
    foreach($info['province'] as $k=>$v){
      $info['province_name'][] = D('Address')->region_name($v);
      $info['city_name'][] = D('Address')->region_name($info['city'][$k]);
      $info['district_name'][] = D('Address')->region_name($info['district'][$k]);
    }

    //是否投递过
    $user_info = session('userInfo');
    if($user_info){
      $info['is_resume_cast'] = M('ResumeCast')->where("recruitment_id=$id AND type=1 AND user_id=".$user_info['user_id'])->count();
    }
    return $info;
  }
  public function is_resume($user_id){
    //判断是否有简历
    $count = M("Resume")->where("user_id=$user_id")->order("add_time DESC")->find();
    return $count;
  }
  public function is_company($user_id){
    //判断是否有简历
    $count = M("Company")->where("user_id=$user_id")->count();
    return $count;
  }
  
  public function company_info($user_id){
    //单位信息
    $info = M("Company")->where("user_id=$user_id")->find();
    return $info;
  }
  public function my_cast_count($filter = array()){
    //简历总数
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND rc.recruitment_id=".$filter['id'];
    }
    $count = M("ResumeCast")->alias("rc")->where($where)->count();
    return $count;
  }
  public function my_cast_lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //简历投放列表
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND rc.recruitment_id=".$filter['id'];
    }
    $result = M("ResumeCast")->alias("rc")->field("u.pic,rc.id,r.name,r.working_age,r.profession,r.education,r.user_id")->join("left join ".table("resume")." as r on r.user_id=rc.user_id")->join("left join ".table("user")." as u on u.user_id=rc.user_id")->where($where)->limit($firstRow,$listRows)->order("rc.add_time DESC")->select();
    return $result;
  }
  public function resume_cast_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //我的投放
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND rc.user_id=".$filter['user_id'];
    }
    $result = M("ResumeCast")->alias("rc")->field("r.*,rc.add_time as add_time2")->join("left join ".table("recruitment")." as r on r.id=rc.recruitment_id")->where($where)->limit($firstRow,$listRows)->order("rc.add_time DESC")->select();


    foreach ($result as &$v) {
      $v['cast_count'] = M("ResumeCast")->where("recruitment_id=".$v['id'])->count();
      $v['city'] = explode(",",$v['city']);
      $v['district'] = explode(",",$v['district']);
      foreach($v['city'] as $k2=>$v2){
        $v['city_name'][] = D('Address')->region_name($v2);
        $v['district_name'][] = D('Address')->region_name($v['district'][$k2]);
      }
      
      // $v['city'] = D('Address')->region_name($v['city']);
      // $v['district'] = D('Address')->region_name($v['district']);
    }
    return $result;
  }
  public function resume_cast_count($filter = array()){
    //我的投放
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND rc.user_id=".$filter['user_id'];
    }
    $count = M("ResumeCast")->alias("rc")->field("r.id,r.position,r.city,r.district,r.add_time")->join("left join ".table("recruitment")." as r on r.id=rc.recruitment_id")->where($where)->limit($firstRow,$listRows)->order("rc.add_time DESC")->count();
    return $count;
  }
  public function my_cast_info($id,$r_user_id){
    //投递的简历详情
    $info = M("ResumeCast")->alias("rc")->field("u.pic,u.last_login_time,r.*")->join("left join ".table("resume")." as r on r.user_id=rc.user_id")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where("rc.id=$id AND rc.r_user_id=$r_user_id")->find();
    if($info){
      $info['province_name'] = D('Address')->region_name($info['province']);
      $info['city_name'] = D('Address')->region_name($info['city']);
      $info['district_name'] = D('Address')->region_name($info['district']);
    }
    return $info;
  }
  public function resume_count($filter = array()){
      //简历总数
      $where = ' r.is_verify=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND r.city=$city";
      }
      if($filter['keyword']){
        $where .= " AND r.position LIKE '%".$filter['keyword']."%'";
      }
      $count = M("Resume")->alias("r")->where($where)->count();
      return $count;
    }
    public function resume_lists($firstRow = 0, $listRows = 20 , $filter = array()){
      //简历投放列表
      $where = ' r.is_verify=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND r.city=$city";
      }
      if($filter['keyword']){
        $where .= " AND r.position LIKE '%".$filter['keyword']."%'";
      }
      $result = M("Resume")->alias("r")->field("u.pic,r.id,r.name,r.position,r.working_age,r.is_open")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
      return $result;
    }
    public function resume_info($filter = array()){
      //我的简历
      $where = " 1 ";
      if($filter['user_id']){
        $where .= " AND r.user_id=".$filter['user_id'];
      }
      if($filter['id']){
        $where .= " AND is_verify=1 AND r.id=".$filter['id'];
      }
      $info = M("Resume")->alias("r")->field("u.pic,u.last_login_time,r.*")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->find();

      $info['cat_name'] = $this->cat_name($info['cat_id']);

      $info['province'] = explode(",",$info['province']);
      $info['city'] = explode(",",$info['city']);
      $info['district'] = explode(",",$info['district']);
      foreach($info['province'] as $k=>$v){
        $info['province_name'][] = D('Address')->region_name($v);
        $info['city_name'][] = D('Address')->region_name($info['city'][$k]);
        $info['district_name'][] = D('Address')->region_name($info['district'][$k]);
      }
      $info['ResumeImg'] = M('ResumeImg')->where("resume_id=".$info['id'])->select();
      return $info;
    }

    public function cat_all($filter = array()){
      //分类
      $where = 1;
      // if($filter['region_type']){
      //   $where .= " AND region_type<=".$filter['region_type'];
      // }
      $list = M("RecruitmentCat")->field("cat_id,parent_id,cat_name")->where($where)->order(SO)->select();
      $list = getTree($list);
      return $list;
    }
    public function cat_name($cat_id){
      $info = M("RecruitmentCat")->field('cat_name')->where("cat_id=$cat_id")->find();
      return $info['cat_name'];
    }




  public function count2($filter = array()){
    //园区总数
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = ' 1 ';
    }else{
      $where = ' k.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND k.city=$city";
      }
    }
    
    if($filter['user_id']){
      $where .= " AND k.user_id=".$filter['user_id'];
    }
    if($filter['keyword']){
      $where .= " AND k.position LIKE '%".$filter['keyword']."%'";
    }
    $count = M("Kindergarten")->alias("k")->where($where)->count();
    return $count;
  }
  public function lists2($firstRow = 0, $listRows = 20 , $filter = array()){
    //园区列表
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = " k.user_id=".$filter['user_id'];
    }else{
      $where = ' k.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND k.city=$city";
      }
    }
    if($filter['keyword']){
      $where .= " AND k.title LIKE '%".$filter['keyword']."%'";
    }
    $result = M("Kindergarten")->alias("k")->field("k.id,k.province,k.status,k.is_open,k.add_time,k.city,k.district,k.title")->where($where)->limit($firstRow,$listRows)->order("k.status DESC,k.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['city'] = D('Address')->region_name($v['city']);
      $v['district'] = D('Address')->region_name($v['district']);
    }
    return $result;
  }
  public function info2($id,$user_id=''){
    //园区详情
    if(!empty($user_id)){
      $where .= " id=$id AND user_id=$user_id";
    }else{
      $where = "id=$id AND is_open=1";
    }
    $info = M("Kindergarten")->where($where)->find();
    $info['province_name'] = D('Address')->region_name($info['province']);
    $info['city_name'] = D('Address')->region_name($info['city']);
    $info['district_name'] = D('Address')->region_name($info['district']);
    return $info;
  }
  


  public function count3($filter = array()){
    //园区总数
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = ' 1 ';
    }else{
      $where = ' k.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND k.city=$city";
      }
    }
    
    if($filter['user_id']){
      $where .= " AND k.user_id=".$filter['user_id'];
    }
    if($filter['keyword']){
      $where .= " AND k.position LIKE '%".$filter['keyword']."%'";
    }
    $count = M("KindergartenXuqiu")->alias("k")->where($where)->count();
    return $count;
  }
  public function lists3($firstRow = 0, $listRows = 20 , $filter = array()){
    //园区列表
    // $where = ' 1 ';
    if($filter['user_id']){
      $where = " k.user_id=".$filter['user_id'];
    }else{
      $where = ' k.is_open=1 ';
      if(cookie('city')){
        $city = cookie('city');
        $where .= " AND k.city=$city";
      }
    }
    if($filter['keyword']){
      $where .= " AND k.title LIKE '%".$filter['keyword']."%'";
    }
    $result = M("KindergartenXuqiu")->alias("k")->field("u.pic,k.id,k.province,k.status,k.is_open,k.add_time,k.city,k.district,k.title")->join("left join ".table("user")." as u on u.user_id=k.user_id")->where($where)->limit($firstRow,$listRows)->order("k.status DESC,k.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['city'] = D('Address')->region_name($v['city']);
      $v['district'] = D('Address')->region_name($v['district']);
    }
    return $result;
  }
  public function info3($id,$user_id=''){
    //园区详情
    if(!empty($user_id)){
      $where .= " id=$id AND user_id=$user_id";
    }else{
      $where = "id=$id AND is_open=1";
    }
    $info = M("KindergartenXuqiu")->alias("k")->where($where)->find();
    $info['province_name'] = D('Address')->region_name($info['province']);
    $info['city_name'] = D('Address')->region_name($info['city']);
    $info['district_name'] = D('Address')->region_name($info['district']);
    return $info;
  }
}

?>
