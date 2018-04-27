<?php
class MeetingAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}
    public function meeting_list(){
        //蜂会列表
        $M_Meeting = D("Meeting");
        $count = $M_Meeting->count();
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("listRows",$page->listRows);
        $this->assign("list", $M_Meeting->lists($page->firstRow, $page->listRows));
        /************************banner*****************************/
        $banner = D('Ads')->lists(5,10);
        $this->assign('banner',$banner);
        if(IS_AJAX){
          $data = $this->fetch('Meeting:meeting_list_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function meeting_info(){
        //蜂会详情
        $id = $this->_get('id');
        if(!$id){
            $this->error();
        }
        $info = D("Meeting")->where("id=$id")->find();
        D("Meeting")->where("id=$id")->setInc("view_count","1");
        $count = M("MeetingSignup")->where("meeting_id=$id")->count();
        if(!$info){
            $this->error();
        }
        $this->assign("info",$info);
        $this->assign("count",$count);
        $this->display();
    }
    public function signup(){
        //蜂会报名
        parent::check_login();
        $id = $this->_get('id');
        $info = D("Meeting")->where("id=$id")->find();
        if($info['meeting_time']<=time()){
            $this->error();
        }
        $this->assign("info",$info);
        $this->display();
    }
    public function signup_save(){
      //蜂会报名
      parent::check_login();
      $M_MeetingSignup = M('MeetingSignup');
      $rules = array (
        array('name','require','请填写联系人',1),
        array('phone','number','请填写手机',1),
        array('meeting_id','number','请选择蜂会',1),
      );
      if (!$M_MeetingSignup->validate($rules)->create()){
        $this->error($M_MeetingSignup->getError());
      }
      $data = $M_MeetingSignup->create();
      $info = D("Meeting")->where("id=".$data['meeting_id'])->find();
      if(!$info||$info['meeting_time']<=time()){
          $this->error("蜂会报名日期已经结束");
      }
      if(!is_phone($data['phone'])){
          $this->error("请填写正确的手机号码");
      }
      $data['add_time'] = time();
      $data['user_id'] = $this->user_info['user_id'];
      $saveId=$M_MeetingSignup->data($data)->add();
      if($saveId){
        $this->success('报名成功');
      }else{
        $this->error('报名失败');
      }
    }
}