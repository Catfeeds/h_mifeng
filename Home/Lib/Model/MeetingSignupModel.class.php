<?php

class MeetingModel extends Model {
	public function lists($firstRow = 0, $listRows = 20 ) {
    $result = M("Meeting")->field("title,add_time,sort_order,meeting_time,id")->limit($firstRow,$listRows)->order(SO)->select();
    return $result;
  }
}

?>
