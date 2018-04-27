<?php

class InformationThemeAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
		parent::admin_priv('Information');
	}
	/**
	* 文章分类列表
	*/
	public function index() {
		$M_InformationTheme = D("InformationTheme");
		$list=$M_InformationTheme->lists();
		foreach($list as $k=>&$v){
			$v['target'] = "container";
			$v['url'] = U('InformationTheme/info',array('theme_id'=>$v['theme_id']));
		}
		$this->assign("list",json_encode($list,JSON_UNESCAPED_UNICODE));
		$this->display();
  }
	/**
     * 文章分类详情页面
     */
	public function info(){
		$M_InformationTheme = D("InformationTheme");
		$list=$M_InformationTheme->lists();
		$info = !empty($this->_get('theme_id'))?$M_InformationTheme->where("theme_id=".$this->_get('theme_id'))->find():'';
		$selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
		$this->assign("info", $info);
		$this->assign("select", optionsDate(getTree($list,"theme_id"),$selected,$info['theme_id'],0,"theme_id","theme_name"));
		$this->display();
	}
	/**
     * 更新文章分类
     */
	public function save(){
		$M_InformationTheme = D("InformationTheme");
		$rules = array (
	    array('theme_name','require','请填写名称！',1),
		);
		if (!$M_InformationTheme->validate($rules)->create()){
			$this->error($M_InformationTheme->getError());
		}
		$theme_id = intval($this->_post('theme_id'));
		$data = $M_InformationTheme->create();
		unset($data['theme_id']);
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
		if($theme_id>0){
			$info = $M_InformationTheme->where(array('theme_id'=>$theme_id))->find();
			$saveId=$M_InformationTheme->where(array('theme_id'=>$theme_id))->save($data);
		}else{
			$saveId=$M_InformationTheme->data($data)->add();
			$theme_id = $saveId;
		}
		if($saveId){
			if($theme_id>0){
				parent::admin_log($this->_post('cat_name')."-ID($theme_id)",'edit','InformationTheme');
				if(!empty($data['original_img'])&&$info['original_img']!=$data['original_img']){
				  @unlink($info['original_img']);
				}
				if(!empty($data['thumb_img'])&&$info['thumb_img']!=$data['thumb_img']){
				  @unlink($info['thumb_img']);
				}
			}else{
				parent::admin_log($this->_post('cat_name')."-ID($theme_id)",'add','InformationTheme');
			}
			$this->success('提交成功！！',U('InformationTheme/info',array('theme_id'=>$theme_id)));
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
		$M_InformationTheme = D("InformationTheme");
		$theme_id = intval($this->_get('theme_id'));
		$countChildcat=$M_InformationTheme->where(array('parent_id'=>$theme_id))->count();
		if ($countChildcat > 0){
			/* 还有子分类，不能删除 */
			$this->error('还有子分类，不能删除');
		}
		$info = $M_InformationTheme->where(array('theme_id'=>$theme_id))->find();
		if ($M_InformationTheme->where("theme_id=" . $theme_id)->delete()) {
			parent::admin_log($cat['theme_name']."-ID($theme_id)",'remove','InformationTheme');
			@unlink($info['original_img']);
			@unlink($info['thumb_img']);
			$this->success("成功删除");
		} else {
			$this->error("删除失败，可能是不存在该ID的记录");
		}
  }
}