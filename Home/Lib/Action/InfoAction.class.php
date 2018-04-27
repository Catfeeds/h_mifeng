<?php
class InfoAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}
    public function information_info(){
        //干货详情
        $id = $this->_get('id');
        if(!$id){
            $this->error();
        }
        $info = D("Information")->info($id);
        if(!$info){
            $this->error();
        }
        //浏览数增加
        D("Information")->where("id=$id")->setInc('pageviews',1);
        
        $user_info = session('userInfo');
        $this->assign("user_info",$user_info);
        $this->assign("info",$info);
        $this->display();
    }
}