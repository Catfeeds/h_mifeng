<?php
class CollectModel extends Model {
  // public function lists($collect_id,$user_id){
  //   //列表
  //   $where = "c.user_id=$user_id AND c.collect_id=$collect_id";
  //   $list = M("Collect")->alias('c')->field("i.id,i.title,i.thumb_img")->join("left join ".table("information")." as i on i.id=c.information_id")->where($where)->order("c.add_time desc")->select();
  //   return $list;
  // }
  public function lists($firstRow = 0, $listRows = 20 , $filter = array()){
    //列表
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND c.user_id=".$filter['user_id'];
    }
    $result = M("Collect")->alias('c')->field("c.*")->where($where)->limit($firstRow,$listRows)->order("c.add_time desc")->select();
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
    

    return $result;
  }
  public function count($filter = array()){
    $where = ' 1 ';
    if($filter['user_id']){
      $where .= " AND c.user_id=".$filter['user_id'];
    }
    $count = M("collect")->alias("c")->where($where)->count();
    return $count;
  }
  public function is_collect($filter = array()){
    $where = 1;
    //判断是否收藏
    if($filter['user_id']){
      $where .= " AND c.user_id=".$filter['user_id'];
    }
    if($filter['relatively_id']){
      $where .= " AND c.relatively_id=".$filter['relatively_id'];
    }
    if($filter['type']){
      $where .= " AND c.type=".$filter['type'];
    }
    $find = M("collect")->alias("c")->where($where)->find();
    return $find;
  }
}
?>