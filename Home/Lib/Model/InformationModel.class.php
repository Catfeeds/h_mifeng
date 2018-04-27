<?php
class InformationModel extends Model {
  public function cat_list($filter=array()){
    //分类列表
    $where = 1;
    if(isset($filter['parent_id'])){
      $where.=" AND parent_id=".$filter['parent_id'];
    }
    $cat_list = M("InformationCat")->where($where)->order(SO)->select();
    return $cat_list;
  }
  public function age_list(){
    //年龄列表
    if(isset($filter['parent_id'])){
      $where.=" AND parent_id=".$filter['parent_id'];
    }
    $age_list = M("InformationAge")->order(SO)->select();
    return $age_list;
  }
  public function material_list(){
    //材料列表
    if(isset($filter['parent_id'])){
      $where.=" AND parent_id=".$filter['parent_id'];
    }
    $material_list = M("InformationMaterial")->order(SO)->select();
    return $material_list;
  }
  public function theme_list(){
    //主题列表
    if(isset($filter['parent_id'])){
      $where.=" AND parent_id=".$filter['parent_id'];
    }
    $theme_list = M("InformationTheme")->order(SO)->select();
    return $theme_list;
  }
  public function InformationList($firstRow = 0, $listRows = 20 , $filter = array()){
    $where = ' 1 ';
    if($filter['cat_id']){
      $where .= " AND i." .get_child_id($filter['cat_id'],'InformationCat','cat_id');
    }
    if($filter['age_id']){
      $where .= " AND i." .get_child_id($filter['age_id'],'InformationAge','age_id');
    }
    if($filter['material_id']){
      $where .= " AND i." .get_child_id($filter['material_id'],'InformationMaterial','material_id');
    }
    if($filter['theme_id']){
      $where .= " AND i." .get_child_id($filter['theme_id'],'InformationTheme','theme_id');
    }
    if($filter['user_id']){
      $where .= " AND i.user_id=".$filter['user_id'];
    }
    if($filter['verify']){
      $where .= " AND i.verify=".$filter['verify'];
    }
    $result = M("Information")->alias("i")->field("i.id,i.title,i.add_time,i.verify,i.thumb_img,i.content,u.user_name")->join("left join ".table("user")." as u on i.user_id=u.user_id")->where($where)->limit($firstRow,$listRows)->group('i.id')->order("add_time desc")->select();
    foreach ($result as &$v) {
      $v['comment_count'] = M("comment")->where("information_id=".$v['id'])->count();
      $v['praise_count'] = M("praise")->where("information_id=".$v['id'])->count();
    }
    return $result;
  }
  public function InformationCount($filter = array()){
    $where = ' 1 ';
    if($filter['cat_id']){
      $where .= " AND i." .get_child_id($filter['cat_id'],'InformationCat','cat_id');
    }
    if($filter['age_id']){
      $where .= " AND i." .get_child_id($filter['age_id'],'InformationAge','age_id');
    }
    if($filter['material_id']){
      $where .= " AND i." .get_child_id($filter['material_id'],'InformationMaterial','material_id');
    }
    if($filter['theme_id']){
      $where .= " AND i." .get_child_id($filter['theme_id'],'InformationTheme','theme_id');
    }
    if($filter['user_id']){
      $where .= " AND i.user_id=".$filter['user_id'];
    }
    if($filter['verify']){
      $where .= " AND i.verify=".$filter['verify'];
    }
    $count = M("Information")->alias("i")->where($where)->count();
    return $count;
  }
  //详情
  public function info($id){
    $where = "id=$id";
    $child_where = "information_id=$id";
    
    $info = M("Information")->alias('i')->field("i.*,u.user_name,u.pic,u.introduction")->join("left join ".table("user")." as u on i.user_id=u.user_id")->where($where)->find();
    $info['child_content'] = M("InformationContent")->where($child_where)->order("sort_order asc")->select();
    return $info;
  }
  //点赞
  public function praise_list($firstRow=0,$listRows=20,$id){
    $list = M('Praise')->field("u.pic")->alias('c')->join("left join ".table("user")." as u on c.user_id=u.user_id")->where("information_id=$id")->limit($firstRow,$listRows)->order("add_time DESC")->select();
    return $list;
  }
  public function praise_count($id){
    $count = M("Praise")->where("information_id=$id")->count();
    return $count;
  }
}
?>
