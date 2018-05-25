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
    $result = M("ContactUs")->alias("c")->where($where)->order("c.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
}
?>
