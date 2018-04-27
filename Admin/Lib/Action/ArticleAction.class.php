<?php

class ArticleAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Article');
	}
  public function index() {
		$articlecat=D("Articlecat")->listArticlecat();
    foreach($articlecat as $k=>&$v){
      $v['target'] = "container";
      $v['url'] = U('Article/lists',array('cat_id'=>$v['cat_id']));
    }
    $this->assign("articlecat",json_encode($articlecat,JSON_UNESCAPED_UNICODE));
    $this->display();
  }
  /**
   * 文章列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Article = D("Article");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['cat_id']     = empty($this->_request('cat_id')) ? 0 : intval($this->_request('cat_id'));
    $filter['sort_by']    = empty($this->_request('sort_by')) ? 'a.sort_order' : trim($this->_request('sort_by'));
    $filter['sort_order'] = empty($this->_request('sort_order')) ? 'asc' : trim($this->_request('sort_order'));
    
    $filter['record_count'] = $count = $M_Article->listArticleCount($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();

    $articlecat=D("Articlecat")->listArticlecat();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Article->listArticle($page->firstRow, $page->listRows,$filter));
    $this->assign("cat_select", optionsDate(getTree($articlecat),$filter['cat_id']));
    $this->display();
  }
	/**
   * 文章详情页面
   */
	public function info(){
    $M_Article = D("Article");
    $articlecat=D("Articlecat")->listArticlecat();
    $info = !empty($this->_get('id'))?$M_Article->where("article_id=".$this->_get('id'))->find():'';
    $cat_id=!empty($info)?$info['cat_id']:(!empty($this->_get('cat_id'))?$this->_get('cat_id'):0);
    $this->assign("info", $info);
    $this->assign("cat_select", optionsDate(getTree($articlecat),$cat_id));
    $this->display();
	}
  //文章保存
	public function save(){
    $M_Article = D("Article");
    $rules = array (
      array('title','require','请填写文章名称',1),
      array('cat_id','require','请选择文章分类',1),
    );
    if (!$M_Article->validate($rules)->create()){
      $this->error($M_Article->getError());
    }
    $id   = intval($this->_post('id'));
    $data = $M_Article->create();
    unset($data['id']);

    $data['content']    = stripslashes(htmlspecialchars_decode($this->_post('content')));
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
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

      $filename = 'Uploads/download/'.$date."/".$file_url['name'];
      $filename2 = iconv('utf-8','gbk',$filename);
      if(is_file($filename2)){
        $filename = $path.uniqid().".".substr(strrchr($file_url['name'],'.'),1);
        $filename2 = $filename;
      }
      move_uploaded_file($file_url['tmp_name'],$filename2);
      $data['file_url'] = $filename;
    }else{
      $data['file_url'] = $this->_post('file_url_text');
    }


    //图片上传
    if($_FILES['article_img']['error']===0){
      //获取缩略图大小
      $thumb = D("Articlecat")->where("cat_id=".$data['cat_id'])->find();
      $date = date("Y-m-d");
      $thumbPath='Uploads/article/thumb_img/'.$date.'/';
      $originalPath='Uploads/article/original_img/'.$date.'/';
      $thumbPrefix='thumb_';
      $widthSize=$thumb['width'];
      $heightSize=$thumb['height'];
      $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,false,2);
      $data['original_img']  = $upfile[0]['savepath'].$upfile[0]['savename'];
      $data['thumb_img']  = $thumbPath.$thumbPrefix.$upfile[0]['savename'];
    }
    

    if($id>0){
      $article = $M_Article->where(array('article_id'=>$id))->find();
      $saveId=$M_Article->where(array('article_id'=>$id))->save($data);
    }else{
      $saveId=$M_Article->data($data)->add();
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        //删除旧图片
        if(!empty($data['original_img'])&&$article['original_img']!=$data['original_img']){
          @unlink($article['original_img']);
        }
        if(!empty($data['thumb_img'])&&$article['thumb_img']!=$data['thumb_img']){
          @unlink($article['thumb_img']);
        }
        parent::admin_log($data['title']."-ID($id)",'edit','article');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','article');
      }
      $this->success('提交成功！！',U('Article/info',array('id'=>$id)));
    }else{
      $this->error('提交失败，或未改动！！');
    }
    exit();
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Article = D("Article");
    $article_id= intval($this->_get('article_id'));
    $row = $M_Article->where("article_id=" . $article_id)->find();

    //删除文章内容图片
    if ($M_Article->where("article_id=".$article_id)->delete()) {
      // M("Comment")->where("article_id=".$article_id)->delete();
      parent::admin_log(addslashes($row['title'])."-ID($article_id)",'remove','article');
      @unlink($row['original_img']);
      @unlink($row['thumb_img']);
      //删除评论
      M("Comment")->where("type=2 AND relatively_id=".$article_id)->delete();
      //删除收藏
      M("Collect")->where("type=2 AND relatively_id=".$article_id)->delete();
      //删除点赞
      M("Praise")->where("type=2 AND relatively_id=".$article_id)->delete();
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
    $M_Article = D("Article");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_article_ids='article_id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择文章！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $article_list = M('article')->where($in_article_ids)->select();
          M('article')->where($in_article_ids)->delete();
          M("Comment")->where($in_article_ids)->delete();
          foreach($article_list as $value){
            @unlink($value['original_img']);
            @unlink($value['thumb_img']);
            remove_content_img($value['content']);
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','article');
          break;
        case 'button_hide':
          /* 批量隐藏 */
          $M_Article->where($in_article_ids)->save(array('is_open'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','article');
          break;
        case 'button_show':
          /* 批量显示 */
          $M_Article->where($in_article_ids)->save(array('is_open'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','article');
          break;
        case 'button_recommend_yes':
          /*批量推荐*/
          M('article')->where($in_article_ids)->save(array('is_recommend'=>1));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','article');
          break;
        case 'button_recommend_no':
          /*批量取消推荐*/
          M('article')->where($in_article_ids)->save(array('is_recommend'=>0));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','article');
          break;
        case 'move_to':
          /* 批量移动分类 */
          if(empty($target_cat)){
            $this->error('请选择要转移的分类！');
          }
          $M_Article->where($in_article_ids)->save(array('cat_id'=>$target_cat));
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_edit','article');
          break;
      }
    }
    $this->success('批量操作成功！');
  }

  public function comment_list(){
    //评论列表
    $article_id = $this->_get('article_id');
    if($article_id){
      $filter = ['relatively_id'=>$article_id,"type"=>2];
    }else{
      $filter = ["type"=>2];
    }
    $comment_count = D("Comment")->comment_count($filter);
    import("ORG.Util.Page");//载入分页类
    $page = new Page($comment_count,20);
    $comment_list = D("Comment")->comment_list($page->firstRow,$page->listRows,$filter);
    $this->assign('list',$comment_list);
    $this->display();
  }
  public function comment_info(){
    $parent_id = $this->_get('parent_id');
    $relatively_id = $this->_get('relatively_id');
    $type = $this->_get('type');
    $this->assign("parent_id",$parent_id);
    $this->assign("relatively_id",$relatively_id);
    $this->assign("type",$type);
    $this->assign("user_info",D("User")->where("user_id=$parent_id")->find());
    $this->display();
  }
  public function comment_save(){
    $M_Comment = D('Comment');
    $rules = array (
      array('content','require','请填写评论！',1),
    );
    if(!$M_Comment->validate($rules)->create()){
      $this->error($M_Comment->getError());
    }
    $data = $M_Comment->create();
    $data['add_time'] = time();
    $data['user_id'] = 100;
    $saveId=$M_Comment->data($data)->add();
    $this->success('操作成功！');
  }
  public function comment_del() {
    $M_Comment = D("Comment");
    $id= intval($this->_get('id'));
    //删除文章内容图片
    if ($M_Comment->where("id=$id")->delete()) {
      parent::admin_log(addslashes("ID($id)",'remove','Comment'));
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  public function comment_batch(){
    $M_Comment = D("Comment");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择评论！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          M('Comment')->where($in_ids)->delete();
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Comment');
          break;
        case 'button_black':
          /* 批量拉黑 */
          $Comment_list = M('Comment')->where($in_ids)->select();
          $black_user = array();
          foreach($Comment_list as $v){
            $black_user[] = $v['user_id'];
          }
          M('User')->where("user_id IN(".implode(",",array_unique($black_user)).")")->save(array('black'=>1));
          parent::admin_log("ID(".implode(",",array_unique($black_user)).")",'batch_add','Black');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
}