<?php

class RecruitmentModel extends Model {
  public function count($filter = array()){
    //总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.position LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }

    $count = M("Recruitment")->alias("r")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.position LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }

    $result = M("Recruitment")->alias("r")->field("r.*,u.user_name,c.company_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("company")." as c on c.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['cast_count'] = M("ResumeCast")->where("recruitment_id=".$v['id'])->count();
      $v['province'] = explode(",",$v['province']);
      $v['city'] = explode(",",$v['city']);
      $v['district'] = explode(",",$v['district']);
      foreach($v['city'] as $k2=>$v2){
        $v['province_name'][] = D('Address')->region_name($v['province'][$k2]);
        $v['city_name'][] = D('Address')->region_name($v['city'][$k2]);
        $v['district_name'][] = D('Address')->region_name($v['district'][$k2]);
      }
      $v['sub_count'] = M("ResumeCast")->where(["type"=>1,"recruitment_id"=>$v['id']])->count();
    }
    return $result;
  }
  public function info($id){
    //岗位详情
    $where = "r.id=$id";
    $info = M("Recruitment")->alias("r")->field("r.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->find();

    $info['company'] = D('Company')->where("user_id=".$info['user_id'])->find();

    $info['cat_name'] = $this->cat_name($info['cat_id']);
    $info['province'] = explode(",",$info['province']);
    $info['city'] = explode(",",$info['city']);
    $info['district'] = explode(",",$info['district']);
    foreach($info['province'] as $k=>$v){
      $info['province_name'][] = D('Address')->region_name($v);
      $info['city_name'][] = D('Address')->region_name($info['city'][$k]);
      $info['district_name'][] = D('Address')->region_name($info['district'][$k]);
    }
    // $info['province_name'] = D('Address')->region_name($info['province']);
    // $info['city_name'] = D('Address')->region_name($info['city']);
    // $info['district_name'] = D('Address')->region_name($info['district']);
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

  public function cat_list() {
    $list = M("RecruitmentCat")->field('cat_id,cat_name,parent_id')->order(SO)->select();
    return $list;
  }
  
  public function cast_count($filter = array()){
    //简历总数
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND type=1  AND rc.recruitment_id=".$filter['id'];
    }
    if($filter['resume_id']){
      $where .= " AND type=2  AND rc.resume_id=".$filter['resume_id'];
    }
    $count = M("ResumeCast")->alias("rc")->where($where)->count();
    return $count;
  }
  public function cast_lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //简历投放列表
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND type=1 AND rc.recruitment_id=".$filter['id'];
      $result = M("ResumeCast")->alias("rc")->field("rc.id,rc.add_time,rc.resume_id,rc.recruitment_id,r.name,r.working_age,r.profession,r.education,r.user_id")->join("left join ".table("resume")." as r on r.id=rc.resume_id")->where($where)->limit($firstRow,$listRows)->order("rc.add_time DESC")->select();
      return $result;
    }
    if($filter['resume_id']){
      $where .= " AND type=2 AND rc.resume_id=".$filter['resume_id'];
      $result = M("ResumeCast")->alias("rc")->field("rc.id,rc.add_time,rc.resume_id,rc.recruitment_id,r.position,r.salary,c.company_name")->join("left join ".table("recruitment")." as r on r.id=rc.recruitment_id")->join("left join ".table("company")." as c on c.user_id=rc.r_user_id")->where($where)->limit($firstRow,$listRows)->group('rc.id')->order("rc.add_time DESC")->select();
      return $result;
    }
    
  }
  public function cast_info($id){
    //投递的简历详情
    $info = M("ResumeCast")->alias("rc")->field("r.*")->join("left join ".table("resume")." as r on r.user_id=rc.user_id")->where("rc.id=$id")->find();

    $info['province'] = explode(",",$info['province']);
    $info['city'] = explode(",",$info['city']);
    foreach($info['province'] as $k=>$v){
      $info['province_name'][] = D('Address')->region_name($v);
      $info['city_name'][] = D('Address')->region_name($info['city'][$k]);
    }

    $info['ResumeImg'] = M('ResumeImg')->where("resume_id=".$info['id'])->select();
    
    // if($info){
    //   $info['province_name'] = D('Address')->region_name($info['province']);
    //   $info['city_name'] = D('Address')->region_name($info['city']);
    //   $info['district_name'] = D('Address')->region_name($info['district']);
    // }
    return $info;
  }



  public function count2($filter = array()){
    //总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.title LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }

    $count = M("Kindergarten")->alias("r")->where($where)->count();
    return $count;
  }
  public function lists2($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.title LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }
    $result = M("Kindergarten")->alias("r")->field("r.*,u.user_name,c.company_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("company")." as c on c.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['province_name'] = D('Address')->region_name($v['province']);
      $v['city_name'] = D('Address')->region_name($v['city']);
      $v['district_name'] = D('Address')->region_name($v['district']);

      $v['sub_count'] = M("KindergartenSignup")->where(["kindergarten_id"=>$v['id']])->count();
    }
    return $result;
  }
  public function info2($id){
    //岗位详情
    $where = "r.id=$id";
    $info = M("Kindergarten")->alias("r")->field("r.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->find();
    $info['province_name'] = D('Address')->region_name($info['province']);
    $info['city_name'] = D('Address')->region_name($info['city']);
    $info['district_name'] = D('Address')->region_name($info['district']);
    return $info;
  }
  public function signup_count($filter = array()){
    //简历总数
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND rc.kindergarten_id=".$filter['id'];
    }
    if($filter['kindergarten_xuqiu_id']){
      $where .= " AND rc.kindergarten_xuqiu_id=".$filter['kindergarten_xuqiu_id'];
    }
    $count = M("KindergartenSignup")->alias("rc")->where($where)->count();
    return $count;
  }
  public function signup_lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //简历投放列表
    $where = ' 1 ';
    if($filter['id']){
      $where .= " AND rc.kindergarten_id=".$filter['id'];
    }
    if($filter['kindergarten_xuqiu_id']){
      $where .= " AND rc.kindergarten_xuqiu_id=".$filter['kindergarten_xuqiu_id'];
    }
    $result = M("KindergartenSignup")->alias("rc")->where($where)->limit($firstRow,$listRows)->order("rc.add_time DESC")->select();
    return $result;
  }



  public function resume_count($filter = array()){
    //简历总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.name LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    $count = M("Resume")->alias("r")->field("r.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->count();
    return $count;
  }
  public function resume_lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //简历投放列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.name LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    $result = M("Resume")->alias("r")->field("r.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['cast_count'] = M("ResumeCast")->where("recruitment_id=".$v['id'])->count();
      $v['province'] = explode(",",$v['province']);
      $v['city'] = explode(",",$v['city']);
      $v['district'] = explode(",",$v['district']);
      foreach($v['city'] as $k2=>$v2){
        $v['province_name'][] = D('Address')->region_name($v['province'][$k2]);
        $v['city_name'][] = D('Address')->region_name($v['city'][$k2]);
        $v['district_name'][] = D('Address')->region_name($v['district'][$k2]);
      }
      $v['sub_count'] = M("ResumeCast")->where(["type"=>2,"resume_id"=>$v['id']])->count();
    }
    return $result;
  }
  public function resume_info($id){
    //投递的简历详情
    $info = M("Resume")->alias("r")->where("r.id=$id")->find();

    $info['province'] = explode(",",$info['province']);
    $info['city'] = explode(",",$info['city']);
    foreach($info['province'] as $k=>$v){
      $info['province_name'][] = D('Address')->region_name($v);
      $info['city_name'][] = D('Address')->region_name($info['city'][$k]);
    }

    $info['ResumeImg'] = M('ResumeImg')->where("resume_id=".$info['id'])->select();
    return $info;
  }




  public function count3($filter = array()){
    //总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.title LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }

    $count = M("KindergartenXuqiu")->alias("r")->where($where)->count();
    return $count;
  }
  public function lists3($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND r.title LIKE '%".$filter['keyword']."%' ";
    }
    if($filter['province']){
        $where .= " AND find_in_set(".$filter['province'].",r.province)";
    }
    if($filter['city']){
        $where .= " AND find_in_set(".$filter['city'].",r.city)";
    }
    if($filter['district']){
        $where .= " AND find_in_set(".$filter['district'].",r.district)";
    }
    $result = M("KindergartenXuqiu")->alias("r")->field("r.*,u.user_name,c.company_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->join("left join ".table("company")." as c on c.user_id=r.user_id")->where($where)->limit($firstRow,$listRows)->order("r.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['province_name'] = D('Address')->region_name($v['province']);
      $v['city_name'] = D('Address')->region_name($v['city']);
      $v['district_name'] = D('Address')->region_name($v['district']);
      $v['sub_count'] = M("KindergartenSignup")->where(["kindergarten_xuqiu_id"=>$v['id']])->count();
    }
    return $result;
  }
  public function info3($id){
    //岗位详情
    $where = "r.id=$id";
    $info = M("KindergartenXuqiu")->alias("r")->field("r.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=r.user_id")->where($where)->find();
    $info['province_name'] = D('Address')->region_name($info['province']);
    $info['city_name'] = D('Address')->region_name($info['city']);
    $info['district_name'] = D('Address')->region_name($info['district']);
    return $info;
  }
}
?>
