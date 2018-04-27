<?php

class CourseModel extends Model {
  public function count($filter = array()){
    //课程总数
    $where = ' c.is_open=1 ';
    if($filter['my_id']){
      $where .= " AND o.user_id=".$filter['my_id'];
    }
    if($filter['cat_id']){
      $where .= " AND c." .get_child_id($filter['cat_id'],'CourseCat','cat_id');
    }
    $count = M("Course")->alias("c")->join("left join ".table("course_order")." as o on o.course_id=c.id")->where($where)->group('c.id')->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //课程列表
    $where = ' c.is_open=1 ';
    if($filter['my_id']){
      $where .= " AND o.user_id=".$filter['my_id'];
    }
    if($filter['cat_id']){
      $where .= " AND c." .get_child_id($filter['cat_id'],'CourseCat','cat_id');
    }
    $result = M("Course")->alias("c")->field("c.id,c.title,c.thumb_img,c.teacher,c.integral")->join("left join ".table("course_order")." as o on o.course_id=c.id")->where($where)->limit($firstRow,$listRows)->group('c.id')->order("c.sort_order ASC , c.add_time DESC")->select();
    foreach ($result as &$v) {
      $v['buy_count'] = M("course_order")->where("course_id=".$v['id'])->count();
      $v['list_count'] = M("course_list")->where("course_id=".$v['id'])->count();
    }
    return $result;
  }
  public function info($id){
    //课程详情
    $info = M("Course")->where("id=$id")->find();
    $info['buy_count'] = M("course_order")->where("course_id=$id")->count();
    $info['child_list'] = M("course_list")->where("course_id=$id")->order(SO.",add_time ASC")->select();
    return $info;
  }
  public function is_pay($id,$user_id){
    //判断是否购买了课程
    $info = M("Course_order")->where("course_id=$id AND user_id=$user_id")->find();
    return $info;
  }

}

?>
