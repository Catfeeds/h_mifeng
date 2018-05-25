<?php

class UserAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('User');
	}
  /**
   * 列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_User = D("User");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    
    $filter['record_count'] = $count = $M_User->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    if($this->_get("excel")){
      $ex_arr = array();
      $ex_arr[] = [
        '名字',
        '手机',
        '注册日期',
        '积分',
      ];
      $list = $M_User->lists(0,0,$filter);
      foreach($list as $k=>$v){
        $ex_arr[] = [
          $v['user_name'],
          $v['phone'],
          date('Y-m-d H:i:s',$v['reg_time']),
          $v['integral'],
        ];
      }
      Excel("会员列表",$ex_arr);
    }
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_User->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function info(){
    $M_User = D("User");
    $info = !empty($this->_get('id'))?$M_User->where("user_id=".$this->_get('id'))->find():'';
    $this->assign("info", $info);
    $this->display();
	}
  //保存
	public function save(){
    $M_User = D("User");
    $id   = intval($this->_post('id'));
    $data = array();

    $data['user_name'] = $this->_post('user_name');
    $data['phone'] = $this->_post('phone');
    $password = $this->_post('password');
    $password2 = $this->_post('password2');
    if(!empty($password)){
      if($password2==$password){
        $data['password'] = md5($password);
      }else{
        $this->error('两次密码不一致');
      }
    }
    if($id>0){
      $User = $M_User->where(array('user_id'=>$id))->find();
      $saveId=$M_User->where(array('user_id'=>$id))->save($data);
    }else{
      $saveId=$M_User->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      $this->success('提交成功！！',U('User/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_User = D("User");
    $id= intval($this->_get('id'));
    $row = $M_User->where("user_id=" . $id)->find();
    //删除文章内容图片
    if ($M_User->where("user_id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','User');
      //删除干货
      $Information_list = M('Information')->where("user_id=$id")->select();
      M('Information')->where("user_id=$id")->delete();
      foreach($Information_list as $value){
        @unlink($value['thumb_img']);
        @unlink($value['original_img']);
        //删除评论
        M("Comment")->where("information_id=".$value['id'])->delete();
        //删除收藏
        M("Collect")->where("information_id=".$value['id'])->delete();
        //删除点赞
        M("Praise")->where("information_id=".$value['id'])->delete();
        $child_content = M("InformationContent")->where("information_id=".$value['id'])->select();
        M("InformationContent")->where("information_id=".$value['id'])->delete();
        foreach($child_content as $v){
          @unlink($v['thumb_img']);
          @unlink($v['original_img']);
        }
      }
      //删除评论
      M("Comment")->where("user_id=$id")->delete();
      //删除岗位
      $Recruitment_list = M('Recruitment')->where("user_id=$id")->select();
      M('Recruitment')->where("user_id=$id")->delete();
      M('Company')->where("user_id=$id")->delete();
      foreach($Recruitment_list as $value){
        M("ResumeCast")->where("recruitment_id=".$value['id'])->delete();
      }
      //删除简历
      $Resume = M('Resume')->where("user_id=$id")->select();
      M('Resume')->where("user_id=$id")->delete();
      foreach($Resume as $value){
        M("ResumeCast")->where("resume_id=".$value['id'])->delete();
        $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->select();
        foreach($ResumeImg as $v){
          @unlink($v['original_img']);
          @unlink($v['thumb_img']);
        }
        $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->delete();
      }
      //删除园区
      $Kindergarten_list = M('Kindergarten')->where("user_id=$id")->select();
      M('Kindergarten')->where("user_id=$id")->delete();
      foreach($Kindergarten_list as $value){
        M("KindergartenSignup")->where("kindergarten_id=".$value['id'])->delete();
      }
      //删除需求
      $Kindergarten_list = M('KindergartenXuqiu')->where("user_id=$id")->select();
      M('KindergartenXuqiu')->where("user_id=$id")->delete();
      foreach($Kindergarten_list as $value){
        M("KindergartenSignup")->where("kindergarten_xuqiu_id=".$value['id'])->delete();
      }
      
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  public function batch(){
    $M_User = D("User");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='user_id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择会员！');exit;
      }
      switch ($type) {
        case 'button_remove':
          $User_list = $M_User->where($in_ids)->select();
          $M_User->where($in_ids)->delete();
          foreach($User_list as $k=>$v){
            $id = $v['user_id'];
            //删除干货
            $Information_list = M('Information')->where("user_id=$id")->select();
            M('Information')->where("user_id=$id")->delete();
            foreach($Information_list as $value){
              @unlink($value['thumb_img']);
              @unlink($value['original_img']);
              //删除评论
              M("Comment")->where("information_id=".$value['id'])->delete();
              //删除收藏
              M("Collect")->where("information_id=".$value['id'])->delete();
              //删除点赞
              M("Praise")->where("information_id=".$value['id'])->delete();
              $child_content = M("InformationContent")->where("information_id=".$value['id'])->select();
              M("InformationContent")->where("information_id=".$value['id'])->delete();
              foreach($child_content as $v){
                @unlink($v['thumb_img']);
                @unlink($v['original_img']);
              }
            }
            //删除评论
            M("Comment")->where("user_id=$id")->delete();
            //删除岗位
            $Recruitment_list = M('Recruitment')->where("user_id=$id")->select();
            M('Recruitment')->where("user_id=$id")->delete();
            M('Company')->where("user_id=$id")->delete();
            foreach($Recruitment_list as $value){
              M("ResumeCast")->where("recruitment_id=".$value['id'])->delete();
            }
            //删除简历
            $Resume = M('Resume')->where("user_id=$id")->select();
            M('Resume')->where("user_id=$id")->delete();
            foreach($Resume as $value){
              M("ResumeCast")->where("resume_id=".$value['id'])->delete();
              $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->select();
              foreach($ResumeImg as $v){
                @unlink($v['original_img']);
                @unlink($v['thumb_img']);
              }
              $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->delete();
            }
            //删除园区
            $Kindergarten_list = M('Kindergarten')->where("user_id=$id")->select();
            M('Kindergarten')->where("user_id=$id")->delete();
            foreach($Kindergarten_list as $value){
              M("KindergartenSignup")->where("kindergarten_id=".$value['id'])->delete();
            }
            //删除需求
            $Kindergarten_list = M('KindergartenXuqiu')->where("user_id=$id")->select();
            M('KindergartenXuqiu')->where("user_id=$id")->delete();
            foreach($Kindergarten_list as $value){
              M("KindergartenSignup")->where("kindergarten_xuqiu_id=".$value['id'])->delete();
            }
          }
          /* 批量删除 */
          parent::admin_log(addslashes($row['title'])."-ID($in_ids)",'remove','User');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function integral(){
    //积分操作界面
    $M_User = D("User");
    $info = $M_User->where("user_id=".$this->_get('id'))->find();
    $this->assign("info", $info);
    $this->display();
  }
  public function integral_save(){
    $M_Integral = D("Integral");
    $id= intval($this->_post('id'));

    $integral = $this->_post('integral');
    $message = $this->_post('message');
    $M_Integral->integral($integral,$id,$message,102);
    $this->success('提交成功！！',U('User/integral',array('id'=>$id)));
  }

  // public function excel(){
  //   Vendor('PHPExcel');
  //   $objPHPExcel = new PHPExcel();

  //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '名字')->setCellValue('B1', '手机')->setCellValue('C1', '注册日期')->setCellValue('D1', '积分');

  //   $list = M("User")->order("reg_time DESC")->select();
  //   foreach($list as $k=>$v){
  //     $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.($k+2),$v['user_name'])->setCellValue('B'.($k+2),$v['phone'])->setCellValue('C'.($k+2),date('Y-m-d',$v['reg_time']))->setCellValue('D'.($k+2),$v['integral']);
  //   }
  //   $filename = '会员列表';
  //   ob_end_clean();//清除缓冲区,避免乱码 
  //   header('Content-Type: application/vnd.ms-excel');
  //   header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
  //   header('Cache-Control: max-age=0');
  //   // If you're serving to IE 9, then the following may be needed
  //   header('Cache-Control: max-age=1');
  //   // If you're serving to IE over SSL, then the following may be needed
  //   header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  //   header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
  //   header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  //   header('Pragma: public'); // HTTP/1.0
  //   $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
  //   $objWriter->save('php://output');

  // }
}