<?php
class CommentModel extends Model {
  //评论
  public function comment_list($firstRow=0,$listRows=20,$filter = array()){
    $where = 1;
    if($filter['relatively_id']){
      $where .= " AND c.relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND c.type=".$filter['type'];
    }
    $list = M('Comment')->field("c.id,c.content,c.add_time,u.user_id,u.user_name,u.pic,u.introduction,u2.user_name as reply")->alias('c')->join("left join ".table("user")." as u on c.user_id=u.user_id")->join("left join ".table("user")." as u2 on c.parent_id=u2.user_id")->where($where)->limit($firstRow,$listRows)->order("add_time DESC")->select();
    return $list;
  }
  public function comment_on($filter = array()){
    // if($filter['information_id']){
    //   $where = "c.id=".$filter['information_id'];
    // }
    // if($filter['course_id']){
    //   $where = "c.id=".$filter['course_id'];
    // }
    // if($filter['article_id']){
    //   $where = "c.id=".$filter['article_id'];
    // }
    if($filter['id']){
      $where = "c.id=".$filter['id'];
    }
    $list = M('Comment')->field("c.id,c.content,c.add_time,u.user_id,u.user_name,u.pic,u.introduction,u2.user_name as reply")->alias('c')->join("left join ".table("user")." as u on c.user_id=u.user_id")->join("left join ".table("user")." as u2 on c.parent_id=u2.user_id")->where($where)->limit($firstRow,$listRows)->order("add_time DESC")->select();
    return $list;
  }
  public function comment_count($filter = array()){
    $where = 1;
    if($filter['relatively_id']){
      $where .= " AND relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND type=".$filter['type'];
    }
    $count = M("Comment")->where($where)->count();
    return $count;
  }
}
?>