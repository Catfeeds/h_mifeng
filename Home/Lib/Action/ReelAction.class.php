<?php
class ReelAction extends CommonAction {
	public function __construct() {
		parent::__construct();
        parent::check_login();
	}
    public function index(){
        //首页
        $M_Reel = D("Reel");
        $this->assign("type1",$M_Reel->lists(0,2,array("user_id"=>$this->user_info['user_id'],'type'=>1)));
        $this->assign("type1_count",$M_Reel->count(array("user_id"=>$this->user_info['user_id'],'type'=>1)));
        $this->assign("type2",$M_Reel->lists(0,2,array("user_id"=>$this->user_info['user_id'],'type'=>2)));
        $this->assign("type2_count",$M_Reel->count(array("user_id"=>$this->user_info['user_id'],'type'=>2)));
        $this->assign("type3",$M_Reel->lists(0,2,array("user_id"=>$this->user_info['user_id'],'type'=>3)));
        $this->assign("type3_count",$M_Reel->count(array("user_id"=>$this->user_info['user_id'],'type'=>3)));
        $this->display();
    }
    public function lists(){
        //列表
        $M_Reel = D("Reel");
        $type = $this->_get('type');
        $filter = array("user_id"=>$this->user_info['user_id'],'type'=>$type);
        $count = $M_Reel->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Reel->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("type",$type);
        if(IS_AJAX){
          $data = $this->fetch('Reel:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function info(){
        //详情
        $id = $this->_get('id');
        $info = D("Reel")->info($id,$this->user_info['user_id']);
        if(!$info){
            $this->error();
        }
        $this->assign('info',$info);

        $this->assign("address_list",D('Address')->address_list($this->user_info['user_id']));
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    public function save(){
        $id = $this->_post('id');
        $info = D("Reel")->info($id,$this->user_info['user_id']);
        if(empty($info)){
            $this->error("操作错误");
        }
        if($info['end_time']<time()){
            $this->error("已过期");
        }
        if($info['type']==1){
            $address_id=$this->_post('address_id');
            if(empty($address_id)){
                $this->error("请选址收货地址");
            }
            $address_info = D("Address")->where("address_id=$address_id")->find();
            $data = array();
            $data['address_name'] = $address_info['address_name'];
            $data['address'] = $address_info['address'];
            $data['province'] = $address_info['province'];
            $data['city'] = $address_info['city'];
            $data['district'] = $address_info['district'];
            $data['mobile'] = $address_info['mobile'];
            $data['is_use'] = 1;
            M("ReelList")->where("id=$id AND user_id=".$this->user_info['user_id'])->save($data);
            $this->success('提交成功！！',U('Reel/info',array('id'=>$id)));
        }else{
            $mobile = $this->_post('mobile');
            if(!is_phone($mobile)){
                $this->error("请填写正确的手机号码");
            }
            $data = array("mobile"=>$mobile,"is_use"=>1);
            M("ReelList")->where(array('id'=>$id))->save($data);
            $this->success('提交成功！！',U('Reel/info',array('id'=>$id)));
        }
    }
    public function exchange(){
        //卡卷兑换
        $rules = array (
          array('serial_no','require','请输入兑换码',1),
        );
        if(!D("Reel")->validate($rules)->create()){
          $this->error(D("Reel")->getError());
        }
        $time = time()-84600;
        $error = M("ReelError")->where("add_time>$time AND user_id=".$this->user_info['user_id'])->count();
        if($error>10){
            $this->error("错误次数太多，请24小时后再试");
        }
        $serial_no = trim($this->_post('serial_no'));
        $info = M("Reel")->where("serial_no='$serial_no'")->find();
        if(!$info){
            $data = array("user_id"=>$this->user_info['user_id'],"add_time"=>time(),"serial_no"=>$serial_no);
            $saveId=M("ReelError")->data($data)->add();
            $this->error("查找不到兑换码");
        }
        if($info['user_id']){
            $this->error("此兑换码已被兑换");
        }
        $data = array("user_id"=>$this->user_info['user_id']);
        $saveId = M("Reel")->where("id=".$info['id'])->save($data);
        if($saveId){
            $this->success('兑换成功！！',U('Reel/info',array('id'=>$info['id'])));
        }else{
            $this->error("兑换失败");
        }
    }
}
?>