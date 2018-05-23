<?php
class CourseAction extends CommonAction {
	public function __construct() {
		parent::__construct();
        $this->assign('nav_nid',4);
	}
    public function lists(){
        //大课程列表
        $M_Course = D("Course");
        $cat_id = $this->_get('cat_id');
        $this->assign('cat_id',$cat_id);
        $filter = array();
        $filter['cat_id'] = $cat_id;
        $count = $M_Course->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Course->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);

        /************************banner*****************************/
        $banner = D('Ads')->lists(3,10);
        $this->assign('banner',$banner);
        $cat_list = M("CourseCat")->where("parent_id=0 AND cat_type=1")->order(SO)->select();
        $this->assign('cat_list',$cat_list);

        if(IS_AJAX){
          $data = $this->fetch('Course:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function detail(){
        //大课程详情
        $id = $this->_get('id');

        $filter = ['relatively_id'=>$id,'type'=>3];
        $this->assign('comment_type',3);
        $this->assign('relatively_id',$id);

        $info = D("Course")->info($id);
        $user_info = session('userInfo');
        $info['is_pay'] = D("Course")->is_pay($id,$user_info['user_id']);
        $this->assign('info',$info);
        //评论
        $comment_count = D("Comment")->comment_count($filter);
        import("ORG.Util.Page");//载入分页类
        $page = new Page($comment_count,16);
        $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
        $this->assign("listRows",$page->listRows);
        $this->assign('comment_list',$comment_list);
        $this->assign('comment_count',$comment_count);

        //点赞
        $praise_count = D("Praise")->praise_count($filter);
        import("ORG.Util.Page");//载入分页类
        $page2 = new Page($praise_count,60);
        $praise_list = D("Praise")->praise_list($page2->firstRow,$page2->listRows,$filter);
        $this->assign('praise_list',$praise_list);
        $this->assign('praise_count',$praise_count);

        $user_info = session('userInfo');
        if($user_info){
          $filter['user_id'] = $user_info['user_id'];
          //是否点赞
          $this->assign('is_praise',D('Praise')->is_praise($filter));
          $this->assign('user_info',$user_info);
        }

        
        //积分抵扣卷
        $this->assign("reel_list",D('Reel')->lists(0,9999,array("type"=>3,"is_use"=>0,"user_id"=>$user_info['user_id'],"end_time"=>time())));
        $this->display();
    }
    
    public function course_info(){
        //课程详情
        parent::check_login();
        $id = $this->_get('id');
        $M_Course = D("Course");
        $info = M('Course_list')->where("id=$id")->find();
        if(!$M_Course->is_pay($info['course_id'],$this->user_info['user_id'])&&$info['try_out']==0){
            $this->error("请先兑换课程");
        }
        $this->assign('info',$info);
        $this->display();
    }
    
    public function pay(){
        //购买
        parent::check_login();
        $id = $this->_request('id');
        $reel_id = $this->_request('reel_id');
        
        $info = M("Course")->where("id=$id")->find();
        if(!$info){
            $this->error("查找不到课程");
        }
        $is_pay = D('Course')->is_pay($id,$this->user_info['user_id']);
        if($is_pay){
            $this->error("您已经兑换了");
        }
        if($reel_id){
            //积分卷使用
            $time = time();
            $reel = D("ReelList")->where("id=$reel_id AND type=3 AND is_use=0 AND end_time>$time AND user_id=".$this->user_info['user_id'])->find();
            $info['integral'] = $info['integral']-$reel['price']>0?$info['integral']-$reel['price']:0;
        }
        if($info['integral']>0){
            if(parent::integral("-".$info['integral'],$this->user_info['user_id'],"兑换课程(".$info['title'].")")){
                $data = array();
                $data['integral'] = $info['integral'];
                $data['course_id'] = $info['id'];
                $data['user_id'] = $this->user_info['user_id'];
                $data['add_time'] = time();
                $saveId=M("CourseOrder")->data($data)->add();
                if($saveId){
                    if($reel_id){
                        //积分卷修改状态
                        D("ReelList")->where("id=$reel_id AND type=3 AND user_id=".$this->user_info['user_id'])->data(array("is_use"=>1))->save();
                    }
                    $this->success('兑换成功！！',U('Course/detail',array('id'=>$id)));
                }else{
                    $this->error("兑换失败");
                }
            }else{
                $this->error("操作失败");
            }
        }else{
            $data = array();
            $data['integral'] = $info['integral'];
            $data['course_id'] = $info['id'];
            $data['user_id'] = $this->user_info['user_id'];
            $data['add_time'] = time();
            $saveId=M("CourseOrder")->data($data)->add();
            if($saveId){
                if($reel_id){
                    //积分卷修改状态
                    D("ReelList")->where("id=$reel_id AND type=3 AND user_id=".$this->user_info['user_id'])->data(array("is_use"=>1))->save();
                }
                $this->success('兑换成功！！',U('Course/detail',array('id'=>$id)));
            }else{
                $this->error("兑换失败");
            }
        }
        
    }
    public function my_list(){
        //我的课程
        parent::check_login();
        $M_Course = D("Course");
        $filter = array('my_id'=>$this->user_info['user_id']);
        $count = $M_Course->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Course->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);

        /************************banner*****************************/
        $banner = D('Ads')->lists(3,10);
        $this->assign('banner',$banner);
        $cat_list = M("CourseCat")->where("parent_id=0 AND cat_type=1")->order(SO)->select();
        $this->assign('cat_list',$cat_list);

        
        if(IS_AJAX){
          $data = $this->fetch('Course:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
}
?>