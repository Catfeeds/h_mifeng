<?php
class PraiseModel extends Model {
  public function praise_list($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND p.user_id=".$filter['user_id'];
    }
    if($filter['relatively_id']){
      $where .= " AND p.relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND p.type=".$filter['type'];
    }
    $result = M("Praise")->alias('p')->field("p.*,u.pic")->join("left join ".table("user")." as u on p.user_id=u.user_id")->where($where)->limit($firstRow,$listRows)->order("p.add_time desc")->select();
    if($filter['user_id']){
      foreach($result as &$v){
        switch ($v['type']) {
          case '1':
            $v['relatively_info'] = M("Information")->where("id=".$v['relatively_id'])->find();
            break;
          case '2':
            $v['relatively_info'] = M("Article")->where("article_id=".$v['relatively_id'])->find();
            break;
        }
      }
    }

    return $result;
  }

  public function praise_count($filter = array()){
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND p.user_id=".$filter['user_id'];
    }
    if($filter['relatively_id']){
      $where .= " AND p.relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND p.type=".$filter['type'];
    }
    $count = M("Praise")->alias("p")->where($where)->count();
    return $count;
  }

  public function is_praise($filter = array()){
    $where = 1;
    //判断是否收藏
    if($filter['user_id']){
      $where .= " AND p.user_id=".$filter['user_id'];
    }
    if($filter['relatively_id']){
      $where .= " AND p.relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND p.type=".$filter['type'];
    }
    $find = M("Praise")->alias("p")->where($where)->find();
    return $find;
  }
}
?>