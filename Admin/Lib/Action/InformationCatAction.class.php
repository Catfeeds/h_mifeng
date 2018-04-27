<?php

class InformationCatAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
		parent::admin_priv('Information');
	}
	/**
	* 文章分类列表
	*/
	public function index() {
		$M_InformationCat = D("InformationCat");
		$list=$M_InformationCat->lists();
		foreach($list as $k=>&$v){
			$v['target'] = "container";
			$v['url'] = U('InformationCat/info',array('cat_id'=>$v['cat_id']));
		}
		$this->assign("list",json_encode($list,JSON_UNESCAPED_UNICODE));
		$this->display();
  }
	/**
     * 文章分类详情页面
     */
	public function info(){
		$M_InformationCat = D("InformationCat");
		$list=$M_InformationCat->lists();
		$info = !empty($this->_get('cat_id'))?$M_InformationCat->where("cat_id=".$this->_get('cat_id'))->find():'';
		$selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
		$this->assign("info", $info);
		$this->assign("select", optionsDate(getTree($list,"cat_id"),$selected,$info['cat_id'],0,"cat_id","cat_name"));
		$this->display();
	}
	/**
     * 更新文章分类
     */
	public function save(){
		$M_InformationCat = D("InformationCat");
		$rules = array (
	    array('cat_name','require','请填写名称！',1),
		);
		if (!$M_InformationCat->validate($rules)->create()){
			$this->error($M_InformationCat->getError());
		}
		$cat_id = intval($this->_post('cat_id'));
		$data = $M_InformationCat->create();
		unset($data['cat_id']);
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
		if($cat_id>0){
			$info = $M_InformationCat->where(array('cat_id'=>$cat_id))->find();
			$saveId=$M_InformationCat->where(array('cat_id'=>$cat_id))->save($data);
		}else{
			$saveId=$M_InformationCat->data($data)->add();
			$cat_id = $saveId;
		}
		if($saveId){
			if($cat_id>0){
				parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'edit','InformationCat');
				if(!empty($data['original_img'])&&$info['original_img']!=$data['original_img']){
				  @unlink($info['original_img']);
				}
				if(!empty($data['thumb_img'])&&$info['thumb_img']!=$data['thumb_img']){
				  @unlink($info['thumb_img']);
				}
			}else{
				parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'add','InformationCat');
			}
			$this->success('提交成功！！',U('InformationCat/info',array('cat_id'=>$cat_id)));
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
		$M_InformationCat = D("InformationCat");
		$cat_id = intval($this->_get('cat_id'));
		$countChildcat=$M_InformationCat->where(array('parent_id'=>$cat_id))->count();
		if ($countChildcat > 0){
			/* 还有子分类，不能删除 */
			$this->error('还有子分类，不能删除');
		}
		$info = $M_InformationCat->where(array('cat_id'=>$cat_id))->find();
		if ($M_InformationCat->where("cat_id=" . $cat_id)->delete()) {
			parent::admin_log($cat['cat_name']."-ID($cat_id)",'remove','InformationCat');
			@unlink($info['original_img']);
			@unlink($info['thumb_img']);
			$this->success("成功删除");
		} else {
			$this->error("删除失败，可能是不存在该ID的记录");
		}
  }
}