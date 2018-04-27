<?php

class MallAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Mall');
	}
  /**
   * 列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Mall = D("Mall");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    
    $filter['record_count'] = $count = $M_Mall->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Mall->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function info(){
    $M_Mall = D("Mall");
    $info = !empty($this->_get('id'))?$M_Mall->where("id=".$this->_get('id'))->find():'';
    $this->assign("info", $info);
    $this->display();
	}
  //保存
	public function save(){
    $M_Mall = D("Mall");
    $rules = array (
      array('title','require','请填写文章名称',1),
      array('integral','require','请填写积分',1),
    );
    if (!$M_Mall->validate($rules)->create()){
      $this->error($M_Mall->getError());
    }
    $id   = intval($this->_post('id'));
    $data = $M_Mall->create();
    unset($data['id']);

    $data['content']    = stripslashes(htmlspecialchars_decode($this->_post('content')));
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();

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

      $filename = 'Uploads/download/'.$date."/".$file_url['name'];
      $filename2 = iconv('utf-8','gbk',$filename);
      if(is_file($filename2)){
        $filename = $path.uniqid().".".substr(strrchr($file_url['name'],'.'),1);
        $filename2 = $filename;
      }
      move_uploaded_file($file_url['tmp_name'],$filename2);
      $data['file'] = $filename;
    }else{
      $data['file'] = $this->_post('file_url_text');
    }

    //图片上传
    if($_FILES['img']['error']===0){
      //获取缩略图大小
      $date = date("Y-m-d");
      $thumbPath='Uploads/Mall/thumb_img/'.$date.'/';
      $originalPath='Uploads/Mall/original_img/'.$date.'/';
      $thumbPrefix='thumb_,original_';
      $widthSize='348,750';
      $heightSize='260,0';

      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,true,2);
      $data['thumb_img']  = $thumbPath."thumb_".$upfile[0]['savename'];
      $data['original_img']  = $thumbPath."original_".$upfile[0]['savename'];
      water($data['original_img']);
    }
    if($id>0){
      $Mall = $M_Mall->where(array('id'=>$id))->find();
      $saveId=$M_Mall->where(array('id'=>$id))->save($data);
    }else{
      $saveId=$M_Mall->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        //删除旧图片
        // if(!empty($data['original_img'])&&$Mall['original_img']!=$data['original_img']){
        //   @unlink($Mall['original_img']);
        // }
        // if(!empty($data['thumb_img'])&&$Mall['thumb_img']!=$data['thumb_img']){
        //   @unlink($Mall['thumb_img']);
        // }
        parent::admin_log($data['title']."-ID($id)",'edit','Mall');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','Mall');
      }
      $this->success('提交成功！！',U('Mall/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Mall = D("Mall");
    $id= intval($this->_get('id'));
    $row = $M_Mall->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_Mall->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Mall');
      // @unlink($row['original_img']);
      // @unlink($row['thumb_img']);
      // @unlink($row['file']);
      remove_content_img($row['content']);
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 文章批量操作
   */
  public function batch(){
    $M_Mall = D("Mall");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择商品！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Mall_list = M('Mall')->where($in_ids)->select();
          M('Mall')->where($in_ids)->delete();
          foreach($Mall_list as $value){
            // @unlink($value['original_img']);
            // @unlink($value['thumb_img']);
            // @unlink($value['file']);
            remove_content_img($value['content']);
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Mall');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_Mall->where($in_ids)->save(array('is_open'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Mall');
          break;
        case 'button_show':
          /* 批量显示 */
          $M_Mall->where($in_ids)->save(array('is_open'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Mall');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function order_list(){
    //兑换列表
    $M_Mall = D("Mall");
    $id = $this->_get('id');
    $keyword = $this->_get('keyword');
    $mall_type = $this->_get('mall_type');
    $type = $this->_get('type');
    $strat_time = $this->_get('strat_time');
    $end_time = $this->_get('end_time');
    $filter = array('mall_id'=>$id,"keyword"=>$keyword,"mall_type"=>$mall_type,"type"=>$type,"strat_time"=>$strat_time,"end_time"=>$end_time);
    $filter['record_count'] = $count = $M_Mall->order_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    if($this->_get("excel")){
      Vendor('PHPExcel');
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '会员名称')->setCellValue('B1', '兑换日期')->setCellValue('C1', '礼物标题')->setCellValue('D1', '礼物类型')->setCellValue('E1', '获取途径')->setCellValue('F1', '花费积分')->setCellValue('G1', '收货人')->setCellValue('H1', '详细地址')->setCellValue('I1', '手机号码')->setCellValue('J1', '快递公司')->setCellValue('K1', '快递单号')->setCellValue('L1', '兑现情况');
      $list = $M_Mall->order_list(0,0,$filter);
      foreach($list as $k=>$v){
        $mall_type = $v['mall_type']==1?'电子素材':'实物礼品';
        $type = $v['type']==1?'积分兑换':'积分抽奖';
        $stauts = $v['stauts']==1?'是':'否';
        if(!empty($v['address'])){
          $address = $v['province']." ".$v['city']." ".$v['district']." ".$v['address'];
        }else{
          $address = '';
        }
        $objPHPExcel
        ->setActiveSheetIndex()
        ->setCellValue('A'.($k+2),$v['user_name'])
        ->setCellValue('B'.($k+2),date('Y-m-d H:i',$v['add_time']))
        ->setCellValue('C'.($k+2),$v['title'])
        ->setCellValue('D'.($k+2),$mall_type)
        ->setCellValue('E'.($k+2),$type)
        ->setCellValue('F'.($k+2),$v['integral'])
        ->setCellValue('G'.($k+2),$v['address_name'])
        ->setCellValue('H'.($k+2),$address)
        ->setCellValue('I'.($k+2),$v['mobile'])
        ->setCellValue('J'.($k+2),$v['delivery_firm'])
        ->setCellValue('K'.($k+2),$v['delivery'])
        ->setCellValue('L'.($k+2),$stauts);
      }
      $filename = '兑换列表';
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
    $this->assign("list", $M_Mall->order_list($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function order_info(){
    //兑换详情
    $M_Mall = D("Mall");
    $id = $this->_get('id');
    $info = $M_Mall->order_info($id);
    $this->assign("info", $info);
    $this->display();
  }
  public function order_save(){
    //订单修改
    $id   = intval($this->_post('id'));
    $data = M("MallOrder")->create();
    $saveId=M("MallOrder")->where(array('id'=>$id))->save($data);
    $this->success('提交成功！！',U('Mall/order_info',array('id'=>$id)));
  }
  public function lottery(){
    //积分抽奖
    $list = M("Lottery")->order('id ASC')->select();
    $lottery = array();
    foreach($list as $v){
      $v['lottery'] = $v['lottery']/1000;
      $lottery[$v['id']] = $v;
    }
    $this->assign("lottery",$lottery);
    $mall_list = M("Mall")->field("id,title")->select();
    $this->assign("mall_list",$mall_list);
    $this->display();
  }
  public function lottery_save(){
    //积分设置修改
    $value = $_POST['value'];
    foreach($value as $k=>$v){
      M("Lottery")->where("id=$k")->save(array("integral"=>$v['integral'],"content"=>$v['content'],"lottery"=>$v['lottery']*1000,"mall_id"=>$v['mall_id']));
    }
    parent::admin_log("",'edit','Lottery');
    $this->success('提交成功！！',U('Mall/lottery'));
  }
  public function lottery_order(){
    //积分抽奖记录
    $M_Mall = D("Mall");
    $id = $this->_get('id');
    $keyword = $this->_get('keyword');
    $lottery_id = $this->_get('lottery_id');
    $strat_time = $this->_get('strat_time');
    $end_time = $this->_get('end_time');
    $filter = array('mall_id'=>$id,"keyword"=>$keyword,"lottery_id"=>$lottery_id,"strat_time"=>$strat_time,"end_time"=>$end_time);

    if($this->_get("excel")){
      Vendor('PHPExcel');
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '会员名称')->setCellValue('B1', '奖项')->setCellValue('C1', '抽奖时间');
      $list = $M_Mall->lottery_log_list(0,0,$filter);
      foreach($list as $k=>$v){
        switch ($v['lottery_id']) {
          case '2':
            $lottery_id = '特等奖';
            break;
          case '3':
            $lottery_id = '一等奖';
            break;
          case '4':
            $lottery_id = '二等奖';
            break;
          case '5':
            $lottery_id = '三等奖';
            break;
          case '6':
            $lottery_id = '幸运奖';
            break;
          default:
            $lottery_id = '再接再厉';
            break;
        }
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.($k+2),$v['user_name'])->setCellValue('B'.($k+2),$lottery_id)->setCellValue('C'.($k+2),date('Y-m-d H:i',$v['add_time']));
      }
      $filename = '抽奖列表';
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



    $filter['record_count'] = $count = $M_Mall->lottery_log_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Mall->lottery_log_list($page->firstRow, $page->listRows,$filter));
    $this->display();
  }


  public function integral_type(){
    //积分赠送配置
    $list = M("IntegralType")->order('id ASC')->select();
    $this->assign("list",$list);
    $this->display();
  }
  public function integral_type_save(){
    //积分赠送配置
    $message = $_POST['message'];
    $integral = $_POST['integral'];
    $upper_limit = $_POST['upper_limit'];
    foreach($message as $k=>$v){
      $arr = array();
      if(isset($message[$k])){
        $arr['message'] = $message[$k];
      }
      if(isset($integral[$k])){
        $arr['integral'] = $integral[$k];
      }
      if(isset($upper_limit[$k])){
        $arr['upper_limit'] = $upper_limit[$k];
      }
      M("IntegralType")->where("id=$k")->save($arr);
    }
    parent::admin_log("",'edit','IntegralType');
    $this->success('提交成功！！');
  }

  public function integral_order(){
    $IntegralOrder = D("IntegralOrder");
    $id = $this->_get('id');
    $keyword = $this->_get('keyword');
    $strat_time = $this->_get('strat_time');
    $end_time = $this->_get('end_time');
    $filter = array('mall_id'=>$id,"keyword"=>$keyword,"strat_time"=>$strat_time,"end_time"=>$end_time);

    if($this->_get("excel")){
      Vendor('PHPExcel');
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '会员名称')->setCellValue('B1', '兑换金额')->setCellValue('C1', '对应积分')->setCellValue('D1', '单号')->setCellValue('E1', '日期');
      $list = $IntegralOrder->lists(0,0,$filter);
      foreach($list as $k=>$v){
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.($k+2),$v['user_name'])->setCellValue('B'.($k+2),$v['price'])->setCellValue('C'.($k+2),$v['integral'])->setCellValue('D'.($k+2),$v['out_trade_no'])->setCellValue('E'.($k+2),date('Y-m-d H:i',$v['pay_time']));
      }
      $filename = '积分兑换列表';
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

    $filter['record_count'] = $count = $IntegralOrder->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $IntegralOrder->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
}