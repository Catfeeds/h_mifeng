<?php

class InformationMaterialAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
		parent::admin_priv('Information');
	}
	/**
	* 文章分类列表
	*/
	public function index() {
		$M_InformationMaterial = D("InformationMaterial");
		$list=$M_InformationMaterial->lists();
		foreach($list as $k=>&$v){
			$v['target'] = "container";
			$v['url'] = U('InformationMaterial/info',array('material_id'=>$v['material_id']));
		}
		$this->assign("list",json_encode($list,JSON_UNESCAPED_UNICODE));
		$this->display();
  }
	/**
     * 文章分类详情页面
     */
	public function info(){
		$M_InformationMaterial = D("InformationMaterial");
		$list=$M_InformationMaterial->lists();
		$info = !empty($this->_get('material_id'))?$M_InformationMaterial->where("material_id=".$this->_get('material_id'))->find():'';
		$selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
		$this->assign("info", $info);
		$this->assign("select", optionsDate(getTree($list,"material_id"),$info['material_id'],0,$selected,"material_id","material_name"));
		$this->display();
	}
	/**
     * 更新文章分类
     */
	public function save(){
		$M_InformationMaterial = D("InformationMaterial");
		$rules = array (
	    array('material_name','require','请填写名称！',1),
		);
		if (!$M_InformationMaterial->validate($rules)->create()){
			$this->error($M_InformationMaterial->getError());
		}
		$material_id = intval($this->_post('material_id'));
		$data = $M_InformationMaterial->create();
		unset($data['material_id']);
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
		if($material_id>0){
			$info = $M_InformationMaterial->where(array('material_id'=>$material_id))->find();
			$saveId=$M_InformationMaterial->where(array('material_id'=>$material_id))->save($data);
		}else{
			$saveId=$M_InformationMaterial->data($data)->add();
			$material_id = $saveId;
		}
		if($saveId){
			if($material_id>0){
				parent::admin_log($this->_post('cat_name')."-ID($material_id)",'edit','InformationMaterial');
				if(!empty($data['original_img'])&&$info['original_img']!=$data['original_img']){
				  @unlink($info['original_img']);
				}
				if(!empty($data['thumb_img'])&&$info['thumb_img']!=$data['thumb_img']){
				  @unlink($info['thumb_img']);
				}
			}else{
				parent::admin_log($this->_post('cat_name')."-ID($material_id)",'add','InformationMaterial');
			}
			$this->success('提交成功！！',U('InformationMaterial/info',array('material_id'=>$material_id)));
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
		$M_InformationMaterial = D("InformationMaterial");
		$material_id = intval($this->_get('material_id'));
		$countChildcat=$M_InformationMaterial->where(array('parent_id'=>$material_id))->count();
		if ($countChildcat > 0){
			/* 还有子分类，不能删除 */
			$this->error('还有子分类，不能删除');
		}
		$info = $M_InformationMaterial->where(array('material_id'=>$material_id))->find();
		if ($M_InformationMaterial->where("material_id=" . $material_id)->delete()) {
			parent::admin_log($cat['material_name']."-ID($material_id)",'remove','InformationMaterial');
			@unlink($info['original_img']);
			@unlink($info['thumb_img']);
			$this->success("成功删除");
		} else {
			$this->error("删除失败，可能是不存在该ID的记录");
		}
  }
}