<?php
class MeetingModel extends Model{
	/**
   * 列表
   */
  public function lists($firstRow = 0, $listRows = 20 ) {
    $result = M("Meeting")->field("title,add_time,sort_order,meeting_time,id")->limit($firstRow,$listRows)->order(SO.",add_time DESC")->select();
    return $result;
  }
  //报名列表
  public function signup_list($firstRow = 0, $listRows = 20 , $filter = array()) {
    $where = ' 1 ';
    if (!empty($filter['keyword'])){
      $where .= " AND s.name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
    }
    if ($filter['meeting_id']){
      $where .= " AND s.meeting_id=".$filter['meeting_id'];
    }
    $result = M("MeetingSignup")->alias("s")->field("s.id,s.add_time,s.phone,s.name,m.title")->join("left join ".table("meeting")." as m on s.meeting_id=m.id")->where($where)->order("s.add_time DESC");
    if($listRows>0){
      $result = $result->limit($firstRow,$listRows);
    }
    $result = $result->select();
    return $result;
  }
  //报名总数
  public function signup_count($filter = array()) {
    $where = ' 1 ';
    if (!empty($filter['keyword'])){
      $where .= " AND s.name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
    }
    if ($filter['meeting_id']){
      $where .= " AND s.meeting_id=".$filter['meeting_id'];
    }
    $count = M("MeetingSignup")->alias("s")->field("s.add_time,s.phone,s.name,m.title")->where($where)->count();
    return $count;
  }
}


?>
