<?php

class CourseAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Course');
	}
  /**
   * 列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Course = D("Course");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    
    $filter['record_count'] = $count = $M_Course->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Course->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function info(){
    $M_Course = D("Course");
    $info = !empty($this->_get('id'))?$M_Course->where("id=".$this->_get('id'))->find():'';
    $this->assign("info", $info);

    $cat_id=!empty($info)?$info['cat_id']:(!empty($this->_get('cat_id'))?$this->_get('cat_id'):0);
    $cat_list=$M_Course->cat_list();
    $this->assign("cat_select", optionsDate(getTree($cat_list),$cat_id));

    $this->display();
	}
  //保存
	public function save(){
    $M_Course = D("Course");
    $rules = array (
      array('title','require','请填写文章名称',1),
      array('integral','number','请填写文章名称',1),
    );
    if (!$M_Course->validate($rules)->create()){
      $this->error($M_Course->getError());
    }
    $id   = intval($this->_post('id'));
    $data = $M_Course->create();
    unset($data['id']);
    if($data['integral']<=0){
      $this->error("积分必须大于0");
    }
    $data['content']    = stripslashes(htmlspecialchars_decode($this->_post('content')));
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    //MP3
    // if($_FILES['mp3']['error']===0){
    //   $mp3 = $_FILES['mp3'];
    //   unset($_FILES['mp3']);
    //   $date = date("Y-m-d");
    //   $filename = 'Uploads/mp3/'.$mp3['name'];
    //   $filename2 = iconv('utf-8','gbk',$filename);
    //   move_uploaded_file($mp3['tmp_name'],$filename2);
    //   $data['mp3'] = $filename;
    // }else{
    //   $data['mp3'] = $this->_post('mp3_text');
    // }

    //图片上传
    if($_FILES['img']['error']===0){
      //获取缩略图大小
      $thumb = D("Coursecat")->where("cat_id=".$data['cat_id'])->find();
      $date = date("Y-m-d");
      $thumbPath='Uploads/Course/thumb_img/'.$date.'/';
      $originalPath='Uploads/Course/original_img/'.$date.'/';
      $thumbPrefix='thumb_';
      $widthSize='750';
      $heightSize='280';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
      $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
      $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
    }
    
    if($id>0){
      $Course = $M_Course->where(array('id'=>$id))->find();
      $saveId=$M_Course->where(array('id'=>$id))->save($data);
    }else{
      $saveId=$M_Course->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        //删除旧图片
        if(!empty($data['original_img'])&&$Course['original_img']!=$data['original_img']){
          @unlink($Course['original_img']);
        }
        if(!empty($data['thumb_img'])&&$Course['thumb_img']!=$data['thumb_img']){
          @unlink($Course['thumb_img']);
        }
        parent::admin_log($data['title']."-ID($id)",'edit','Course');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','Course');
      }
      $this->success('提交成功！！',U('Course/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Course = D("Course");
    $id= intval($this->_get('id'));
    $row = $M_Course->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_Course->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Course');
      @unlink($row['original_img']);
      @unlink($row['thumb_img']);
      remove_content_img($row['content']);
      //删除评论
      M("Comment")->where("type=3 AND relatively_id=".$id)->delete();
      //删除收藏
      M("Collect")->where("type=3 AND relatively_id=".$id)->delete();
      //删除点赞
      M("Praise")->where("type=3 AND relatively_id=".$id)->delete();
      //删除相关课程
      $child = M('CourseList')->where("course_id=".$id)->select();
      if (M('CourseList')->where("course_id=".$id)->delete()) {
        foreach($child as $v){
          @unlink($v['original_img']);
          @unlink($v['thumb_img']);
          @unlink($v['mp3']);
          remove_content_img($v['content']);
        }
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
    $M_Course = D("Course");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择课堂！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Course_list = M('Course')->where($in_ids)->select();
          M('Course')->where($in_ids)->delete();
          foreach($Course_list as $value){
            @unlink($value['original_img']);
            @unlink($value['thumb_img']);
            remove_content_img($value['content']);
            //删除评论
            M("Comment")->where("course_id=".$value['id'])->delete();
            //删除相关课程
            $child = M('CourseList')->where("course_id=".$value['id'])->select();
            if (M('CourseList')->where("course_id=".$value['id'])->delete()) {
              foreach($child as $v){
                @unlink($v['original_img']);
                @unlink($v['thumb_img']);
                @unlink($v['mp3']);
                remove_content_img($v['content']);
              }
            }
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Course');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_Course->where($in_ids)->save(array('is_open'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Course');
          break;
        case 'button_show':
          /* 批量显示 */
          $M_Course->where($in_ids)->save(array('is_open'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Course');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function order_list(){
    //兑换的会员列表
    $M_Course = D("Course");
    $id = $this->_get('id');
    $filter = array(
      'course_id'=>$id,
      'strat_time'=>$this->_get('strat_time'),
      'end_time'=>$this->_get('end_time'),
    );
    $filter['record_count'] = $count = $M_Course->order_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    if($this->_get("excel")){
      Vendor('PHPExcel');
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '会员名称')->setCellValue('B1', '兑换日期')->setCellValue('C1', '兑换课程');
      $Course_info = $M_Course->find($id);
      $list = $M_Course->order_list(0,0,$filter);
      foreach($list as $k=>$v){
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.($k+2),$v['user_name'])->setCellValue('B'.($k+2),date('Y-m-d H:i:s',$v['add_time']))->setCellValue('C'.($k+2),$Course_info['title']);
      }
      $filename = $Course_info['title'].'兑换列表';
      ob_end_clean();//清除缓冲区,避免乱码 
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
      $objWriter->save('php://output');
    }
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Course->order_list($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function child_list(){
    //课程列表
    $id = $this->_get('id');
    $M_Course = D("Course");
    $this->assign("course_info",$M_Course->where("id=$id")->find());
    $this->assign("list", $M_Course->course_lists($id));
    $this->display();
  }
  public function child_info(){
    //课程详情
    $info = !empty($this->_get('id'))?M('CourseList')->where("id=".$this->_get('id'))->find():'';
    $this->assign("info", $info);
    $this->assign("course_id",$this->_get('course_id'));
    $this->display();
  }
  public function child_save(){
    $M_CourseList = M('CourseList');
    $rules = array (
      array('title','require','请填写文章名称',1),
    );
    if (!$M_CourseList->validate($rules)->create()){
      $this->error($M_CourseList->getError());
    }
    $id   = intval($this->_post('id'));

    $data = $M_CourseList->create();
    unset($data['id']);
    $data['content']    = stripslashes(htmlspecialchars_decode($this->_post('content')));
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    //MP3
    // if($_FILES['mp3']['error']===0){
    //   $mp3 = $_FILES['mp3'];
    //   unset($_FILES['mp3']);
    //   $date = date("Y-m-d");
    //   import("ORG.Util.UploadFile"); 
    //   import("ORG.Util.File");
    //   $upload = new UploadFile(); // 实例化上传类 
    //   $path = 'Uploads/mp3/'.$date."/";
    //   File::make_dir($path);
    //   $filename = $path.$mp3['name'];
    //   $filename2 = iconv('utf-8','gbk',$filename);
    //   if(is_file($filename2)){
    //     $filename = $path.uniqid().".".substr(strrchr($mp3['name'],'.'),1);
    //     $filename2 = $filename;
    //   }
    //   move_uploaded_file($mp3['tmp_name'],$filename2);
    //   $data['mp3'] = $filename;
    // }else{
    //   $data['mp3'] = $this->_post('mp3_text');
    // }

    //图片上传
    if($_FILES['img']['error']===0){
      //获取缩略图大小
      $thumb = D("Coursecat")->where("cat_id=".$data['cat_id'])->find();
      $date = date("Y-m-d");
      $thumbPath='Uploads/Course/thumb_img/'.$date.'/';
      $originalPath='Uploads/Course/original_img/'.$date.'/';
      $thumbPrefix='thumb_';
      $widthSize='750';
      $heightSize='280';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
      $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
      $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
    }
    if($id>0){
      $CourseList = $M_CourseList->where(array('id'=>$id))->find();
      $saveId=$M_CourseList->where(array('id'=>$id))->save($data);
    }else{
      $saveId=$M_CourseList->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        //删除旧图片
        if(!empty($data['original_img'])&&$CourseList['original_img']!=$data['original_img']){
          @unlink($CourseList['original_img']);
        }
        if(!empty($data['thumb_img'])&&$CourseList['thumb_img']!=$data['thumb_img']){
          @unlink($CourseList['thumb_img']);
        }
        if(!empty($data['mp3'])&&$CourseList['mp3']!=$data['mp3']){
          @unlink($CourseList['mp3']);
        }
        parent::admin_log($data['title']."-ID($id)",'edit','CourseList');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','CourseList');
      }
      $this->success('提交成功！！',U('Course/child_list',array('id'=>$data['course_id'])));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  public function child_batch(){
    //课程批量操作
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择课堂！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Course_list = M('CourseList')->where($in_ids)->select();
          M('CourseList')->where($in_ids)->delete();
          foreach($Course_list as $value){
            @unlink($value['original_img']);
            @unlink($value['thumb_img']);
            @unlink($value['mp3']);
            remove_content_img($value['content']);
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','CourseList');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function child_del() {
    $id= intval($this->_get('id'));
    $row = M('CourseList')->where("id=" . $id)->find();
    //删除文章内容图片
    if (M('CourseList')->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','CourseList');
      @unlink($row['original_img']);
      @unlink($row['thumb_img']);
      @unlink($row['mp3']);
      remove_content_img($row['content']);
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }


  /**
  * 分类列表
  */
  public function cat_list() {
    $M_Course = D("Course");
    $cat_list=$M_Course->cat_list();
    foreach($cat_list as $k=>&$v){
      $v['target'] = "container";
      $v['url'] = U('Course/cat_info',array('cat_id'=>$v['cat_id']));
    }
    $this->assign("cat_list",json_encode($cat_list,JSON_UNESCAPED_UNICODE));
    $this->display();
  }
  /**
   * 分类详情页面
   */
  public function cat_info(){
    $M_Course = D("Course");
    $cat_list=$M_Course->cat_list();
    $info = !empty($this->_get('cat_id'))?M("CourseCat")->where("cat_id=".$this->_get('cat_id'))->find():'';
    $selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
    $this->assign("info", $info);
    $this->assign("cat_select", optionsDate(getTree($cat_list),$selected,$info['cat_id']));
    $this->display();
  }
  /**
   * 更新分类
   */
  public function cat_save(){
    $M_CourseCat = M("CourseCat");
    $rules = array (
      array('cat_name','require','请填写分类名称！',1),
    );
    if (!$M_CourseCat->validate($rules)->create()){
      $this->error($M_CourseCat->getError());
    }
    $cat_id = intval($this->_post('cat_id'));
    $data = $M_CourseCat->create();
    unset($data['cat_id']);
    if($cat_id>0){
      $saveId=$M_CourseCat->where(array('cat_id'=>$cat_id))->save($data);
    }else{
      $saveId=$M_CourseCat->data($data)->add();
      $cat_id = $saveId;
    }
    if($saveId){
      if($cat_id>0){
        parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'edit','CourseCat');
      }else{
        parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'add','CourseCat');
      }
      $this->success('提交成功！！',U('Course/cat_info',array('cat_id'=>$cat_id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除分类
   */
  public function cat_del() {
    /* 权限判断 */
    $M_CourseCat = M("CourseCat");
    $M_Course = D("Course");
    $cat_id       = intval($this->_get('cat_id'));
    $cat = $M_CourseCat->where("cat_id=".$cat_id)->find();
    $cat_type = $cat['cat_type'];
    if ($cat_type == 2 ){
      /* 系统保留分类，不能删除 */
      $this->error('系统保留分类，不能删除');
    }
    $countChildcat=$M_CourseCat->where(array('parent_id'=>$cat_id))->count();
    if ($countChildcat > 0){
      /* 还有子分类，不能删除 */
      $this->error('还有子分类，不能删除');
    }
    /* 非空的分类不允许删除 */
    $count=M('Course')->where(array('cat_id'=>$cat_id))->count();
    if ($count > 0)
    {
      $this->error('非空的分类不允许删除');
    }
    else
    {
      if ($M_CourseCat->where("cat_id=" . $cat_id)->delete()) {
        parent::admin_log($cat['cat_name']."-ID($cat_id)",'remove','CourseCat');
        $this->success("成功删除");
      } else {
        $this->error("删除失败，可能是不存在该ID的记录");
      }
    }   
  }



  public function comment_list(){
    //评论列表
    $course_id = $this->_get('course_id');
    $filter = array();
    if($course_id){
      $filter['course_id'] = $course_id;
    }else{
      $filter['course_id'] = 0;
    }
    $comment_count = D("Comment")->comment_count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($comment_count,20);
    $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
    $this->assign('list',$comment_list);
    $this->display();
  }
  public function comment_info(){
    $parent_id = $this->_get('parent_id');
    $course_id = $this->_get('course_id');
    $this->assign("parent_id",$parent_id);
    $this->assign("course_id",$course_id);
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