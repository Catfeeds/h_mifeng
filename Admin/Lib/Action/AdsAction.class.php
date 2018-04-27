<?php

class AdsAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
    	parent::admin_priv('Images');
	}
	/**
      +----------------------------------------------------------
     * 广告图列表
      +----------------------------------------------------------
     */
    public function lists() {
		$cat_id = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
		$width = empty($_REQUEST['width']) ? 0 : intval($_REQUEST['width']);
		$height = empty($_REQUEST['height']) ? 0 : intval($_REQUEST['height']);
		$M_Ads  = M('Ads');
		$adsList = $M_Ads->where(array('cat_id'=>$cat_id))->order('sort_order asc')->select();
		$counts = count($adsList);
		$this->assign("width",$width);
		$this->assign("height",$height);
		$this->assign("counts",$counts);
		$this->assign("cat_id",$cat_id);
		$this->assign("adsList",$adsList);
        $this->display();
    }
	

	/**
      +----------------------------------------------------------
     * 广告图详情
      +----------------------------------------------------------
     */
	public function info(){
		/* 权限判断 */
		$ads_id=empty($_GET['id'])?0:intval($_GET['id']);
		$cat_id = empty($_GET['cat_id'])?0:intval($_GET['cat_id']);
		$width=empty($_GET['width'])?750:intval($_GET['width']);
		$height=empty($_GET['height'])?300:intval($_GET['height']);
		$M_Ads = M("Ads");
		$info = !empty($ads_id)?$M_Ads->where("ads_id=".$ads_id)->find():'';
		$this->assign("cat_id", $cat_id);
		$this->assign("width",$width);
		$this->assign("height",$height);
		$this->assign("info", $info);
		$this->display();
	}
	
	/**
      +----------------------------------------------------------
     * 广告图提交
      +----------------------------------------------------------
     */
	public function save(){
		$M_Ads = M("Ads");
		$ads_id = intval($this->_post('ads_id'));
		$data = $M_Ads->create();
		$width=empty($_POST['width'])?750:intval($_POST['width']);
		$height=empty($_POST['height'])?300:intval($_POST['height']);
		unset($data['ads_id']);
		//图片上传
		if($_FILES['ads_img']['error']===0){
		  $date = date("Y-m-d");
		  $thumbPath='Uploads/ads_img/thumb_img/'.$date.'/';
		  $originalPath='Uploads/ads_img/original_img/'.$date.'/';
		  $thumbPrefix='thumb_';
		  $widthSize=$width;
		  $heightSize=$height;
		  $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
		  $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
		  $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
		}
		if($ads_id>0){
		  $article = $M_Ads->where(array('ads_id'=>$ads_id))->find();
      if(!empty($data['original_img'])&&$article['original_img']!=$data['original_img']){
        @unlink($article['original_img']);
      }
      if(!empty($data['thumb_img'])&&$article['thumb_img']!=$data['thumb_img']){
        @unlink($article['thumb_img']);
      }
		  $saveId=$M_Ads->where(array('ads_id'=>$ads_id))->save($data);
		}else{
		  $saveId=$M_Ads->data($data)->add();
		  $ads_id = $saveId;
		}
		if($ads_id){
        	parent::admin_log("ID($ads_id)",'edit','ads');
			$this->success('提交成功！！',U('Ads/lists/',array('cat_id'=>$data['cat_id'],'width'=>$width,'height'=>$height)));
		}else{
			$this->error('提交失败！！');
		}
		exit();
	}
	/**
      +----------------------------------------------------------
     * 删除广告图
      +----------------------------------------------------------
     */
	public function del() {
		/* 权限判断 */
		$M_Ads = M("Ads");
		$ads_id = $_REQUEST['ads_id']+0;
		$oldRow = $M_Ads->where("ads_id=" . $ads_id)->find();
		if ($M_Ads->where("ads_id=" . $ads_id)->delete()) {
			parent::admin_log("ID($ads_id)",'remove','ads');
			/* 删除旧图片 */
			@unlink($oldRow['thumb_img']);
			@unlink($oldRow['original_img']);
			$this->success("成功删除");
		} else {
			$this->error("删除失败，可能是不存在该ID的记录");
		} 
    }

}