<?php

class ContactUsModel extends Model {
  public function count($filter = array()){
    //课堂总数
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND c.phone LIKE '%".$filter['keyword']."%' ";
    }
    $count = M("ContactUs")->alias("c")->where($where)->count();
    return $count;
  }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //课堂列表
    $where = ' 1 ';
    if($filter['keyword']){
        $where .= " AND c.phone LIKE '%".$filter['keyword']."%' ";
    }
    $result = M("ContactUs")->alias("c")->where($where)->limit($firstRow,$listRows)->order("c.add_time DESC")->select();
    return $result;
  }
}
?>
