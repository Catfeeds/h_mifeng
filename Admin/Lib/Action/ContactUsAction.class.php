<?php

class ContactUsAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('ContactUs');
	}
  public function lists(){
    //积分抽奖记录
    $M_ContactUs = D("ContactUs");
    $keyword = $this->_get('keyword');
    $filter = array("keyword"=>$keyword);
    $filter['record_count'] = $count = $M_ContactUs->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    if($this->_get("excel")){
      $ex_arr = array();
      $ex_arr[] = [
        '标题',
        '姓名',
        '手机号码',
        '添加时间',
        '内容',
        '回复内容',
      ];
      $list = $M_ContactUs->lists(0,0,$filter);
      foreach($list as $k=>$v){
        $ex_arr[] = [
          $v['title'],
          $v['name'],
          $v['phone'],
          date('Y-m-d H:i:s',$v['add_time']),
          $v['content'],
          $v['return_content'],
        ];
      }
      Excel("留言列表",$ex_arr);
    }
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_ContactUs->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function info(){
    $M_ContactUs = D("ContactUs");
    $id = $this->_get('id');
    $info = $M_ContactUs->where("id=$id")->find();
    $this->assign("info", $info);
    $this->display();
  }
  public function save(){
    $M_ContactUs = D("ContactUs");
    $id = $this->_post('id');
    $data = $M_ContactUs->create();
    $saveId=$M_ContactUs->where(array('id'=>$id))->save($data);
    if($saveId){
      $this->success('提交成功！！',U('ContactUs/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_ContactUs = D("ContactUs");
    $id= intval($this->_get('id'));
    $row = $M_ContactUs->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_ContactUs->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['phone'])."-ID($id)",'remove','ContactUs');
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
    $M_ContactUs = D("ContactUs");
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
          $ContactUs_list = M('ContactUs')->where($in_ids)->select();
          M('ContactUs')->where($in_ids)->delete();
          foreach($ContactUs_list as $value){
            // @unlink($value['original_img']);
            // @unlink($value['thumb_img']);
            // @unlink($value['file']);
            remove_content_img($value['content']);
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','ContactUs');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_ContactUs->where($in_ids)->save(array('is_open'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','ContactUs');
          break;
        case 'button_show':
          /* 批量显示 */
          $M_ContactUs->where($in_ids)->save(array('is_open'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','ContactUs');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
}