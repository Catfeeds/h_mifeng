<?php
class InformationAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}
  public function index(){
    //蜜蜂广场
    $this->assign("nav_nid",2);
    $this->assign("list", D("Information")->InformationList(0,8,array("verify"=>1)));
    /************************banner*****************************/
    $banner = D('Ads')->lists(6,10);
    $this->assign('banner',$banner);

    $this->assign("kindergarten_list",D("Recruitment")->lists2(0,3));//园区

    // 按钮
    $btn_list = D('Ads')->lists(12,4);
    $this->assign('btn_list',$btn_list);

    $this->display();
  }
    public function lists(){
        $cat_id = $this->_get('cat_id');
        $M_Information = D("Information");
        $cat_filter = ['parent_id'=>0];
        $cat_list = $M_Information->cat_list($cat_filter);
        $filter = ['cat_id'=>$cat_id,"verify"=>1];
        $count = $M_Information->InformationCount($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Information->InformationList($page->firstRow, $page->listRows,$filter));
        $this->assign("cat_list",$cat_list);
        $this->assign("cat_id",$cat_id);
        $this->assign("listRows",$page->listRows);
        if(IS_AJAX){
          $data = $this->fetch('Information:lists_ajax');
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
        $info = D("Information")->info($id);

        $this->assign('comment_type',1);
        $this->assign('relatively_id',$id);

        $filter = ['relatively_id'=>$id,'type'=>1];
        //评论
        $comment_count = D("Comment")->comment_count($filter);
        import("ORG.Util.Page");//载入分页类
        $page = new Page($comment_count,16);
        $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
        $this->assign("listRows",$page->listRows);
        $this->assign('comment_list',$comment_list);
        $this->assign('comment_count',$comment_count);

        
        // var_dump($comment_list);exit;
        //点赞
        $praise_count = D("Praise")->praise_count($filter);
        import("ORG.Util.Page");//载入分页类
        $page2 = new Page($praise_count,60);
        $praise_list = D("Praise")->praise_list($page2->firstRow,$page2->listRows,$filter);
        $this->assign('praise_list',$praise_list);
        $this->assign('praise_count',$praise_count);


        $user_info = session('userInfo');
        if($info['verify']==0){
          $this->error("审核中！");
          // if(!$user_info||$user_info['user_id']!=$info['user_id']){
          //   $this->error("审核中！");
          // }
        }
        if($user_info){
          $filter['user_id'] = $user_info['user_id'];
          //是否点赞
          $this->assign('is_praise',D('Praise')->is_praise($filter));
          //是否关注
          $this->assign('is_attention',D('Attention')->is_attention(array('user_id'=>$user_info['user_id'],'attention_id'=>$info['user_id'])));
          //是否收藏
          $this->assign('is_collect',D('Collect')->is_collect($filter));
          $this->assign('user_info',$user_info);
        }

        $this->assign('filter',$filter);
        $this->assign('info',$info);
        $this->display();
    }
    public function comment_list(){
      //评论列表获取
      $id = $this->_get('id');
      $type = $this->_get('type');
      $filter = ['relatively_id'=>$id,'type'=>$type];

      $comment_count = D("Comment")->comment_count($filter);
      import("ORG.Util.Page");//载入分页类
      $page = new Page($comment_count,16);
      $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
      $this->assign('comment_list',$comment_list);
      $data = $this->fetch('Information:comment_list'); 
      if($page->nowPage>=$page->totalPages){
        $this->ajaxReturn($data,"完结了",500);
      }else{
        $this->ajaxReturn($data,"获取成功",200);
      }
    }
    public function comment_save(){
      //添加评论
      parent::check_login();
      if($this->user_info['black']==1){
        $this->ajaxReturn(500,"由于您最近的不良评论，已经被禁止了",500);
      }
      $M_Comment = D('Comment');
      $rules = array (
        array('relatively_id','number','请选择文章',1),
        array('type','number','请选择文章',1),
        array('parent_id','number','请选择回答人',1),
        array('content','require','请填写评论',1),
      );
      if(!$M_Comment->validate($rules)->create()){
        $this->error($M_Comment->getError());
      }
      $data = $M_Comment->create();
      $data['user_id'] = $this->user_info['user_id'];
      $data['add_time'] = time();
      $saveId=$M_Comment->data($data)->add();
      $comment_list = D("Comment")->comment_on(['id'=>$saveId]);
      $this->assign('comment_list',$comment_list); 
      $data = $this->fetch('Information:comment_list'); 
      if($saveId){
        D("Integral")->integral_zs($this->user_info['user_id'],4);//点赞赠送积分
        $this->ajaxReturn($data,"评论成功！",200);
      }else{
        $this->ajaxReturn(500,"评论失败",500);
      }
    }

    public function praise_save(){
      //原点赞
      parent::check_login();
      $M_Praise = D('Praise');
      $rules = array (
        array('relatively_id','number','请选择文章',1),
        array('type','number','请选择文章',1),
      );
      if(!$M_Praise->validate($rules)->create()){
        $this->ajaxReturn(500,$M_Praise->getError(),500);
      }
      $data = $M_Praise->create();
      $data['user_id'] = $this->user_info['user_id'];
      $data['add_time'] = time();
      if($M_Praise->where("relatively_id=".$data['relatively_id']." AND type=".$data['type']." AND user_id=".$data['user_id'])->delete()){
        $this->ajaxReturn(200,"取消成功",201);
      }
      $saveId=$M_Praise->data($data)->add();
      if($saveId){
        D("Integral")->integral_zs($this->user_info['user_id'],6);//点赞赠送积分
        $this->ajaxReturn(200,"点赞成功",200);
      }else{
        $this->ajaxReturn(500,"点赞失败",500);
      }
    }

    public function collect_save(){
      //收藏
      parent::check_login();
      $M_Collect = D('Collect');
      $rules = array (
        array('relatively_id','number','请选择文章',1),
        array('type','number','请选择文章',1),
      );
      if(!$M_Collect->validate($rules)->create()){
        $this->ajaxReturn(500,$M_Collect->getError(),500);
      }
      $data['relatively_id'] = $this->_post('relatively_id');
      $data['type'] = $this->_post('type');
      $data['user_id'] = $this->user_info['user_id'];
      $data['add_time'] = time();
      if($M_Collect->where("relatively_id=".$data['relatively_id']." AND type=".$data['type']." AND user_id=".$data['user_id'])->delete()){
        $this->ajaxReturn(200,"取消成功",201);
      }
      $saveId=$M_Collect->data($data)->add();
      if($saveId){
        $this->ajaxReturn(200,"收藏成功",200);
      }else{
        $this->ajaxReturn(500,"收藏失败",500);
      }
    }
}
?>