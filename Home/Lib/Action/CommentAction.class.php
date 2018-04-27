<?php
class ArticleAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}
  public function comment(){
    $user_info = session('userInfo');
    $relatively_id = $this->_get('relatively_id');
    $type = $this->_get('type');
    $filter = ['relatively_id'=>$relatively_id,'type'=>$type];
    //评论
    $comment_count = D("Comment")->comment_count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($comment_count,16);
    $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
    //点赞
    $praise_count = D("Information")->praise_count($id);
    import("ORG.Util.Page");//载入分页类
    $page2 = new Page($praise_count,16);
    $praise_list = D("Information")->praise_list($page2->firstRow,$page2->listRows,$id);
    //收藏
    
  }
}
?>