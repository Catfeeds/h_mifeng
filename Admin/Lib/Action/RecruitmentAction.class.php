<?php

class RecruitmentAction extends CommonAction {
	public function _initialize() {
		parent::_initialize();	
    parent::admin_priv('Recruitment');
	}
  /**
   * 列表
   */
  public function lists(){
    // 筛选条件及排序
    $M_Recruitment = D("Recruitment");
    
    $filter = array();
    $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
    $filter['province']    = empty($this->_request('province')) ? '' : trim($this->_request('province'));
    $filter['city']    = empty($this->_request('city')) ? '' : trim($this->_request('city'));
    $filter['district']    = empty($this->_request('district')) ? '' : trim($this->_request('district'));
    
    $filter['record_count'] = $count = $M_Recruitment->count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Recruitment->lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
	/**
   * 详情页面
   */
	public function info(){
    $M_Recruitment = D("Recruitment");
    $info = !empty($this->_get('id'))?$M_Recruitment->info($this->_get('id')):'';
    $Company = D('Company')->where("user_id=100")->find();
    $this->assign("info",$info);
    $this->assign("Company",$Company);
    $region_all = D('Address')->region_all();
    $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));

    $cat_all = D('Recruitment')->cat_all();
    $this->assign("cat_all",json_encode($cat_all,JSON_UNESCAPED_UNICODE));

    $this->display();
	}
  //保存
	public function save(){
    $M_Recruitment = M("Recruitment");
    $rules = array (
      array('cat_id','require','请选择分类！'),
      array('position','require','请填写职位！'),
      array('salary','require','请填写薪资！'),
      array('education','require','请填写学历！'),
      array('working_age','require','请填写工作年龄！'),
      array('province[]','require','请选择工作地点！'),
      array('city[]','require','请选择工作地点！'),
      array('district[]','require','请选择工作地点！'),
      array('work_content','require','请填写工作内容！'),
      array('position_description','require','请填写职位描述！'),
    );
    if(!$M_Recruitment->validate($rules)->create()){
      $this->error($M_Recruitment->getError());
    }
    $M_Company = M("Company");
    $rules = array (
      array('company_name','require','请填写单位名称！'),
      array('company_info','require','请填写单位描述！'),
    );
    if(!$M_Company->validate($rules)->create()){
      $this->error($M_Company->getError());
    }
    $id   = intval($this->_post('id'));
    $province = $this->_post('province');
    $city = $this->_post('city');
    $district = $this->_post('district');

    $data = $M_Recruitment->create();
    $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
    $data['end_time']   = $this->_post('end_time')?strtotime($this->_post('end_time')):time();

    $data['province'] = implode(",",array_filter($province));
    $data['city'] = implode(",",array_filter($city));
    $data['district'] = implode(",",array_filter($district));

    if($id>0){
      $Recruitment = $M_Recruitment->where(array('id'=>$id))->find();
      $company_arr = array("company_name"=>$this->_post('company_name'),"company_info"=>$this->_post('company_info'));
      M('company')->where("user_id=".$Recruitment['user_id'])->save($company_arr);
      $saveId=$M_Recruitment->where(array('id'=>$id))->save($data);
    }else{
      $data['user_id'] = 100;
      $saveId=$M_Recruitment->data($data)->add();
      $company_arr = array("company_name"=>$this->_post('company_name'),"company_info"=>$this->_post('company_info'),"user_id"=>100);
      if(M('company')->where("user_id=100")->count()){
        M('company')->where("user_id=100")->save($company_arr);
      }else{
        M('company')->where("user_id=100")->data($company_arr)->add();
      }
      $id = $saveId;
    }
    if($saveId){
      if($id>0){
        parent::admin_log($data['title']."-ID($id)",'edit','Recruitment');
      }else{
        parent::admin_log($data['title']."-ID($id)",'add','Recruitment');
      }
      // $this->success('提交成功！！',U('Recruitment/info',array('id'=>$id)));
    }else{
      // $this->error('提交失败，或未改动！！');
    }
    $this->success('提交成功！！',U('Recruitment/info',array('id'=>$id)));
    exit();
  }
  /**
   * 删除文章
   */
  public function del() {
    $M_Recruitment = D("Recruitment");
    $id= intval($this->_get('id'));
    $row = $M_Recruitment->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_Recruitment->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Recruitment');
      M("ResumeCast")->where("recruitment_id=".$row['id'])->delete();
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 文章批量操作
   */
  public function batch(){
    $M_Recruitment = D("Recruitment");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择商品！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $Recruitment_list = M('Recruitment')->where($in_ids)->select();
          M('Recruitment')->where($in_ids)->delete();
          foreach($Recruitment_list as $value){
            M("ResumeCast")->where("recruitment_id=".$value['id'])->delete();
          }
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Recruitment');
          break;
      }
    }
    $this->success('批量操作成功！');
  }
  
  public function cast_list(){
    //简历
    $M_Recruitment = D("Recruitment");
    $id = $this->_get('id');

    $filter = array("id"=>$id);
    M("Recruitment")->where("id",$id)->save(["new_count"=>0]);
    $filter['record_count'] = $count = $M_Recruitment->cast_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Recruitment->cast_lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function cast_info(){
    $M_Recruitment = D("Recruitment");
    $info = !empty($this->_get('id'))?$M_Recruitment->cast_info($this->_get('id')):'';
    $this->assign("info", $info);
    $this->display();
  }

  public function cast_list2(){
    //岗位邀请
    $M_Recruitment = D("Recruitment");
    $id = $this->_get('id');
    M("Resume")->where("id",$id)->save(["new_count"=>0]);
    $filter = array("resume_id"=>$id);
    $filter['record_count'] = $count = $M_Recruitment->cast_count($filter);
    import("ORG.Util.Page");       //载入分页类
    $page = new Page($count, 20);
    $showPage = $page->show();
    $this->assign("filter", $filter);
    $this->assign("page", $showPage);
    $this->assign("list", $M_Recruitment->cast_lists($page->firstRow, $page->listRows,$filter));
    $this->display();
  }
  public function cast_info2(){
    //岗位详情
    $M_Recruitment = D("Recruitment");
    $info = !empty($this->_get('id'))?$M_Recruitment->info($this->_get('id')):'';
    $this->assign("info", $info);
    $this->display();
  }
  /**
   * 删除文章
   */
  public function cast_del() {
    $M_ResumeCast = M("ResumeCast");
    $id= intval($this->_get('id'));
    $row = $M_ResumeCast->where("id=" . $id)->find();
    //删除文章内容图片
    if ($M_ResumeCast->where("id=".$id)->delete()) {
      parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','ResumeCast');
      $this->success("成功删除");
    } else {
      $this->error("删除失败，可能是不存在该ID的记录");
    } 
  }
  /**
   * 文章批量操作
   */
  public function cast_batch(){
    $M_ResumeCast = D("ResumeCast");
    $type = $this->_post('type');
    $checkboxes = $this->_post('checkboxes');
    $target_cat = $this->_post('target_cat');
    if(isset($type)){
      $in_ids='id '.db_create_in(join(',',$checkboxes));
      if (!isset($checkboxes) || !is_array($checkboxes)){
        $this->error('请选择商品！');exit;
      }
      switch ($type) {
        case 'button_remove':
          /* 批量删除 */
          $ResumeCast_list = M('ResumeCast')->where($in_ids)->select();
          M('ResumeCast')->where($in_ids)->delete();
          parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','ResumeCast');
          break;
      }
    }
    $this->success('批量操作成功！');
  }


    /**
     * 列表
     */
    public function lists2(){
      // 筛选条件及排序
      $M_Recruitment = D("Recruitment");
      
      $filter = array();
      $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
      $filter['province']    = empty($this->_request('province')) ? '' : trim($this->_request('province'));
      $filter['city']    = empty($this->_request('city')) ? '' : trim($this->_request('city'));
      $filter['district']    = empty($this->_request('district')) ? '' : trim($this->_request('district'));
      
      $filter['record_count'] = $count = $M_Recruitment->count2($filter);
      import("ORG.Util.Page");       //载入分页类
      $page = new Page($count, 20);
      $showPage = $page->show();
      
      $this->assign("filter", $filter);
      $this->assign("page", $showPage);
      $this->assign("list", $M_Recruitment->lists2($page->firstRow, $page->listRows,$filter));
      $this->display();
    }
    /**
     * 详情页面
     */
    public function info2(){
      $M_Recruitment = D("Recruitment");
      $info = !empty($this->_get('id'))?$M_Recruitment->info2($this->_get('id')):'';
      $this->assign("info", $info);
      $region_all = D('Address')->region_all();
      $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
      $this->display();
    }
    //保存
    public function save2(){
      $M_Kindergarten = M("Kindergarten");
      $M_Recruitment = M("Kindergarten");
      $rules = array (
        array('title','require','请填写园区名称！'),
        array('address','require','请填园区地址！'),
        array('province','require','请选择园区地点！'),
        array('city','require','请选择园区地点！'),
        array('district','require','请选择园区地点！'),
        array('text','require','请填写园区描述！'),
      );
      if(!$M_Recruitment->validate($rules)->create()){
        $this->error($M_Recruitment->getError());
      }
      $id   = intval($this->_post('id'));
      $data = $M_Kindergarten->create();
      $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
      $data['end_time']   = $this->_post('end_time')?strtotime($this->_post('end_time')):time();
      if($id>0){
        $Kindergarten = $M_Kindergarten->where(array('id'=>$id))->find();
        $saveId=$M_Kindergarten->where(array('id'=>$id))->save($data);
      }else{
        $data['user_id'] = 100;
        $saveId=$M_Kindergarten->data($data)->add();
        $id = $saveId;
      }
      if($saveId){
        if($id>0){
          parent::admin_log($data['title']."-ID($id)",'edit','Kindergarten');
        }else{
          parent::admin_log($data['title']."-ID($id)",'add','Kindergarten');
        }
        $this->success('提交成功！！',U('Recruitment/info2',array('id'=>$id)));
      }else{
        $this->error('提交失败，或未改动！！');
      }
      exit();
    }
    /**
     * 删除文章
     */
    public function del2() {
      $M_Kindergarten = M("Kindergarten");
      $id= intval($this->_get('id'));
      $row = $M_Kindergarten->where("id=" . $id)->find();
      //删除文章内容图片
      if ($M_Kindergarten->where("id=".$id)->delete()) {
        parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','Kindergarten');
        M("KindergartenSignup")->where("kindergarten_id=".$row['id'])->delete();
        $this->success("成功删除");
      } else {
        $this->error("删除失败，可能是不存在该ID的记录");
      } 
    }
    /**
     * 文章批量操作
     */
    public function batch2(){
      $M_Kindergarten = D("Kindergarten");
      $type = $this->_post('type');
      $checkboxes = $this->_post('checkboxes');
      $target_cat = $this->_post('target_cat');
      if(isset($type)){
        $in_ids='id '.db_create_in(join(',',$checkboxes));
        if (!isset($checkboxes) || !is_array($checkboxes)){
          $this->error('请选择商品！');exit;
        }
        switch ($type) {
          case 'button_remove':
            /* 批量删除 */
            $Kindergarten_list = M('Kindergarten')->where($in_ids)->select();
            M('Kindergarten')->where($in_ids)->delete();
            foreach($Kindergarten_list as $value){
              M("KindergartenSignup")->where("kindergarten_id=".$value['id'])->delete();
            }
            parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Kindergarten');
            break;
        }
      }
      $this->success('批量操作成功！');
    }
    public function signup_list(){
      //意向
      $M_Recruitment = D("Recruitment");
      $id = $this->_get('id');
      
      if($id){
        $filter = array("id"=>$id);
        $this->assign("kindergarten",$M_Recruitment->info2($id));
        M("Kindergarten")->where("id",$id)->save(["new_count"=>0]);
      }
      $kindergarten_xuqiu_id = $this->_get('kindergarten_xuqiu_id');
      if($kindergarten_xuqiu_id){
        $filter = array("kindergarten_xuqiu_id"=>$kindergarten_xuqiu_id);
        $this->assign("kindergarten_xuqiu",$M_Recruitment->info3($kindergarten_xuqiu_id));
        M("KindergartenXuqiu")->where("id",$kindergarten_xuqiu_id)->save(["new_count"=>0]);
      }
      $filter['record_count'] = $count = $M_Recruitment->signup_count($filter);
      import("ORG.Util.Page");       //载入分页类
      $page = new Page($count, 20);
      $showPage = $page->show();
      
      $this->assign("filter", $filter);
      $this->assign("page", $showPage);
      $this->assign("list", $M_Recruitment->signup_lists($page->firstRow, $page->listRows,$filter));
      
      $this->display();
    }

    /**
     * 删除文章
     */
    public function signup_del() {
      $M_KindergartenSignup = M("KindergartenSignup");
      $id= intval($this->_get('id'));
      $row = $M_KindergartenSignup->where("id=" . $id)->find();
      //删除文章内容图片
      if ($M_KindergartenSignup->where("id=".$id)->delete()) {
        parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','KindergartenSignup');
        $this->success("成功删除");
      } else {
        $this->error("删除失败，可能是不存在该ID的记录");
      } 
    }
    /**
     * 文章批量操作
     */
    public function signup_batch(){
      $M_KindergartenSignup = D("KindergartenSignup");
      $type = $this->_post('type');
      $checkboxes = $this->_post('checkboxes');
      $target_cat = $this->_post('target_cat');
      if(isset($type)){
        $in_ids='id '.db_create_in(join(',',$checkboxes));
        if (!isset($checkboxes) || !is_array($checkboxes)){
          $this->error('请选择商品！');exit;
        }
        switch ($type) {
          case 'button_remove':
            /* 批量删除 */
            $KindergartenSignup_list = M('KindergartenSignup')->where($in_ids)->select();
            M('KindergartenSignup')->where($in_ids)->delete();
            parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','KindergartenSignup');
            break;
        }
      }
      $this->success('批量操作成功！');
    }

    public function resume_list(){
      //简历
      $M_Recruitment = D("Recruitment");
      $id = $this->_get('id');
      $keyword = $this->_get('keyword');
      $filter = array("id"=>$id,"keyword"=>$keyword);

      $filter['province']    = empty($this->_request('province')) ? '' : trim($this->_request('province'));
      $filter['city']    = empty($this->_request('city')) ? '' : trim($this->_request('city'));

      $filter['record_count'] = $count = $M_Recruitment->resume_count($filter);
      import("ORG.Util.Page");       //载入分页类
      $page = new Page($count, 20);
      $showPage = $page->show();
      
      $this->assign("filter", $filter);
      $this->assign("page", $showPage);
      $this->assign("list", $M_Recruitment->resume_lists($page->firstRow, $page->listRows,$filter));
      $this->display();
    }
    public function resume_info(){
      $M_Recruitment = D("Recruitment");
      $info = !empty($this->_get('id'))?$M_Recruitment->resume_info($this->_get('id')):'';
      $this->assign("info", $info);

      $region_all = D('Address')->region_all(['region_type'=>2]);
      $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));

      $this->display();
    }
    public function resume_save(){
      $M_Resume = M("Resume");
      $id   = intval($this->_post('id'));
      $data = $M_Resume->create();
      $rules = array (
        array('name','require','请填写姓名！'),
        array('age','number','请填写年龄！'),
        // array('height','number','请填写身高！'),
        array('marriage','require','请填写婚姻状况！'),
        array('phone','number','请填写手机！'),
        array('email','email','请填写邮箱！'),
        array('province[]','require','请选择工作地点！'),
        array('city[]','require','请选择工作地点！'),
        // // array('district[]','require','请选择工作地点！'),
        array('education','require','请填写学历！'),
        array('profession','require','请填写专业！'),
        array('working_age','require','请填写工作年龄！'),
        array('experience','require','请填写工作经验！'),
        array('education_information','require','请填写教育信息！'),
        array('talent','require','请填写技能专长！'),
        array('self_evaluation','require','请填写自我评价！'),
        // array('protocol','require','请同意协议内容！'),
        // array('position','require','请填写期望职位！'),
      );
      if(!$M_Resume->validate($rules)->create()){
        $this->error($M_Resume->getError());
      }
      $province = $this->_post('province');
      $city = $this->_post('city');
      // $district = $this->_post('district');
      $data['province'] = implode(",",array_filter($province));
      $data['city'] = implode(",",array_filter($city));
      // $data['district'] = implode(",",array_filter($district));
      $data['is_open'] = $this->_post('is_open')?$this->_post('is_open'):0;
      
      if($id>0){
        $saveId=$M_Resume->where(array('id'=>$id))->save($data);
      }else{
        $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
        $data['user_id'] = 0;
        $saveId=$M_Resume->data($data)->add();
        $id = $saveId;
      }
      if($saveId){
        if($id>0){
          parent::admin_log($data['name']."-ID($id)",'edit','Resume');
        }else{
          parent::admin_log($data['name']."-ID($id)",'add','Resume');
        }
        // $this->success('提交成功！！',U('Recruitment/resume_info',array('id'=>$id)));
      }
      $file_id = $this->_post("file_id");
      $is_img = false;
      foreach($_FILES['file']['error'] as $v){
        if($v===0){
          $is_img = true;
        }
      }
      if($is_img){
        $date = date("Y-m-d");
        $thumbPath='Uploads/resume/thumb_img/'.$date.'/';
        $originalPath='Uploads/resume/original_img/'.$date.'/';
        $thumbPrefix='thumb_,original_';
        $widthSize='750';
        $heightSize='0';
        $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg'),$originalPath,'uniqid',true,$thumbPath,$widthSize,$heightSize,$thumbPrefix,true,2);
      }
      if(is_array($file_id)){
          $ResumeImg = M("ResumeImg")->where("id NOT IN (".implode(",",$file_id).") AND resume_id=$id")->select();
          foreach($ResumeImg as $v){
              @unlink($v['original_img']);
              @unlink($v['thumb_img']);
          }
          $ResumeImg = M("ResumeImg")->where("id NOT IN (".implode(",",$file_id).") AND resume_id=$id")->delete();
      }
      foreach($upfile as $k=>$v){
          $con = [
              'thumb_img'=>$thumbPath."thumb_".$upfile[$k]['savename'],
              'original_img'=>$thumbPath."original_".$upfile[$k]['savename'],
              'resume_id'=>$id,
              'user_id'=>0,
          ];
          // if($file_id[$k]>0){
          //   M("ResumeImg")->where(array('id'=>$file_id[$k]))->save($con);
          // }else{
            M("ResumeImg")->data($con)->add();
          // }
      }
      // else{
      //   $this->error('提交失败，或未改动！！');
      // }
      $this->success('提交成功！！',U('Recruitment/resume_info',array('id'=>$id)));
      exit;
    }
    public function resume_del(){
      //简历删除
      $M_Resume = M("Resume");
      $id= intval($this->_get('id'));
      $row = $M_Resume->where("id=" . $id)->find();
      //删除文章内容图片
      if($M_Resume->where("id=".$id)->delete()){
        parent::admin_log(addslashes($row['name'])."-ID($id)",'remove','Resume');
        M("ResumeCast")->where("resume_id=".$row['id'])->delete();
        $ResumeImg=M('ResumeImg')->where("resume_id=".$row['id'])->select();
        foreach($ResumeImg as $v){
          @unlink($v['original_img']);
          @unlink($v['thumb_img']);
        }
        $ResumeImg=M('ResumeImg')->where("resume_id=".$row['id'])->delete();
        $this->success("成功删除");
      }else{
        $this->error("删除失败，可能是不存在该ID的记录");
      } 
    }
    public function resume_batch(){
      //简历批量操作
      $M_Resume = D("Resume");
      $type = $this->_post('type');
      $checkboxes = $this->_post('checkboxes');
      if(isset($type)){
        $in_ids='id '.db_create_in(join(',',$checkboxes));
        if (!isset($checkboxes) || !is_array($checkboxes)){
          $this->error('请选择简历！');exit;
        }
        switch ($type) {
          case 'button_remove':
            /* 批量删除 */
            $Resume = M('Resume')->where($in_ids)->select();
            M('Resume')->where($in_ids)->delete();
            foreach($Resume as $value){
              M("ResumeCast")->where("resume_id=".$value['id'])->delete();
              $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->select();
              foreach($ResumeImg as $v){
                @unlink($v['original_img']);
                @unlink($v['thumb_img']);
              }
              $ResumeImg=M('ResumeImg')->where("resume_id=".$value['id'])->delete();
            }
            parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','Resume');
            break;
        }
      }
      $this->success('批量操作成功！');
    }



    /**
     * 列表
     */
    public function lists3(){
      // 筛选条件及排序
      $M_Recruitment = D("Recruitment");
      
      $filter = array();
      $filter['keyword']    = empty($this->_request('keyword')) ? '' : trim($this->_request('keyword'));
      $filter['province']    = empty($this->_request('province')) ? '' : trim($this->_request('province'));
      $filter['city']    = empty($this->_request('city')) ? '' : trim($this->_request('city'));
      $filter['district']    = empty($this->_request('district')) ? '' : trim($this->_request('district'));
      $filter['record_count'] = $count = $M_Recruitment->count3($filter);
      import("ORG.Util.Page");       //载入分页类
      $page = new Page($count, 20);
      $showPage = $page->show();
      
      $this->assign("filter", $filter);
      $this->assign("page", $showPage);
      $this->assign("list", $M_Recruitment->lists3($page->firstRow, $page->listRows,$filter));
      $this->display();
    }
    /**
     * 详情页面
     */
    public function info3(){
      $M_Recruitment = D("Recruitment");
      $info = !empty($this->_get('id'))?$M_Recruitment->info3($this->_get('id')):'';
      $this->assign("info", $info);
      $region_all = D('Address')->region_all();
      $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
      $this->display();
    }
    //保存
    public function save3(){
      $M_Kindergarten = M("KindergartenXuqiu");
      $id   = intval($this->_post('id'));
      $rules = array (
        array('title','require','请填写园区名称！'),
        array('address','require','请填园区地址！'),
        array('province','require','请选择园区地点！'),
        array('city','require','请选择园区地点！'),
        array('district','require','请选择园区地点！'),
        array('text','require','请填写园区描述！'),
      );
      if(!$M_Kindergarten->validate($rules)->create()){
        $this->error($M_Kindergarten->getError());
      }
      $data = $M_Kindergarten->create();
      $data['add_time']   = $this->_post('add_time')?strtotime($this->_post('add_time')):time();
      $data['end_time']   = $this->_post('end_time')?strtotime($this->_post('end_time')):time();
      if($id>0){
        $Kindergarten = $M_Kindergarten->where(array('id'=>$id))->find();
        $saveId=$M_Kindergarten->where(array('id'=>$id))->save($data);
      }else{
        $data['user_id'] = 100;
        $saveId=$M_Kindergarten->data($data)->add();
        $id = $saveId;
      }
      if($saveId){
        if($id>0){
          parent::admin_log($data['title']."-ID($id)",'edit','KindergartenXuqiu');
        }else{
          parent::admin_log($data['title']."-ID($id)",'add','KindergartenXuqiu');
        }
        $this->success('提交成功！！',U('Recruitment/info3',array('id'=>$id)));
      }else{
        $this->error('提交失败，或未改动！！');
      }
      exit();
    }
    /**
     * 删除文章
     */
    public function del3() {
      $M_Kindergarten = M("KindergartenXuqiu");
      $id= intval($this->_get('id'));
      $row = $M_Kindergarten->where("id=" . $id)->find();
      //删除文章内容图片
      if ($M_Kindergarten->where("id=".$id)->delete()) {
        parent::admin_log(addslashes($row['title'])."-ID($id)",'remove','KindergartenXuqiu');
        M("KindergartenSignup")->where("kindergarten_xuqiu_id=".$row['id'])->delete();
        $this->success("成功删除");
      } else {
        $this->error("删除失败，可能是不存在该ID的记录");
      } 
    }
    /**
     * 文章批量操作
     */
    public function batch3(){
      $M_Kindergarten = D("KindergartenXuqiu");
      $type = $this->_post('type');
      $checkboxes = $this->_post('checkboxes');
      $target_cat = $this->_post('target_cat');
      if(isset($type)){
        $in_ids='id '.db_create_in(join(',',$checkboxes));
        if (!isset($checkboxes) || !is_array($checkboxes)){
          $this->error('请选择商品！');exit;
        }
        switch ($type) {
          case 'button_remove':
            /* 批量删除 */
            $Kindergarten_list = M('KindergartenXuqiu')->where($in_ids)->select();
            M('KindergartenXuqiu')->where($in_ids)->delete();
            foreach($Kindergarten_list as $value){
              M("KindergartenSignup")->where("kindergarten_xuqiu_id=".$value['id'])->delete();
            }
            parent::admin_log("ID(".join(',',$checkboxes).")",'batch_remove','KindergartenXuqiu');
            break;
        }
      }
      $this->success('批量操作成功！');
    }



    /**
    * 分类列表
    */
    public function cat_list() {
      $M_Recruitment = D("Recruitment");
      $cat_list=$M_Recruitment->cat_list();
      foreach($cat_list as $k=>&$v){
        $v['target'] = "container";
        $v['url'] = U('Recruitment/cat_info',array('cat_id'=>$v['cat_id']));
      }
      $this->assign("cat_list",json_encode($cat_list,JSON_UNESCAPED_UNICODE));
      $this->display();
    }
    /**
     * 分类详情页面
     */
    public function cat_info(){
      $M_Recruitment = D("Recruitment");
      $cat_list=$M_Recruitment->cat_list();
      $info = !empty($this->_get('cat_id'))?M("RecruitmentCat")->where("cat_id=".$this->_get('cat_id'))->find():'';
      $selected=!empty($info)?$info['parent_id']:(!empty($this->_get('selected'))?$this->_get('selected'):0);
      $this->assign("info", $info);
      $this->assign("cat_select", optionsDate(getTree($cat_list),$selected,$info['cat_id']));
      $this->display();
    }
    /**
     * 更新分类
     */
    public function cat_save(){
      $M_RecruitmentCat = M("RecruitmentCat");
      $rules = array (
        array('cat_name','require','请填写分类名称！',1),
      );
      if (!$M_RecruitmentCat->validate($rules)->create()){
        $this->error($M_RecruitmentCat->getError());
      }
      $cat_id = intval($this->_post('cat_id'));
      $data = $M_RecruitmentCat->create();
      unset($data['cat_id']);
      if($cat_id>0){
        $saveId=$M_RecruitmentCat->where(array('cat_id'=>$cat_id))->save($data);
      }else{
        $saveId=$M_RecruitmentCat->data($data)->add();
        $cat_id = $saveId;
      }
      if($saveId){
        if($cat_id>0){
          parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'edit','RecruitmentCat');
        }else{
          parent::admin_log($this->_post('cat_name')."-ID($cat_id)",'add','RecruitmentCat');
        }
        $this->success('提交成功！！',U('Recruitment/cat_info',array('cat_id'=>$cat_id)));
      }else{
        $this->error('提交失败，或未改动！！');
      }
      exit();
    }
    /**
     * 删除分类
     */
    public function cat_del() {
      /* 权限判断 */
      $M_RecruitmentCat = M("RecruitmentCat");
      $M_Recruitment = D("Recruitment");
      $cat_id       = intval($this->_get('cat_id'));
      $cat = $M_RecruitmentCat->where("cat_id=".$cat_id)->find();
      $cat_type = $cat['cat_type'];
      if ($cat_type == 2 ){
        /* 系统保留分类，不能删除 */
        $this->error('系统保留分类，不能删除');
      }
      $countChildcat=$M_RecruitmentCat->where(array('parent_id'=>$cat_id))->count();
      if ($countChildcat > 0){
        /* 还有子分类，不能删除 */
        $this->error('还有子分类，不能删除');
      }
      /* 非空的分类不允许删除 */
      $count=M('Recruitment')->where(array('cat_id'=>$cat_id))->count();
      if ($count > 0)
      {
        $this->error('非空的分类不允许删除');
      }
      else
      {
        if ($M_RecruitmentCat->where("cat_id=" . $cat_id)->delete()) {
          parent::admin_log($cat['cat_name']."-ID($cat_id)",'remove','RecruitmentCat');
          $this->success("成功删除");
        } else {
          $this->error("删除失败，可能是不存在该ID的记录");
        }
      }   
    }
}