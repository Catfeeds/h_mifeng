<?php
class InformationModel extends Model{
	/**
   * 文章列表
   */
  public function InformationList($firstRow = 0, $listRows = 20 , $filter = array()) {
  	$where = ' 1 ';
  	if (!empty($filter['keyword'])){
  		$where .= " AND i.title LIKE '%" .mysql_like_quote($filter['keyword']) . "%'";
  	}
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
    $result = M("Information")->alias("i")->field("i.id,i.title,i.add_time,i.verify,u.user_name")->join("left join ".table("user")." as u on i.user_id=u.user_id")->where($where)->limit($firstRow,$listRows)->order("id desc")->select();
    return $result;
  }
	
	/**
   * 文章总数
   */
  public function InformationCount($filter = array()) {
  	$where = ' 1 ';
    if (!empty($filter['keyword'])){
      $where .= " AND i.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
    }
    if($filter['cat_id']){
      $where .= " AND i." . get_child_id($filter['cat_id'],'InformationCat','cat_id');
    }
    if($filter['age_id']){
      $where .= " AND i." . get_child_id($filter['age_id'],'InformationAge','age_id');
    }
    if($filter['material_id']){
      $where .= " AND i." . get_child_id($filter['material_id'],'InformationMaterial','material_id');
    }
    if($filter['theme_id']){
      $where .= " AND i." . get_child_id($filter['theme_id'],'InformationTheme','theme_id');
    }
    $count = M("Information")->alias("i")->where($where)->count();
  	return $count;
  }	
  //详情
  public function info($id){
    $info = M("Information")->alias('i')->field("i.*,u.user_name,u.user_id")->join("left join ".table("user")." as u on i.user_id=u.user_id")->where("id=$id")->find();
    $info['child_content'] = M("InformationContent")->where("information_id=$id")->order("sort_order asc")->select();
    return $info;
  }
}

?>
