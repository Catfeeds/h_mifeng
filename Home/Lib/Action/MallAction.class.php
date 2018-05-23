<?php
class MallAction extends CommonAction {
	public function __construct() {
		parent::__construct();
        parent::check_login();
	}
    public function index(){
        //积分兑换首页
        $this->assign('user_info',$this->user_info);
        $this->assign('list',D('Mall')->lists(0,4,array('is_recommend'=>1)));
        $this->assign('list2',M('Mall')->alias("m")->field("m.*,COUNT('mo.id') as count")->join("left join ".table("mall_order")." as mo on mo.mall_id=m.id")->where("m.is_open=1")->limit(0,4)->group('m.id')->order("count DESC")->select());
        /************************banner*****************************/
        $banner = D('Ads')->lists(4,10);
        $this->assign('banner',$banner);

        // 按钮
        $btn_list = D('Ads')->lists(10,4);
        $this->assign('btn_list',$btn_list);

        // 标题
        $btn_title = D('Ads')->lists(14,2);
        $this->assign('btn_title',$btn_title);

        $this->display();
    }
    public function lists(){
        //列表
        $M_Mall = D("Mall");
        $type = $this->_get('type');
        $filter = array("type"=>$type);
        $count = $M_Mall->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Mall->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("type",$type);
        if(IS_AJAX){
          $data = $this->fetch('Mall:lists_ajax');
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
        $info = D("Mall")->info($id);
        $this->assign('info',$info);
        $this->assign("address_list",D('Address')->address_list($this->user_info['user_id']));
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));

        //电子素材判断是否兑换过
        if($info['type']==1){
            $user_info = session('userInfo');
            if($user_info){
                $this->assign('is_buy',D("Mall")->is_buy(array('user_id'=>$user_info['user_id'],'mall_id'=>$id)));
            }
        }
        
        $this->display();
    }
    public function buy(){
        //购买
        $id = $this->_post('id');
        $info = D("Mall")->info($id);
        if(!$info){
            $this->error("查找不到商品");
        }
        //查看是否兑换过
        
        //电子素材判断是否兑换过
        if($info['type']==1){
            $user_info = session('userInfo');
            if($user_info){
                $is_buy = D("Mall")->is_buy(array('user_id'=>$user_info['user_id'],'mall_id'=>$id));
                if($is_buy){
                    $this->success('您已经兑换过了！！',U('Mall/order_info',array('id'=>$is_buy['id'])));
                    exit;
                }
            }
        }

        if($info['type']==2){
            //判断收货地址
            $address_id=$this->_post('address_id');
            if(empty($address_id)){
                $this->error("请选址收货地址");
            }
        }
        if(parent::integral("-".$info['integral'],$this->user_info['user_id'],"兑换(".$info['title'].")")){
            $data = array();
            $data['integral']     = $info['integral'];
            $data['mall_id']      = $info['id'];
            $data['title']        = $info['title'];
            $data['type']         = 1;
            $data['mall_type']    = $info['type'];
            $data['original_img'] = $info['original_img'];
            $data['thumb_img']    = $info['thumb_img'];
            $data['content']      = $info['content'];
            $data['user_id']      = $this->user_info['user_id'];
            $data['add_time']     = time();
            if($info['type']==2){
                $address_info = D("Address")->where("address_id=$address_id")->find();
                $data['address_name'] = $address_info['address_name'];
                $data['address'] = $address_info['address'];
                $data['province'] = $address_info['province'];
                $data['city'] = $address_info['city'];
                $data['district'] = $address_info['district'];
                $data['mobile'] = $address_info['mobile'];
            }
            if($info['type']==1){
                $data['file'] = $info['file'];
            }
            $saveId=M("MallOrder")->data($data)->add();
            if($saveId){
                $this->success('兑换成功！！',U('Mall/order_info',array('id'=>$saveId)));
            }else{
                $this->error("兑换失败");
            }
        }else{
            $this->error("操作失败");
        }
    }
    public function order_list(){
        //兑换记录列表
        $M_Mall = D("Mall");
        $type = $this->_get('type')?$this->_get('type'):1;
        $this->assign("type",$type);
        $filter = array("user_id"=>$this->user_info['user_id'],'type'=>$type);
        $count = $M_Mall->order_count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list",$M_Mall->order_list($page->firstRow,$page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        if(IS_AJAX){
          $data = $this->fetch('Mall:order_list_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function order_info(){
        //兑换详情
        $id = $this->_get('id');
        $info = D("Mall")->order_info($id,$this->user_info['user_id']);
        $this->assign('info',$info);
        $this->assign("address_list",D('Address')->address_list($this->user_info['user_id']));
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    public function order_edit(){
        $id = $this->_post('order_id');
        $info = D("Mall")->order_info($id,$this->user_info['user_id']);
        if(!$info){
            $this->error("查找不到记录");
        }
        if($info['mall_type']==2){
            //判断收货地址
            $address_id=$this->_post('address_id');
            if(empty($address_id)){
                $this->error("请选址收货地址");
            }
        }
        $data = array();
        if($info['mall_type']==2){
            $address_info = D("Address")->where("address_id=$address_id")->find();
            $data['address_name'] = $address_info['address_name'];
            $data['address'] = $address_info['address'];
            $data['province'] = $address_info['province'];
            $data['city'] = $address_info['city'];
            $data['district'] = $address_info['district'];
            $data['mobile'] = $address_info['mobile'];
        }
        $saveId=M("MallOrder")->where("id=$id AND user_id=".$this->user_info['user_id'])->save($data);
        if($saveId){
            $this->success('提交成功！！',U('Mall/order_info',array('id'=>$id)));
        }else{
            $this->error("提交失败");
        }
    }

    public function lottery(){
        //积分抽奖
        $info = M("Lottery")->find(1);

        $this->assign("address_list",D('Address')->address_list($this->user_info['user_id']));
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->assign("info",$info);
        //中奖名单
        $lottery_log = M("LotteryLog")->alias("l")->field("l.add_time,l.lottery_id,u.user_name,u.pic,u.user_id")->join("left join ".table("user")." as u on u.user_id=l.user_id")->where("lottery_id<>7 AND u.user_id>0")->order("add_time DESC")->limit(30)->select();
        $this->assign("lottery_log",$lottery_log);
        $lottery_title = array("2"=>"特等奖","3"=>"一等奖","4"=>"二等奖","5"=>"三等奖","6"=>"幸运奖","7"=>"再接再历");
        $this->assign('lottery_title',$lottery_title);
        $this->assign('integral_7',1);
        $this->display();
    }
    public function integral_zs(){
        //分享朋友圈赠送积分
        D("Integral")->integral_zs($this->user_info['user_id'],7);
        D("Integral")->integral_zs($this->user_info['user_id'],5);
    }
    // public function integral_5(){
    //     //分享朋友圈赠送积分
    //     D("Integral")->integral_zs($this->user_info['user_id'],5);
    // }
    public function lottery_save(){
        //奖品分配，积分扣除
        $lottery_info = M("Lottery")->find(1);
        $user_id = $this->user_info['user_id'];
        $is_post = $this->_post('is_post');
        if(!$is_post){
            $this->error();
        }
        //每天抽3次
        $time = strtotime(date("Y-m-d"));
        $is_true = D('Integral')->integral_log(array("user_id"=>$user_id,'type'=>101,'start_time'=>$time,'end_time'=>$time+86399));
        if($is_true>=3){
            $this->ajaxReturn('',"您今天已经抽奖3次了，请明天再来吧",500);
        }
        //扣除积分
        if(!parent::integral("-".$lottery_info['integral'],$user_id,"积分抽奖",101)){
            $this->ajaxReturn('',"操作失败",500);
        }
        $list = M("Lottery")->where("id<>1")->order('lottery ASC')->select();
        $lottery = '';
        foreach($list as $k=>$v){
            $randNum = mt_rand(1,100000);
            if($randNum<=$v['lottery']){
                $lottery = $v;
                break;
            }
        }
        // 5;//+5(再接再历)
        // 3;//+3(幸运奖)
        // 2;//+2(三等奖)
        // 4;//+4(二等奖)
        // 0;//+0(一等奖)
        // 1;//+1(特等奖)
        $jianpin = array("2"=>1,"3"=>0,"4"=>4,"5"=>2,"6"=>3,"7"=>5);
        $lottery_title = array("2"=>"特等奖","3"=>"一等奖","4"=>"二等奖","5"=>"三等奖","6"=>"幸运奖","7"=>"再接再历");
        $data = array("lottery"=>$jianpin[$lottery['id']]);
        if(!empty($lottery['mall_id'])){
            //订单添加
            $info = D("Mall")->info($lottery['mall_id']);
            $arr = array();
            $arr['integral']     = $lottery_info['integral'];
            $arr['mall_id']      = $info['id'];
            $arr['title']        = $info['title'];
            $arr['type']         = 2;
            $arr['mall_type']    = $info['type'];
            $arr['original_img'] = $info['original_img'];
            $arr['thumb_img']    = $info['thumb_img'];
            $arr['content']      = $info['content'];
            $arr['user_id']      = $user_id;
            $arr['add_time']     = time();
            if($info['type']==1){
                $arr['file'] = $info['file'];
            }
            $saveId=M("MallOrder")->data($arr)->add();
            $data['mall'] = $info;
            $data['order_id'] = $saveId;
            $data['lottery_title'] = $lottery_title[$lottery['id']];
            $data['url'] = U('Mall/order_info',array('id'=>$saveId));
        }
        //抽奖记录
        $arr2 = array();
        $arr2['lottery_id'] = $lottery['id'];
        $arr2['user_id']    = $user_id;
        $arr2['add_time']   = time();
        $arr2['mall_id']    = isset($saveId)?$saveId:'0';
        M("LotteryLog")->data($arr2)->add();

        $integral_type = M("IntegralType")->where("type=7")->find();
        if($integral_type['upper_limit']>0){
          //判断每天次数上限
          $time = strtotime(date("Y-m-d"));
          $count = D("Integral")->integral_log(array('user_id'=>$user_id,'type'=>7,'start_time'=>$time,'end_time'=>$time+86399));
          if($integral_type['upper_limit']>$count){
            $data['integral_7'] = 1;
          }
        }
        $this->ajaxReturn($data,"获取成功",200);
    }
}
?>