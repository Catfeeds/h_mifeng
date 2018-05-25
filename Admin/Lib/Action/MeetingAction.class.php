<?php

class MeetingAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Meeting');
	}
  /**
   * 列表
   */
  public function meeting_list(){
    $M_Meeting = D("Meeting");
    $count = $M_Meeting->count();
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    $this->assign("page", $showPage);
    $this->assign("list", $M_Meeting->lists($page->firstRow, $page->listRows));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function meeting_info(){
    $M_Meeting = D("Meeting");
    $info = !empty($this->_get('id'))?$M_Meeting->where("id=".$this->_get('id'))->find():'';
    $this->assign("info", $info);
    $this->display();
	}
  //保存
	public function meeting_save(){
    $M_Meeting = D("Meeting");
    $rules = array (
      array('title','require','请填写名称',1),
      array('meeting_address','require','请选择蜂会地点',1),
    );
    if (!$M_Meeting->validate($rules)->create()){
      $this->error($M_Meeting->getError());
    }
    $id = intval($this->_post('id'));
    if(empty($id)&&$_FILES['file_img']['error']!==0){
      $this->error("请上传图片");
    }
    $data = $M_Meeting->create();
    unset($data['id']);
    $data['content']    = stripslashes(htmlspecialchars_decode($this->_post('content')));
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    $data['meeting_time']   = $this->_post('meeting_time')?strtotime($this->_post('meeting_time')):time();
    //图片上传
    if($_FILES['file_img']['error']===0){
      $date = date("Y-m-d");
      $thumbPath='Uploads/Meeting/thumb_img/'.$date.'/';
      $originalPath='Uploads/Meeting/original_img/'.$date.'/';
      $thumbPrefix='thumb_';
      $widthSize='180';
      $heightSize='120';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
      $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
      $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
    }
    if($id>0){
      $Meeting = $M_Meeting->where(array('id'=>$id))->find();
      $saveId=$M_Meeting->where(array('id'=>$id))->save($data);
    }else{
      $saveId=$M_Meeting->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        //删除旧图片
        if(!empty($data['original_img'])&&$Meeting['original_img']!=$data['original_img']){
          @unlink($Meeting['original_img']);
        }
        if(!empty($data['thumb_img'])&&$Meeting['thumb_img']!=$data['thumb_img']){
          @unlink($Meeting['thumb_img']);
        }
        parent::admin_log($data['title']."-ID($id)",'edit','Meeting');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','Meeting');
      }
      $this->success('提交成功！！',U('Meeting/meeting_info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除
   */
  public function meeting_del() {
    $M_Meeting = D("Meeting");
    $id= intval($this->_get('id'));
    $row = $M_Meeting->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_Meeting->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Meeting');
      @unlink($row['original_img']);
      @unlink($row['thumb_img']);
      remove_content_img($row['content']);
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 批量操作
   */
  public function batch(){
    $M_Meeting = M('Meeting');
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择编号！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Meeting = $M_Meeting->where($in_ids)->select();
          $M_Meeting->where($in_ids)->delete();
          foreach($Meeting as $v){
            @unlink($v['original_img']);
            @unlink($v['thumb_img']);
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Meeting');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function meeting_signup(){
    //报名列表
    $M_Meeting = D("Meeting");
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['meeting_id']     = empty($this->_request('meeting_id')) ? 0 : intval($this->_request('meeting_id'));
    $filter['record_count'] = $count = $M_Meeting->signup_count($filter);
    if($this->_get("excel")){
      Vendor('PHPExcel');
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '蜂会')->setCellValue('B1', '姓名')->setCellValue('C1', '手机号')->setCellValue('D1', '报名日期');
      $list = $M_Meeting->signup_list(0,0,$filter);
      foreach($list as $k=>$v){
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.($k+2),$v['title'])->setCellValue('B'.($k+2),$v['name'])->setCellValue('C'.($k+2),$v['phone'])->setCellValue('D'.($k+2),date('Y-m-d H:i',$v['add_time']));
      }
      $filename = '蜂会报名列表';
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
    

    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();

    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Meeting->signup_list($page->firstRow, $page->listRows,$filter));
    $this->assign("meeting_select",$M_Meeting->lists(0,99999));
    $this->display();
  }
  //报名删除
  public function signup_del() {
    $M_MeetingSignup = M('MeetingSignup');
    $id= intval($this->_get('id'));
    $row = $M_MeetingSignup->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_MeetingSignup->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['name'])."-ID($id)",'remove','MeetingSignup');
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 报名批量操作
   */
  public function signup_batch(){
    $M_MeetingSignup = M('MeetingSignup');
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择编号！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $M_MeetingSignup->where($in_ids)->delete();
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','MeetingSignup');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
}