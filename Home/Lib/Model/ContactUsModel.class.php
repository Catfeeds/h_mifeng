<?php
class ContactUsModel extends Model {
    public function count($filter = array()){
      //课程总数
      $where = ' 1 ';
      if($filter['user_id']){
        $where .= " AND c.user_id=".$filter['user_id'];
      }
      $count = M("ContactUs")->alias("c")->where($where)->count();
      return $count;
    }
    public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
      //课程列表
      $where = ' 1 ';
      if($filter['user_id']){
        $where .= " AND c.user_id=".$filter['user_id'];
      }
      $result = M("ContactUs")->alias("c")->where($where)->limit($firstRow,$listRows)->order("c.add_time DESC")->select();
      return $result;
    }
    public function info($id,$user_id){
        $info = M("ContactUs")->where("id=$id AND user_id=$user_id")->find();
        return $info;
    }
}
?>