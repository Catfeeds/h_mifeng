<?php

class InformationAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Information');
	}
  /**
   * 文章列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Information = D("Information");

    $filter = array();
    $filter['keyword']     = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['cat_id']      = empty($this->_request('cat_id')) ? 0 : intval($this->_request('cat_id'));
    $filter['age_id']      = empty($this->_request('age_id')) ? 0 : intval($this->_request('age_id'));
    $filter['material_id'] = empty($this->_request('material_id')) ? 0 : intval($this->_request('material_id'));
    $filter['theme_id']    = empty($this->_request('theme_id')) ? 0 : intval($this->_request('theme_id'));
    $filter['record_count'] = $count = $M_Information->InformationCount($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    $cat_list      =D("InformationCat")->lists();
    $age_list      =D("InformationAge")->lists();
    $material_list =D("InformationMaterial")->lists();
    $theme_list    =D("InformationTheme")->lists();
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("cat_list", optionsDate(getTree($cat_list),$filter['cat_id'],0,0,"cat_id",'cat_name'));
    $this->assign("age_list", optionsDate(getTree($age_list),$filter['age_id'],0,0,"age_id",'age_name'));
    $this->assign("material_list", optionsDate(getTree($material_list),$filter['material_id'],0,0,"material_id",'material_name'));
    $this->assign("theme_list", optionsDate(getTree($theme_list),$filter['theme_id'],0,0,"theme_id",'theme_name'));
    $this->assign("list", $M_Information->InformationList($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 文章详情页面
   */
	public function info(){
    $M_Information = D("Information");

    $id = $this->_get('id');
    $info = !empty($id)?$M_Information->info($id):'';

    $cat_list      =D("InformationCat")->lists();
    $age_list      =D("InformationAge")->lists();
    $material_list =D("InformationMaterial")->lists();
    $theme_list    =D("InformationTheme")->lists();
    $this->assign("cat_list", optionsDate(getTree($cat_list),$info['cat_id'],0,0,"cat_id",'cat_name'));
    $this->assign("age_list", optionsDate(getTree($age_list),$info['age_id'],0,0,"age_id",'age_name'));
    $this->assign("material_list", optionsDate(getTree($material_list),$info['material_id'],0,0,"material_id",'material_name'));
    $this->assign("theme_list", optionsDate(getTree($theme_list),$info['theme_id'],0,0,"theme_id",'theme_name'));
    
    $this->assign("info", $info);
    $this->display();
	}
  //文章保存
	public function save(){
    //投稿提交
    $M_Information = D('Information');
    $rules = array (
      array('title','require','请填写标题！',1),
    );
    if(!$M_Information->validate($rules)->create()){
      $this->error($M_Information->getError());
    }
    $id = $this->_post('id');
    if($id>0){
      //上传图片
      $img = false;//验证是否有上传图片
      if($_FILES['file']['error']===0){
        $img = true;
      }
      foreach($_FILES['file2']['error'] as $v){
        if($v===0){
          $img = true;
        }
      }
    }else{
      $img = true;
      if($_FILES['file']['error']!==0){
        $this->error("请上传封面图");
      }
    }
    $data = $M_Information->create();
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    
    $data['file_url']   = $this->_post('file_url_text');
    //附件
    if($_FILES['file_url']['error']===0){
      $file_url = $_FILES['file_url'];
      unset($_FILES['file_url']);
      $date = date("Y-m-d");
      import("ORG.Util.UploadFile"); 
      import("ORG.Util.File");
      $upload = new UploadFile(); // 实例化上传类 
      $path = 'Uploads/download/'.$date."/";
      File::make_dir($path);
      $filename = $path.uniqid().".".substr(strrchr($file_url['name'],'.'),1);
      move_uploaded_file($file_url['tmp_name'],$filename);
      $data['file_url'] = $filename;
    }

    
    if($img){
      $date = date("Y-m-d");
      $thumbPath='Uploads/information/thumb_img/'.$date.'/';
      $originalPath='Uploads/information/original_img/'.$date.'/';
      $thumbPrefix='thumb_,original_';
      $widthSize='300,750';
      $heightSize='300,0';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,true,2);
      if($upfile[0]['key']=="file"){
        $data['thumb_img']  = $thumbPath."thumb_".$upfile[0]['savename'];
        $data['original_img']  = $thumbPath."original_".$upfile[0]['savename'];
        water($data['original_img']);
        unset($upfile[0]);
        $upfile = array_merge($upfile);
      }
    }
    if($id>0){
      $info=$M_Information->find($id);
      if($data['verify']==1&&$info['integral']==0){
        //赠送积分
        D('Integral')->integral_zs($data['user_id'],3);
        $data['integral'] = 1;
      }
      $saveId=$M_Information->where(array('id'=>$id))->save($data);
      //附加
      $content2 = $this->_post("content2");
      $id2 = $this->_post("id2");
      //删除子内容
      $child_in = array();
      foreach($id2 as $v){
        if($v>0){
          $child_in[] = $v;
        }
      }
      if($child_in){
        M("InformationContent")->where("information_id=$id AND id NOT IN (".implode(",",$child_in).") ")->delete();
      }else{
        M("InformationContent")->where("information_id=$id")->delete();
      }
      $img_i = 0;
      foreach($content2 as $k=>$v){
        $con = array();
        if($_FILES['file2']['error'][$k]===0&&$upfile[$img_i]['key']=="file2"){
          $con['thumb_img']  = $thumbPath."thumb_".$upfile[$img_i]['savename'];
          $con['original_img']  = $thumbPath."original_".$upfile[$img_i]['savename'];
          water($con['original_img']);
          $img_i++;
        }
        $con['sort_order'] = $k;
        $con['information_id'] = $id;
        $con['content'] = $content2[$k];
        if($id2[$k]>0){
          M("InformationContent")->where(array('id'=>$id2[$k]))->save($con);
        }else{
          M("InformationContent")->data($con)->add();
        }
      }
    }else{
      $data['user_id'] = 100;
      $saveId=$M_Information->data($data)->add();
      $id = $saveId;
      $content2 = $this->_post("content2");
      $img_i = 0;
      foreach($content2 as $k=>$v){
        $con = array();
        if($_FILES['file2']['error'][$k]===0&&$upfile[$img_i]['key']=="file2"){
          $con['thumb_img']  = $thumbPath."thumb_".$upfile[$img_i]['savename'];
          $con['original_img']  = $thumbPath."original_".$upfile[$img_i]['savename'];
          water($con['original_img']);
          $img_i++;
        }
        $con['sort_order'] = $k;
        $con['information_id'] = $id;
        $con['content'] = $content2[$k];
        M("InformationContent")->data($con)->add();
      }
    }
    if($id>0){
      //删除旧图片
      $Information = $M_Information->info($id);
      if(!empty($data['thumb_img'])&&$Information['thumb_img']!=$data['thumb_img']){
        @unlink($Information['thumb_img']);
      }
      if(!empty($data['original_img'])&&$Information['original_img']!=$data['original_img']){
        @unlink($Information['original_img']);
      }
      parent::admin_log($data['title']."-ID($id)",'edit','Information');
    }else{
      parent::admin_log($data['title']."-ID($id)",'add','Information');
    }
    $this->success('提交成功！！',U('Information/info',array('id'=>$id)));
    exit();
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Information = D("Information");
    $id= intval($this->_get('id'));
    $row = $M_Information->info($id);
    //删除文章内容图片
    if ($M_Information->where("id=$id")->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Information');
      @unlink($row['thumb_img']);
      @unlink($row['original_img']);
      //删除评论
      M("Comment")->where("type=1 AND relatively_id=".$id)->delete();
      //删除收藏
      M("Collect")->where("type=1 AND relatively_id=".$id)->delete();
      //删除点赞
      M("Praise")->where("type=1 AND relatively_id=".$id)->delete();
      //删除子内容
      M("InformationContent")->where("information_id=$id")->delete();
      foreach($row['child_content'] as $v){
        @unlink($v['original_img']);
        @unlink($v['thumb_img']);
      }
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 文章批量操作
   */
  public function batch(){
    $M_Information = D("Information");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择文章！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Information_list = M('Information')->where($in_ids)->select();
          M('Information')->where($in_ids)->delete();
          foreach($Information_list as $value){
            @unlink($value['thumb_img']);
            @unlink($value['original_img']);
            //删除评论
            M("Comment")->where("information_id=".$value['id'])->delete();
            //删除收藏
            M("Collect")->where("information_id=".$value['id'])->delete();
            //删除点赞
            M("Praise")->where("information_id=".$value['id'])->delete();
            $in_ids='information_id '.db_create_in(join(',',$checkboxes));
            $child_content = M("InformationContent")->where($in_ids)->select();
            M("InformationContent")->where($in_ids)->delete();
            foreach($child_content as $v){
              @unlink($v['thumb_img']);
              @unlink($v['original_img']);
            }
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Information');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_Information->where($in_ids)->save(array('verify'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Information');
          break;
        case 'button_show':
          /* 批量通过 */
          $M_Information->where($in_ids)->save(array('verify'=>1,'integral'=>1));
          $list = $M_Information->where($in_ids)->select();
          foreach($list as $v){
            if($v['verify']==1&&$v['integral']==0){
              //赠送积分
              D('Integral')->integral_zs($v['user_id'],3);
            }
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Information');
          break;
      }
    }
    $this->success('批量操作成功！');
  }

  public function comment_list(){
    //评论列表
    $relatively_id = $this->_get('relatively_id');
    $type = $this->_get('type');
    $filter = ['relatively_id'=>$relatively_id,"type"=>$type];
    $comment_count = D("Comment")->comment_count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($comment_count,20);
    $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
    $this->assign('list',$comment_list);
    $this->display();
  }
  public function comment_info(){
    $parent_id = $this->_get('parent_id');
    $relatively_id = $this->_get('relatively_id');
    $type = $this->_get('type');
    $this->assign("parent_id",$parent_id);
    $this->assign("relatively_id",$relatively_id);
    $this->assign("type",$type);
    $this->assign("user_info",D("User")->where("user_id=$parent_id")->find());
    $this->display();
  }
  public function comment_save(){
    $M_Comment = D('Comment');
    $rules = array (
      array('content','require','请填写评论！',1),
    );
    if(!$M_Comment->validate($rules)->create()){
      $this->error($M_Comment->getError());
    }
    $data = $M_Comment->create();
    $data['add_time'] = time();
    $data['user_id'] = 100;
    $saveId=$M_Comment->data($data)->add();
    $this->success('操作成功！');
  }
  public function comment_del() {
    $M_Comment = D("Comment");
    $id= intval($this->_get('id'));
    //删除文章内容图片
    if ($M_Comment->where("id=$id")->delete()) {
      parent::admin_log(addslashes("ID($id)",'remove','Comment'));
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  public function comment_batch(){
    $M_Comment = D("Comment");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择评论！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          M('Comment')->where($in_ids)->delete();
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Comment');
          break;
        case 'button_black':
          /* 批量拉黑 */
          $Comment_list = M('Comment')->where($in_ids)->select();
          $black_user = array();
          foreach($Comment_list as $v){
            $black_user[] = $v['user_id'];
          }
          M('User')->where("user_id IN(".implode(",",array_unique($black_user)).")")->save(array('black'=>1));
          parent::admin_log("ID(".implode(",",array_unique($black_user)).")",'batch_add','Black');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function black_list(){
    // 筛选条件及排序
    $M_User = D("User");
    $filter = array();
    $filter['black'] = 1;
    $filter['record_count'] = $count = $M_User->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_User->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function black_save(){
    $id= intval($this->_get('id'));
    if (M('User')->where("user_id=$id")->save(array('black'=>1))) {
      parent::admin_log(addslashes("ID($id)",'add','Black'));
    } 
    $this->success("成功拉黑");
  }
  public function black_del(){
    $id= intval($this->_get('id'));
    if (M('User')->where("user_id=$id")->save(array('black'=>0))) {
      parent::admin_log(addslashes("ID($id)",'remove','Black'));
    } 
    $this->success("删除成功");
  }
  public function black_batch(){
    $M_Comment = D("Comment");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='user_id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择会员！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          M('User')->where($in_ids)->save(array('black'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Comment');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
}