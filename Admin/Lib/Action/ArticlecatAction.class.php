<?php

class ArticlecatAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();
		parent::admin_priv('Article');
	}
	/**
	* 文章分类列表
	*/
	public function index() {
		$M_Articlecat = D("Articlecat");
		$articlecat=$M_Articlecat->listArticlecat();
		foreach($articlecat as $k=>&$v){
			$v['target'] = "container";
			$v['url'] = U('Articlecat/info',array('cat_id'=>$v['cat_id']));
		}
		$this->assign("articlecat",json_encode($articlecat,JSON_UNESCAPED_UNICODE));
		$this->display();
  }
	/**
     * 文章分类详情页面
     */
	public function info(){
		$M_Articlecat = D("Articlecat");
		$articlecat=$M_Articlecat->listArticlecat();
		$info = !empty($this->_get('cat_id'))?$M_Articlecat->where("cat_id=".$this->_get('cat_id'))->find():'';
		$selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
		$this->assign("info", $info);
		$this->assign("cat_select", optionsDate(getTree($articlecat),$selected,$info['cat_id']));
		$this->display();
	}
	/**
     * 更新文章分类
     */
	public function save(){
		$M_Articlecat = D("Articlecat");
		$rules = array (
	    array('cat_name','require','请填写分类名称！',1),
		);
		if (!$M_Articlecat->validate($rules)->create()){
			$this->error($M_Articlecat->getError());
		}
		$cat_id = intval($this->_post('cat_id'));
		$data = $M_Articlecat->create();
		unset($data['cat_id']);
		if($cat_id>0){
			$saveId=$M_Articlecat->where(array('cat_id'=>$cat_id))->save($data);
		}else{
			$saveId=$M_Articlecat->data($data)->add();
			$cat_id = $saveId;
		}
		if($saveId){
			if($cat_id>0){
				parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'edit','articlecat');
			}else{
				parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'add','articlecat');
			}
			$this->success('提交成功！！',U('Articlecat/info',array('cat_id'=>$cat_id)));
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
		$M_Articlecat = D("Articlecat");
		$M_Article    = D("Article");
		$cat_id       = intval($this->_get('cat_id'));
		$cat = $M_Articlecat->where("cat_id=".$cat_id)->find();
		$cat_type = $cat['cat_type'];
		if ($cat_type == 2 ){
			/* 系统保留分类，不能删除 */
			$this->error('系统保留分类，不能删除');
		}
		$countChildcat=$M_Articlecat->where(array('parent_id'=>$cat_id))->count();
		if ($countChildcat > 0){
			/* 还有子分类，不能删除 */
			$this->error('还有子分类，不能删除');
		}
		/* 非空的分类不允许删除 */
		$countArticle=$M_Article->where(array('cat_id'=>$cat_id))->count();
		if ($countArticle > 0)
		{
			$this->error('非空的分类不允许删除');
		}
		else
		{
			if ($M_Articlecat->where("cat_id=" . $cat_id)->delete()) {
				parent::admin_log($cat['cat_name']."-ID($cat_id)",'remove','articlecat');
				$this->success("成功删除");
			} else {
				$this->error("删除失败，可能是不存在该ID的记录");
			}
		}   
  }
}