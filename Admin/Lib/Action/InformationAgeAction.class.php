<?php

class InformationAgeAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
		parent::admin_priv('Information');
	}
	/**
	* 文章分类列表
	*/
	public function index() {
		$M_InformationAge = D("InformationAge");
		$list=$M_InformationAge->lists();
		foreach($list as $k=>&$v){
			$v['target'] = "container";
			$v['url'] = U('InformationAge/info',array('age_id'=>$v['age_id']));
		}
		$this->assign("list",json_encode($list,JSON_UNESCAPED_UNICODE));
		$this->display();
  }
	/**
     * 文章分类详情页面
     */
	public function info(){
		$M_InformationAge = D("InformationAge");
		$list=$M_InformationAge->lists();
		$info = !empty($this->_get('age_id'))?$M_InformationAge->where("age_id=".$this->_get('age_id'))->find():'';
		$selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
		$this->assign("info", $info);
		$this->assign("select", optionsDate(getTree($list,"age_id"),$selected,$info['age_id'],0,"age_id","age_name"));
		$this->display();
	}
	/**
     * 更新文章分类
     */
	public function save(){
		$M_InformationAge = D("InformationAge");
		$rules = array (
	    array('age_name','require','请填写名称！',1),
		);
		if (!$M_InformationAge->validate($rules)->create()){
			$this->error($M_InformationAge->getError());
		}
		$age_id = intval($this->_post('age_id'));
		$data = $M_InformationAge->create();
		unset($data['age_id']);
		if($_FILES['file_img']['error']===0){
		  $date = date("Y-m-d");
		  $thumbPath='Uploads/information_cat/thumb_img/'.$date.'/';
		  $originalPath='Uploads/information_cat/original_img/'.$date.'/';
		  $thumbPrefix='thumb_';
		  $widthSize='300';
		  $heightSize='300';
		  $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
		  $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
		  $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
		}

		if($age_id>0){
			$info = $M_InformationAge->where(array('age_id'=>$age_id))->find();
			$saveId=$M_InformationAge->where(array('age_id'=>$age_id))->save($data);
		}else{
			$saveId=$M_InformationAge->data($data)->add();
			$age_id = $saveId;
		}
		if($saveId){
			if($age_id>0){
				parent::admin_log($this->_post('cat_name')."-ID($age_id)",'edit','InformationAge');
				if(!empty($data['original_img'])&&$info['original_img']!=$data['original_img']){
				  @unlink($info['original_img']);
				}
				if(!empty($data['thumb_img'])&&$info['thumb_img']!=$data['thumb_img']){
				  @unlink($info['thumb_img']);
				}
			}else{
				parent::admin_log($this->_post('cat_name')."-ID($age_id)",'add','InformationAge');
			}
			$this->success('提交成功！！',U('InformationAge/info',array('age_id'=>$age_id)));
		}else{
      $this->error('提交失败，或未改动！！');
		}
		exit();
	}
	/**
     * 删除文章分类
     */
	public function del() {
		/* 权限判断 */
		$M_InformationAge = D("InformationAge");
		$age_id = intval($this->_get('age_id'));
		$countChildcat=$M_InformationAge->where(array('parent_id'=>$age_id))->count();
		if ($countChildcat > 0){
			/* 还有子分类，不能删除 */
			$this->error('还有子分类，不能删除');
		}
		$info = $M_InformationAge->where(array('age_id'=>$age_id))->find();
		if ($M_InformationAge->where("age_id=" . $age_id)->delete()) {
			parent::admin_log($cat['age_name']."-ID($age_id)",'remove','InformationAge');
			@unlink($info['original_img']);
			@unlink($info['thumb_img']);
			$this->success("成功删除");
		} else {
			$this->error("删除失败，可能是不存在该ID的记录");
		}
  }
}