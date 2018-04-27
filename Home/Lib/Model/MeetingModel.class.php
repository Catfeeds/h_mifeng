<?php

class MeetingModel extends Model {
	public function lists($firstRow = 0, $listRows = 20 ) {
    $result = M("Meeting")->field("title,add_time,sort_order,meeting_time,id,short,thumb_img")->limit($firstRow,$listRows)->order(SO.",add_time DESC")->select();
    return $result;
  }
}

?>
