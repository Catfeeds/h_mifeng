<?php

class CourseModel extends Model {
  public function count($filter = array()){
    //课堂总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND c.title LIKE '%".$filter['keyword']."%' ";
    }

    $count = M("Course")->alias("c")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //课堂列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND c.title LIKE '%".$filter['keyword']."%' ";
    }
    $result = M("Course")->alias("c")->where($where)->limit($firstRow,$listRows)->order("c.sort_order ASC,c.add_time DESC")->select();
    return $result;
  }
  public function course_lists($id){
    //课程列表

    $where = "course_id=$id";
    $result = M("course_list")->where($where)->order(SO.",add_time ASC")->select();
    return $result;
    
  }
  public function order_count($filter = array()){
    $where = "1";
    if($filter['course_id']){
      $where .= " AND o.course_id=".$filter['course_id'];
    }
    $count = M("CourseOrder")->alias("o")->where($where)->count();
    return $count;
  }
  public function order_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //会员兑换列表
    $where = ' 1 ';
    if($filter['course_id']){
      $where .= " AND o.course_id=".$filter['course_id'];
    }
    if($filter['strat_time']){
      $where .= " AND o.add_time>".strtotime($filter['strat_time']);
    }
    if($filter['end_time']){
      $where .= " AND o.add_time<=".(strtotime($filter['end_time'])+86399);
    }
    $result = M("CourseOrder")->alias("o")->field("o.*,u.user_name")->join("left join ".table("user")." as u on u.user_id=o.user_id")->where($where)->order("o.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }


  public function cat_list() {
    $list = M("CourseCat")->field('cat_id,cat_name,parent_id')->order(SO)->select();
    return $list;
  }
}
?>
