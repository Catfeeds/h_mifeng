<?php

class ReelAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Reel');
	}
  /**
   * 列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Reel = D("Reel");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['type']    = empty($this->_request('type')) ? '' : trim($this->_request('type'));
    
    $filter['record_count'] = $count = $M_Reel->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Reel->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function info(){
    $M_Reel = D("Reel");
    $info = !empty($this->_get('id'))?$M_Reel->info($this->_get('id')):'';
    $this->assign("info", $info);
    $filter = array();
    $filter['cat_id']     = 81;
    $filter['sort_by']    = empty($this->_request('sort_by')) ? 'a.sort_order' : trim($this->_request('sort_by'));
    $filter['sort_order'] = empty($this->_request('sort_order')) ? 'asc' : trim($this->_request('sort_order'));
    $template = D("Article")->listArticle(0,9999,$filter);
    $this->assign("template",$template);
    $this->display();
	}
  //保存
	public function save(){
    $M_Reel = D("Reel");
    $rules = array (
      array('template','require','请填写模版',1),
      array('end_time','require','请填写有效期',1),
      array('price','require','请填写有价值',1),
    );
    if (!$M_Reel->validate($rules)->create()){
      $this->error($M_Reel->getError());
    }
    $id   = intval($this->_post('id'));
    $data = $M_Reel->create();
    unset($data['id']);
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    $data['end_time']   = $this->_post('end_time')?strtotime($this->_post('end_time')):time();
    if($id>0){
      $Reel = $M_Reel->where(array('id'=>$id))->find();
      $saveId=$M_Reel->where(array('id'=>$id))->save($data);
    }else{
      $data['serial_no'] = md5(uniqid(md5(microtime(true)),true));
      $saveId=$M_Reel->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        parent::admin_log($data['title']."-ID($id)",'edit','Reel');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','Reel');
      }
      $this->success('提交成功！！',U('Reel/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  public function zengsong(){
    //赠送卡卷
    $id = $this->_get('id');
    $this->assign("id",$id);
    $user_list = D("User")->lists(0,99999);
    $this->assign("user_list",$user_list);
    $this->display();
  }
  public function zengsong_save(){
    $user_id = $this->_post('user_id');
    $id = $this->_post('id');
    $info = D("Reel")->info($id);
    $data = array("user_id"=>0,"type"=>$info['type'],"end_time"=>$info['end_time'],"add_time"=>time(),"price"=>$info['price'],"template"=>$info['template']);
    foreach($user_id as $v){
      $data['user_id'] = $v;
      M("ReelList")->data($data)->add();
    }
    $this->success('提交成功！！',U('Reel/lists'));
  }
  /**
   * 列表
   */
  public function lists2(){
    // 筛选条件及排序
    $M_Reel = D("Reel");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['type']    = empty($this->_request('type')) ? '' : trim($this->_request('type'));
    
    $filter['record_count'] = $count = $M_Reel->count2($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Reel->lists2($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  /**
   * 详情页面
   */
  public function info2(){
    $M_Reel = D("Reel");
    $info = !empty($this->_get('id'))?$M_Reel->info2($this->_get('id')):'';
    $this->assign("info", $info);
    $filter = array();
    $filter['cat_id']     = 81;
    $filter['sort_by']    = empty($this->_request('sort_by')) ? 'a.sort_order' : trim($this->_request('sort_by'));
    $filter['sort_order'] = empty($this->_request('sort_order')) ? 'asc' : trim($this->_request('sort_order'));
    $template = D("Article")->listArticle(0,9999,$filter);
    $this->assign("template",$template);
    $this->display();
  }
  public function save2(){
    $M_Reel = M("ReelList");
    $rules = array (
      array('id','require','请填写卡卷',1),
      array('template','require','请填写模版',1),
      array('end_time','require','请填写有效期',1),
      array('price','require','请填写有价值',1),
    );
    if (!$M_Reel->validate($rules)->create()){
      $this->error($M_Reel->getError());
    }
    $id   = intval($this->_post('id'));
    $data = $M_Reel->create();
    unset($data['id']);
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    $data['end_time']   = $this->_post('end_time')?strtotime($this->_post('end_time')):time();

    $Reel = $M_Reel->where(array('id'=>$id))->find();
    $saveId=$M_Reel->where(array('id'=>$id))->save($data);
    
    if($saveId){
      if($id>0){
        parent::admin_log($data['title']."-ID($id)",'edit','ReelList');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','ReelList');
      }
      $this->success('提交成功！！',U('Reel/lists2',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除文章
   */
  public function del2() {
    $id= intval($this->_get('id'));
    //删除文章内容图片
    if (M("ReelList")->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','ReelList');
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Reel = D("Reel");
    $id= intval($this->_get('id'));
    $row = $M_Reel->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_Reel->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Reel');
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 文章批量操作
   */
  public function batch(){
    $M_Reel = D("Reel");
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
          $Reel_list = M('Reel')->where($in_ids)->select();
          M('Reel')->where($in_ids)->delete();
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Reel');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_Reel->where($in_ids)->save(array('is_open'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Reel');
          break;
        case 'button_show':
          /* 批量显示 */
          $M_Reel->where($in_ids)->save(array('is_open'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','Reel');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  public function order_list(){
    //兑换列表
    $M_Reel = D("Reel");
    $id = $this->_get('id');
    $keyword = $this->_get('keyword');
    $filter = array('Reel_id'=>$id,"keyword"=>$keyword);
    $filter['record_count'] = $count = $M_Reel->order_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Reel->order_list($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function order_info(){
    //兑换详情
    $M_Reel = D("Reel");
    $id = $this->_get('id');
    $info = $M_Reel->order_info($id);
    $this->assign("info", $info);
    $this->display();
  }
  public function order_save(){
    //订单修改
    $id   = intval($this->_post('id'));
    $data = M("ReelOrder")->create();
    $saveId=M("ReelOrder")->where(array('id'=>$id))->save($data);
    $this->success('提交成功！！',U('Reel/order_info',array('id'=>$id)));
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
    $Reel_list = M("Reel")->field("id,title")->select();
    $this->assign("Reel_list",$Reel_list);
    $this->display();
  }
  public function lottery_save(){
    //积分设置修改
    $value = $_POST['value'];
    foreach($value as $k=>$v){
      M("Lottery")->where("id=$k")->save(array("integral"=>$v['integral'],"content"=>$v['content'],"lottery"=>$v['lottery']*1000,"Reel_id"=>$v['Reel_id']));
    }
    $this->success('提交成功！！',U('Reel/lottery'));
  }
  public function lottery_order(){
    //积分抽奖记录
    $M_Reel = D("Reel");
    $id = $this->_get('id');
    $keyword = $this->_get('keyword');
    $filter = array('Reel_id'=>$id,"keyword"=>$keyword);
    $filter['record_count'] = $count = $M_Reel->lottery_log_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Reel->lottery_log_list($page->firstRow, $page->listRows,$filter));
    $this->display();
  }


  public function user_key(){
    $user_name = $this->_post("user_name");
    $user_list = D("User")->lists(0,99999,array('keyword'=>$user_name));
    $this->assign("user_list",$user_list);
    if(IS_AJAX){
      $data = $this->fetch('Reel:user_option');
      $this->ajaxReturn($data,"获取成功",200);
    }
  }
}