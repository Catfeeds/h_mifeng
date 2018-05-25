<?php
class MemberAction extends CommonAction {
	public function __construct() {
		parent::__construct();
    parent::check_login();
    // parent::integral(10,5,"test");//积分操作
    $this->assign('nav_nid',5);
	}
  public function index() {
      //会员中心
      $user_info = $this->user_info;
      $this->assign('user_info',$user_info);
      //发布数量
      $this->assign("InformationCount",D('Information')->InformationCount(array('user_id'=>$user_info['user_id'])));
      //蜜蜂数量
      $this->assign("CollectCount",D('Collect')->count(array('user_id'=>$user_info['user_id'])));
      //点赞数量
      $this->assign("PraiseCount",D('Praise')->count(array('user_id'=>$user_info['user_id'])));
      //关注数量
      $this->assign("attention_count",D('Attention')->attention_count(array('attention_id'=>$user_info['user_id'])));
      //粉丝数量
      $this->assign("fans_count",D('Attention')->fans_count(array('fans_id'=>$user_info['user_id'])));
      //卡卷数量
      $this->assign("reel_count",D('Reel')->count(array('user_id'=>$user_info['user_id'])));
      //奖品数量
      $this->assign("mall_count",D('Mall')->order_count(array('user_id'=>$user_info['user_id'])));
      //今天是否签到
      $time = strtotime(date("Y-m-d"));
      $is_checkin = D('Integral')->integral_log(array("user_id"=>$user_info['user_id'],'type'=>1,'start_time'=>$time,'end_time'=>$time+86399));
      $this->assign('is_checkin',$is_checkin);

      $this->display();
  }
  public function user_site(){
    //设置
    $user_info = $this->user_info;
    $this->assign('user_info',$user_info);
    $this->display();
  }
  public function user_save(){
    //会员资料修改
    $M_User = D('User');
    $data = array();
    $data['user_name'] = $this->_post('user_name');
    $data['introduction'] = $this->_post('introduction');
    if($_FILES['pic']['error']===0){
      $date = date("Y-m-d");
      $thumbPath='Uploads/user/thumb_img/'.$date.'/';
      $originalPath='Uploads/user/original_img/'.$date.'/';
      $thumbPrefix='thumb_';
      $widthSize='80';
      $heightSize='80';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,true,2);
      $data['pic']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
    }
    $saveId = $M_User->where("user_id=".$this->user_info['user_id'])->data($data)->save();
    if($saveId){
      if(!empty($data['pic'])&&$this->user_info['pic']!='Uploads/user/avatar.png'){
        @unlink($this->user_info['pic']);
      }
      $this->success('修改成功！',U('Member/index'));
    }else{
      $this->error('请求错误！');
    }
  }
  public function user_index(){
    //观看别的会员主页
    $uid = $this->_get('uid');
    $u_info = M('User')->where("user_id=$uid")->find();
    if(!$u_info){
      $this->error("找不到此会员");
    }
    //发布
    $this->assign("InformationCount",D('Information')->InformationCount(array('user_id'=>$uid,"verify"=>1)));
    $this->assign("Informationlist",D('Information')->InformationList(0,2,array('user_id'=>$uid,"verify"=>1)));
    //蜜蜂
    $this->assign("CollectCount",D('Collect')->count(array('user_id'=>$uid)));
    $this->assign("Collectlist",D('Collect')->lists(0,2,array('user_id'=>$uid)));
    //点赞
    $this->assign("PraiseCount",D('Praise')->praise_count(array('user_id'=>$uid)));
    $this->assign("Praiselist",D('Praise')->praise_list(0,2,array('user_id'=>$uid)));

    //关注数量
    $this->assign("attention_count",D('Attention')->attention_count(array('attention_id'=>$uid)));
    //粉丝数量
    $this->assign("fans_count",D('Attention')->fans_count(array('fans_id'=>$uid)));

    //是否已经关注
    $this->assign('is_attention',D('Attention')->is_attention(array('user_id'=>$this->user_info['user_id'],'attention_id'=>$uid)));

    $user_info = $this->user_info;
    $this->assign('u_info',$u_info);
    $this->assign('user_info',$user_info);
    $this->display();
  }
  public function attention_save(){
    //关注会员操作
    $M_Attention = D('Attention');
    $rules = array (
      array('attention_id','number','请选择对象',1),
    );
    if(!$M_Attention->validate($rules)->create()){
      $this->ajaxReturn(500,$M_Attention->getError(),500);
    }
    $data['attention_id'] = $this->_post('attention_id');
    $data['user_id'] = $this->user_info['user_id'];
    $data['add_time'] = time();

    if($M_Attention->where("attention_id=".$data['attention_id']." AND user_id=".$data['user_id'])->delete()){
      $this->ajaxReturn(200,"取消成功",201);
    }
    $saveId=$M_Attention->data($data)->add();
    if($saveId){
      $this->ajaxReturn(200,"关注成功",200);
    }else{
      $this->ajaxReturn(500,"关注失败",500);
    }
  }
  public function attention_lists(){
    //关注列表
    $uid = $this->_get('uid');
    $attention_count = D("Attention")->attention_count(array('attention_id'=>$uid));
    import("ORG.Util.Page");//载入分页类
    $page = new Page($attention_count,8);
    $list = D('Attention')->attention_lists($page->firstRow,$page->listRows,array('attention_id'=>$uid,'user_id'=>$this->user_info['user_id']));
    $this->assign('list',$list);
    $this->assign('uid',$uid);
    $this->assign("listRows",$page->listRows);
    if(IS_AJAX){
      $data = $this->fetch('Member:ajax_attention_lists'); 
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function fans_lists(){
    //粉丝列表
    $uid = $this->_get('uid');
    $fans_count = D("Attention")->fans_count(array('fans_id'=>$uid));
    import("ORG.Util.Page");//载入分页类
    $page = new Page($fans_count,8);
    $list = D('Attention')->fans_lists($page->firstRow,$page->listRows,array('fans_id'=>$uid,'user_id'=>$this->user_info['user_id']));
    $this->assign('list',$list);
    
    $this->assign('uid',$uid);
    $this->assign("listRows",$page->listRows);

    if(IS_AJAX){
      $data = $this->fetch('Member:ajax_attention_lists');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function invite(){
    //邀请好友页面
    $user_info = $this->user_info;
    $invite_list = M('User')->where("invite_id=".$user_info['user_id'])->order("reg_time","desc")->select();
    $this->assign('user_info',$user_info);
    $this->assign('invite_list',$invite_list);
    $this->assign('url',"http://".$_SERVER['HTTP_HOST'].U('User/registered',array('invite_uid'=>$user_info['user_id'])));
    $this->display();
  }
  public function checkin(){
    //签到
    $this->checkin_save();
    $integral_type = M('IntegralType')->where('type=1')->find();
    $user_info = $this->user_info;
    $this->assign('invite_list',$invite_list);
    $this->assign('integral_type',$integral_type);
    $this->assign('list',D('Mall')->lists(0,4,array('is_recommend'=>1)));
    $starttime = strtotime(date("Y-m"));
    $endtime = strtotime("+1 month",strtotime(date("Y-m")))-1;
    $date = array();
    for($i=$starttime;$i<=$endtime;$i+=24*3600) {
        $date[$i]['time'] = $i;
        $date[$i]['is_checkin'] = D('Integral')->integral_log(array("user_id"=>$user_info['user_id'],'type'=>1,'start_time'=>$i,'end_time'=>$i+86399));
    }
    $this->assign('date',$date);
    //今天是否签到
    $time = strtotime(date("Y-m-d"));
    $is_checkin = D('Integral')->integral_log(array("user_id"=>$user_info['user_id'],'type'=>1,'start_time'=>$time,'end_time'=>$time+86399));
    $this->assign('is_checkin',$is_checkin);

    $this->display();
  }
  public function checkin_save(){
    //签到提交
    $user_info = $this->user_info;
    $time = strtotime(date("Y-m-d"));
    $is_checkin = D('Integral')->integral_log(array("user_id"=>$user_info['user_id'],'type'=>1,'start_time'=>$time,'end_time'=>$time+86399));
    if(!$is_checkin){
      //昨天是否有签到，没有的话断签
      $time2 = strtotime("-1 day",strtotime(date("Y-m-d")));
      $yesterday = D('Integral')->integral_log(array("user_id"=>$user_info['user_id'],'type'=>1,'start_time'=>$time2,'end_time'=>$time2+86399));
      if(!empty($yesterday)){
        M("User")->where("user_id=".$user_info['user_id'])->setInc('checkin',1);
      }else{
        M("User")->where("user_id=".$user_info['user_id'])->save(['checkin'=>1]);
      }
      D('Integral')->integral_zs($user_info['user_id'],1);
    }
    parent::check_login();
    $user_info = $this->user_info;
    if(IS_AJAX){
      $this->ajaxReturn($user_info,"签到成功",200);
    }
    // $this->success('签到成功！！',U('Member/checkin'));
  }
  public function integral_log(){
    //积分纪录
    $user_info = $this->user_info;
    $time = strtotime(date("Y-m-d"));
    $time2 = $time+86399;
    $day_integral = M("IntegralLog")->where("add_time>=$time AND add_time<=$time2 AND user_id=".$user_info['user_id']." AND integral>0 ")->sum('integral');
    $count = D("Integral")->count(array('user_id'=>$user_info['user_id']));
    import("ORG.Util.Page");//载入分页类
    $page = new Page($count,20);

    $list = D('Integral')->lists($page->firstRow,$page->listRows,array('user_id'=>$user_info['user_id']));
    $this->assign('list',$list);

    $this->assign('user_info',$user_info);
    $this->assign('day_integral',$day_integral);
    $this->assign("listRows",$page->listRows);
    if(IS_AJAX){
      $data = $this->fetch('Member:integral_log_list');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function information_add(){
    //干货投稿
    $M_Information = D('Information');
    $cat_list      = $M_Information->cat_list(array('parent_id'=>0));
    $age_list      = $M_Information->age_list(array('parent_id'=>0));
    $material_list = $M_Information->material_list(array('parent_id'=>0));
    $theme_list    = $M_Information->theme_list(array('parent_id'=>0));

    $this->assign("cat_list",$cat_list);
    $this->assign("age_list",$age_list);
    $this->assign("material_list",$material_list);
    $this->assign("theme_list",$theme_list);

    $this->display();
  }
  public function information_save(){
    //投稿提交
    $M_Information = D('Information');
    $rules = array (
      array('title','require','请填写标题！',1),
      array('cat_id','require','请选择分类！',1),
      // array('cat_id','require','请选择分类！',1),
    );
    if(!$M_Information->validate($rules)->create()){
      $this->error($M_Information->getError());
    }
    if($_FILES['file']['error']!==0){
      $this->error("请上传封面图");
    }
    $data = $M_Information->create();
    //附件
    if($_FILES['file_url']['error']===0){
      $file_url = $_FILES['file_url'];
      unset($_FILES['file_url']);
      $date = date("Y-m-d");
      import("ORG.Util.UploadFile"); 
      import("ORG.Util.File");
      $upload = new UploadFile(); // 实例化上传类 
      $path = 'Uploads/download/'.$date."/";
      File::make_dir($path);
      $filename = $path.uniqid().".".substr(strrchr($file_url['name'],'.'),1);
      move_uploaded_file($file_url['tmp_name'],$filename);
      $data['file_url'] = $filename;
    }

    if($_FILES['file']['error']===0){
      $date = date("Y-m-d");
      $thumbPath='Uploads/information/thumb_img/'.$date.'/';
      $originalPath='Uploads/information/original_img/'.$date.'/';
      $thumbPrefix='thumb_,original_';
      $widthSize='300,750';
      $heightSize='300,0';
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,true,2);
      if($upfile[0]['key']=="file"){
        $data['thumb_img']  = $thumbPath."thumb_".$upfile[0]['savename'];
        $data['original_img']  = $thumbPath."original_".$upfile[0]['savename'];
        water($data['original_img']);
        unset($upfile[0]);
        $upfile = array_merge($upfile);
      }
    }
    $data['user_id'] = $this->user_info['user_id'];
    $data['add_time'] = time();
    $saveId=$M_Information->data($data)->add();
    if($saveId){
      $content2 = $this->_post("content2");
      foreach($content2 as $k=>$v){
        $con = array();
        if($upfile[$k]['key']=="file2"){
          $con['thumb_img']  = $thumbPath."thumb_".$upfile[$k]['savename'];
          $con['original_img']  = $thumbPath."original_".$upfile[$k]['savename'];
          water($con['original_img']);
        }
        $con['sort_order'] = $k;
        $con['information_id'] = $saveId;
        $con['content'] = $content2[$k];
        M("InformationContent")->data($con)->add();
      }
      $this->success('提交成功！！',U('Member/information_list'));
    }else{
      $this->error("网络错误，请刷新！");
    }
  }
  public function information_list(){
    //发布干货列表
    $M_Information = D('Information');

    $filter = array();
    $uid = $this->_get('uid');
    if(empty($uid)){
      $uid = $this->user_info['user_id'];
    }else{
      $filter['verify']=1;
    }
    $filter['user_id']=$uid;
    $count = $M_Information->InformationCount($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($count,8);
    $list = $M_Information->InformationList($page->firstRow,$page->listRows,$filter);
    foreach($list as &$v){
      //读取内容图
      $v['child_content'] = M("InformationContent")->where("information_id=".$v['id'])->order("sort_order asc")->limit(2)->select();
    }
    $this->assign("filter",$filter);
    $this->assign("list",$list);
    $this->assign("listRows",$page->listRows);
    $this->assign("uid",$this->_get('uid'));
    if(IS_AJAX){
      $data = $this->fetch('Member:ajax_information_list');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  /**
   * 删除发布
   */
  public function information_del() {
    $M_Information = D("Information");
    $id= intval($this->_get('id'));
    $row = $M_Information->info($id);
    //删除文章内容图片
    if ($M_Information->where("id=$id AND user_id=".$this->user_info['user_id'])->delete()) {
      @unlink($row['thumb_img']);
      @unlink($row['original_img']);
      //删除评论
      M("Comment")->where("type=1 AND relatively_id=".$id)->delete();
      //删除收藏
      M("Collect")->where("type=1 AND relatively_id=".$id)->delete();
      //删除点赞
      M("Praise")->where("type=1 AND relatively_id=".$id)->delete();
      M("InformationContent")->where("information_id=$id")->delete();
      foreach($row['child_content'] as $v){
        @unlink($v['original_img']);
        @unlink($v['thumb_img']);
      }
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  public function collect_list(){
    //收藏列表
    $M_Collect = D('Collect');

    $filter = array();
    $uid = $this->_get('uid');
    if(empty($uid)){
      $uid = $this->user_info['user_id'];
    }
    $filter['user_id']=$uid;
    $count = $M_Collect->count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($count,8);
    $list = $M_Collect->lists($page->firstRow,$page->listRows,$filter);
    foreach($list as &$v){
      //读取内容图
      $v['child_content'] = M("InformationContent")->where("information_id=".$v['id'])->order("sort_order asc")->limit(2)->select();
    }
    $this->assign("filter",$filter);
    $this->assign("list",$list);
    $this->assign("listRows",$page->listRows);
    $this->assign("uid",$this->_get('uid'));
    if(IS_AJAX){
      $data = $this->fetch('Member:collect_list_ajax');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function collect_del(){
    //收藏删除
    $M_Praise = D('Collect');
    $id = $this->_get('id');
    $del = D("Collect")->where("id=".$id." AND user_id=".$this->user_info['user_id'])->delete();
    if($del){
      $this->success('删除成功！！');
    }else{
      $this->error("删除失败，可能是不存在该ID的记录");
    }
  }
  public function praise_list(){
    //点赞列表
    $M_Praise = D('Praise');
    $filter = array();
    $uid = $this->_get('uid');
    if(empty($uid)){
      $uid = $this->user_info['user_id'];
    }
    $filter['user_id']=$uid;
    $count = $M_Praise->praise_count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($count,8);
    $list = $M_Praise->praise_list($page->firstRow,$page->listRows,$filter);
    foreach($list as &$v){
      //读取内容图
      $v['child_content'] = M("InformationContent")->where("information_id=".$v['id'])->order("sort_order asc")->limit(2)->select();
    }
    $this->assign("filter",$filter);
    $this->assign("list",$list);
    $this->assign("listRows",$page->listRows);
    $this->assign("uid",$this->_get('uid'));
    if(IS_AJAX){
      $data = $this->fetch('Member:praise_list_ajax');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function praise_del(){
    //点赞删除
    $M_Praise = D('Praise');
    $id = $this->_get('id');
    $del = D("Praise")->where("id=".$id." AND user_id=".$this->user_info['user_id'])->delete();
    if($del){
      $this->success('删除成功！！');
    }else{
      $this->error("删除失败，可能是不存在该ID的记录");
    }
  }
  public function integral_type(){
    //赚积分
    $list = M("IntegralType")->select();
    $this->assign("list",$list);
    $this->display();
  }
  public function address_save(){
    //地址添加
    $M_Address = D('Address');
    $rules = array (
      array('province','require','请选址省份',1),
      array('city','require','请选址城市',1),
      array('district','require','请选址区域',1),
      array('address','require','请填写详细地址',1),
      array('address_name','require','请填写收货人姓名',1),
      array('mobile','require','请填写收货人手机号码',1),
    );
    if(!$M_Address->validate($rules)->create()){
      $this->error($M_Address->getError());
    }
    $data = $M_Address->create();
    $data['user_id'] = $this->user_info['user_id'];
    if(!is_phone($data['mobile'])){
      $this->error("请填写正确的手机号码");
    }
    $saveId=$M_Address->data($data)->add();
    if($saveId){
      if(IS_AJAX){
        $this->ajaxReturn(D("Address")->address_info($saveId),"成功",200);
      }
      $this->success('添加成功！！',U('Member/address_info',array('address_id'=>$id)));
    }else{
      $this->error("添加失败");
    }
  }
  public function contact_us(){
    //联系我们
    $this->display();
  }
  public function contact_us_list(){
    //联系我们列表
    $M_ContactUs = D("ContactUs");
    $filter = array("user_id"=>$this->user_info['user_id']);
    $count = $M_ContactUs->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count,8);
    $this->assign("list", $M_ContactUs->lists($page->firstRow, $page->listRows,$filter));
    $this->assign("listRows",$page->listRows);
    if(IS_AJAX){
      $data = $this->fetch('Member:contact_us_list_ajax');
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    $this->display();
  }
  public function contact_us_info(){
    //联系我们列表
    $M_ContactUs = D("ContactUs");
    $id = $this->_get('id');
    $this->assign("info", $M_ContactUs->info($id,$this->user_info['user_id']));
    $this->display();
  }
  public function contact_us_save(){
    //联系我们提交
    $M_ContactUs = D('ContactUs');
    $rules = array (
      array('title','require','请填写标题',1),
      array('content','require','请填写内容',1),
      array('phone','require','请填写手机号码  ',1),
      array('name','require','请填写姓名',1),
    );
    if(!$M_ContactUs->validate($rules)->create()){
      $this->error($M_ContactUs->getError());
    }
    $data = $M_ContactUs->create();
    if(!is_phone($data['phone'])){
      $this->error("请填写正确的手机号码");
    }
    $data['user_id'] = $this->user_info['user_id'];
    $data['add_time'] = time();
    $saveId=$M_ContactUs->data($data)->add();
    $this->success('提交成功！！',U('Member/index'));
  }
  public function integral_order(){
    require_once "wx/lib/WxPay.Api.php";
    require_once "wx/example/WxPay.JsApiPay.php";
    $price = $this->_request('price');
    if(!is_price_int($price)){
      $this->error("请输入整数金额");
    }
    if(isset($_GET['code'])){
      $data_arr = array();
      $data_arr['price'] = $price;
      $data_arr['user_id'] = $this->user_info['user_id'];
      $data_arr['out_trade_no'] = D("IntegralOrder")->out_trade_no();
      $integral_type = M("IntegralType")->where("id=8")->find();
      $data_arr['integral'] = $integral_type['integral']*$data_arr['price'];
      $saveId=M("IntegralOrder")->data($data_arr)->add();
    }
    
    //①、获取用户openid
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid("?price=$price");
    
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody("积分兑换");
    $input->SetAttach("积分兑换");
    $input->SetOut_trade_no($data_arr['out_trade_no']);
    $input->SetTotal_fee($data_arr['price']*100);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("积分兑换");
    $input->SetNotify_url("http://".$_SERVER['HTTP_HOST'].U('Index/notify'));
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $order = WxPayApi::unifiedOrder($input);
    // var_dump($data_arr['price']);exit;
    // echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
    // printf_info($order);
    $jsApiParameters = $tools->GetJsApiParameters($order);
    $this->assign("jsApiParameters",$jsApiParameters);

    //获取共享收货地址js函数参数
    $editAddress = $tools->GetEditAddressParameters();
    $this->assign("editAddress",$editAddress);
    $this->display();

  }

















  


















  
  public function collect_cat_list(){
    //收藏分类列表
    $list = D("CollectCat")->lists($this->user_info['user_id']);
    $this->assign("list",$list);
    $this->display();
  }
  public function collect_cat_info(){
    //收藏分类添加-修改
    $M_CollectCat = D('CollectCat');
    $id = $this->_get('id');
    if($id){
      $info = $M_CollectCat->where("id=$id")->find();
    }else{
      $info = array();
    }
    $this->assign("info",$info);
    $this->display();
  }
  public function collect_cat_save(){
    //收藏分类信息提交
    $M_CollectCat = D('CollectCat');
    $rules = array (
      array('title','require','请填写名称！',1),
    );
    if(!$M_CollectCat->validate($rules)->create()){
      $this->error($M_CollectCat->getError());
    }
    $id = intval($this->_post('id'));
    $data = $M_CollectCat->create();
    $data['user_id'] = $this->user_info['user_id'];
    unset($data['id']);
    if($id>0){
      $saveId=$M_CollectCat->where(array('article_id'=>$id))->save($data);
    }else{
      $saveId=$M_CollectCat->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      $this->success('提交成功！！',U('Member/collect',array('collect_id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
  }
}
?>