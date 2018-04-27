<?php
class ArticleAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}
    public function lists(){
        $cat_id = $this->_get('cat_id');
        $M_Article = D("Article");
        $filter = array();
        /************************banner*****************************/
        $banner = D('Ads')->lists(2,10);
        $this->assign('banner',$banner);

        $filter['cat_id'] = $cat_id;
        // $filter['cat_id'] = 75;
        $count = $M_Article->listArticleCount($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $car_info = M("Articlecat")->where("cat_id=$cat_id")->find();
        $parent_id = empty($car_info['parent_id'])?$car_info['cat_id']:$car_info['parent_id'];
        $cat_list = M("Articlecat")->where("parent_id=$parent_id AND cat_type=1")->order(SO)->select();
        $this->assign("list", $M_Article->listArticle($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("parent_id",$parent_id);
        $this->assign("cat_id",$cat_id);
        $this->assign("cat_list",$cat_list);
        if(IS_AJAX){
          $data = $this->fetch('Article:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function lists2(){
        $cat_id = $this->_get('cat_id');
        $M_Article = D("Article");
        $filter = array();
        $filter['cat_id'] = $cat_id;
        $count = $M_Article->listArticleCount($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,10);
        $this->assign("list", $M_Article->listArticle($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("cat_id",$cat_id);
        if(IS_AJAX){
          $data = $this->fetch('Article:lists2_ajax');
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

        $filter = ['relatively_id'=>$id,'type'=>2];
        $this->assign('comment_type',2);
        $this->assign('relatively_id',$id);

        M("Article")->where("article_id=$id")->setInc("view_count","1");
        $info = M("Article")->where("article_id=$id")->find();
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
          //是否收藏
          $this->assign('is_collect',D('Collect')->is_collect($filter));
          $this->assign('user_info',$user_info);
        }

        $this->display();
    }
}
?>