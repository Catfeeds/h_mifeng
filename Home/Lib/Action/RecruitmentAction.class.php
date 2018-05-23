<?php
class RecruitmentAction extends CommonAction {
	public function __construct() {
		parent::__construct();
        parent::check_login();
        // $ip = get_client_ip();
        // $api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip"; 
        // $json = @file_get_contents($api);//调用新浪IP地址库 
        // if($json){
        //     $arr = json_decode($json,true);//解析json
        //     $city = $arr['city']; //取得城市
        //     if(!empty($city)){
        //         $city_id = M("region")->where("region_name LIKE '%".$city."%' AND region_type=2")->find();
        //         if($city_id){
        //             cookie('city',$city_id['region_id'],3600*24*30);
        //             $this->assign("city_title",$city_id['region_name']);
        //         }
        //     }
        // }
        $region_city = D('Address')->region_all(array("region_type"=>2));
        $this->assign("region_city",json_encode($region_city['0']['child'],JSON_UNESCAPED_UNICODE));
        if($this->_get("city")){
            $city = $this->_get("city");
            cookie('city',$city,3600*24*30);
            // cookie('aaa',123,3600*24*30);
        }
        
        if(cookie('city')){
            $this->assign("city_title",D('Address')->region_name(cookie('city')));
        }else{
            //定位城市
            $ip = get_client_ip();
            $api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip"; 
            $json = @file_get_contents($api);//调用新浪IP地址库 
            if($json){
                $arr = json_decode($json,true);//解析json
                $city = $arr['city']; //取得城市
                if(!empty($city)){
                    $city_id = M("region")->where("region_name LIKE '%".$city."%' AND region_type=2")->find();
                    if($city_id){
                        cookie('city',$city_id['region_id'],3600*24*30);
                        $this->assign("city_title",$city_id['region_name']);
                    }
                }
            }
        }
        $time = time();
        M('Recruitment')->where("status=1 AND end_time<".time())->save(array('status'=>0));
        M('Kindergarten')->where("status=1 AND end_time<".time())->save(array('status'=>0));
        // var_dump(M('Recruitment')->where("status=1 AND end_time<$time")->select());
        // $city = $this->_get('city');
        // if($city){
        //     cookie('city',$city,3600*24*30);
        // }
	}
    public function index(){

        $banner = D('Ads')->lists(16,10);
        $this->assign('banner',$banner);

        //招聘中心
        $this->assign("recruitment_list",D("Recruitment")->lists(0,3));
        $this->assign("resume_list",D("Recruitment")->resume_lists(0,3));
        $this->assign("kindergarten_list",D("Recruitment")->lists2(0,3));
        // import('ORG.Net.IpLocation');// 导入IpLocation类
        // $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        // $area = $Ip->getlocation(); // 获取某个IP地址所在的位置
        // var_dump($area);
        //蜜蜂蜂会
        $this->assign('meeting_list',D('Meeting')->lists(0,3));
        //课堂
        $this->assign("coures_list", D("Course")->lists(0,3));

        // 按钮
        $btn_list = D('Ads')->lists(11,4);
        $this->assign('btn_list',$btn_list);

        $this->display();
    }
    public function resume_lists(){
        //人才列表
        $M_Recruitment = D("Recruitment");
        $keyword = $this->_get("keyword");
        $filter = array("keyword"=>$keyword);
        $count = $M_Recruitment->resume_count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Recruitment->resume_lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("keyword",$keyword);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:resume_lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function resume_show(){
        //简历展示
        $id = $this->_get("id");
        $this->assign("info",D("Recruitment")->resume_info(array("id"=>$id)));

        //我的单位
        $this->assign("recruitment_list",D("Recruitment")->lists(0,999,["user_id"=>$this->user_info['user_id']]));

        $this->display();
    }
    public function lists(){
        //岗位列表
        $M_Recruitment = D("Recruitment");
        $keyword = $this->_get("keyword");
        $cat_id = $this->_get("cat_id");
        $cat_name = $this->_get("cat_name");
        $district = $this->_get("district");
        $district_name = $this->_get("district_name");

        $filter = array("keyword"=>$keyword,"cat_id"=>$cat_id,"district"=>$district);
        $count = $M_Recruitment->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Recruitment->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("keyword",$keyword);
        $this->assign("cat_id",$cat_id);
        $this->assign("cat_name",$cat_name);
        $this->assign("district",$district);
        $this->assign("district_name",$district_name);

        $cat_all = D('Recruitment')->cat_all();
        array_unshift($cat_all,['cat_id'=>0,"cat_name"=>"全部"]);
        $this->assign("cat_all",json_encode($cat_all,JSON_UNESCAPED_UNICODE));

        $city = cookie('city');
        $this->assign("city",$city);
        if($city){
            $district_all = D('Address')->region_all(['parent_id'=>$city]);
            array_unshift($district_all,['region_id'=>0,"region_name"=>"全部"]);
            $this->assign("district_all",json_encode($district_all,JSON_UNESCAPED_UNICODE));
        }
        

        

        if(IS_AJAX){
          $data = $this->fetch('Recruitment:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function info(){
        //岗位详情
        $id = $this->_get('id');
        M("Recruitment")->where("id=$id")->setInc("view_count","1");
        $info = D("Recruitment")->info($id);
        $this->assign("info",$info);
        $this->display();
    }
    public function my_recruitment(){
        //我的岗位列表
        $M_Recruitment = D("Recruitment");
        $filter = array("user_id"=>$this->user_info['user_id']);
        $count = $M_Recruitment->count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,999);
        $this->assign("list",$M_Recruitment->lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:lists_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function company(){
        //单位信息
        $info = D("Recruitment")->company_info($this->user_info['user_id']);
        $this->assign("info",$info);
        $this->display();
    }
    public function company_save(){
        //单位信息保存
        $M_Company = M("Company");
        $rules = array (
          array('company_name','require','请填写单位名称！'),
          array('company_info','require','请填写单位描述！'),
        );
        if(!$M_Company->validate($rules)->create()){
          $this->error($M_Company->getError());
        }
        $data = $M_Company->create();
        $data['user_id'] = $this->user_info['user_id'];
          $is_company = D("Recruitment")->is_company($this->user_info['user_id']);
          if(empty($is_company)){
              $M_Company->data($data)->add();
          }else{
              $M_Company->where("user_id=".$this->user_info['user_id'])->save($data);
          }
          $this->success('提交成功',U('Recruitment/my_recruitment'));
    }
    public function my_info(){
        //我的岗位详情
        $is_company = D("Recruitment")->is_company($this->user_info['user_id']);
        if(empty($is_company)){
            $this->error("请先填写单位信息",U('Recruitment/company'));
        }
        $id = $this->_get('id');

        $info = D("Recruitment")->info($id,$this->user_info['user_id']);
        $this->assign("info",$info);
        
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));

        $cat_all = D('Recruitment')->cat_all();
        $this->assign("cat_all",json_encode($cat_all,JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    public function my_del(){
        //岗位删除
        $id = $this->_get("id");
        D("Recruitment")->where("id=$id AND user_id=".$this->user_info['user_id'])->delete();
        D("ResumeCast")->where("recruitment_id=$id AND r_user_id=".$this->user_info['user_id'])->delete();
        $this->success('删除成功',U('Recruitment/my_recruitment'));
    }
    public function my_info_save(){
      //简历保存
      $M_Recruitment = M("Recruitment");
      $rules = array (
        array('cat_id','require','请选择分类！'),
        array('name','require','请填写联系人！'),
        array('phone','require','请填写联系电话！'),
        array('position','require','请填写职位！'),
        array('salary','require','请填写薪资！'),
        array('education','require','请填写学历！'),
        array('working_age','require','请填写工作年龄！'),
        array('province[]','require','请选择工作地点！'),
        array('city[]','require','请选择工作地点！'),
        array('district[]','require','请选择工作地点！'),
        array('work_content','require','请填写工作内容！'),
        array('position_description','require','请填写职位描述！'),
        array('protocol','require','请同意协议内容！'),
        array('end_time','require','请填写有效时间！'),
      );
      if(!$M_Recruitment->validate($rules)->create()){
        $this->error($M_Recruitment->getError());
      }
      
      $id = $this->_post('id');
      $province = $this->_post('province');
      $city = $this->_post('city');
      $district = $this->_post('district');

      $data = $M_Recruitment->create();
      $data['province'] = implode(",",array_filter($province));
      $data['city'] = implode(",",array_filter($city));
      $data['district'] = implode(",",array_filter($district));
      if(!is_phone($data['phone'])&&!is_tel($data['phone'])){
        $this->error("请填写正确的联系电话");
      }
      // if(!empty($id)){
      //   $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
      //   if($info['is_open']==1){
      //       $data = array();
      //   }
      // }
      $data['user_id'] = $this->user_info['user_id'];
      $data['end_time'] = strtotime($this->_post('end_time'));
      $data['status'] = $this->_post('status')!==null?$this->_post('status'):0;
        if(empty($id)){
            $data['add_time'] = time();
            $M_Recruitment->data($data)->add();
        }else{
            $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
            if(!$info){
                $this->error("找不到此记录");
            }
            $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->save($data);
        }
        $this->success('提交成功',U('Recruitment/my_recruitment'));
    }
    public function my_cast(){
        //收到的简历
        $M_Recruitment = D("Recruitment");
        $id = $this->_get('id');
        $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
        if(!$info){
            $this->error("找不到此记录");
        }
        $filter = array("id"=>$id,"user_id"=>$this->user_info['user_id']);
        $count = $M_Recruitment->my_cast_count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list",$M_Recruitment->my_cast_lists($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("info",$info);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:cast_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function my_cast_info(){
        //查看简历
        $id = $this->_get("id");
        $user_id = $this->_get("user_id");
        $info = D("Recruitment")->my_cast_info($id,$this->user_info['user_id']);
        if(empty($info)){
            $this->error("没有此记录");
        }
        $this->assign("info",$info);
        $this->display();
    }

    public function cast_resume(){
        //简历投放
        $rules = array (
          array('recruitment_id','number','请选择岗位',1),
        );
        if(!M("ResumeCast")->validate($rules)->create()){
          $this->error(M("ResumeCast")->getError());
        }
        $id = $this->_post('recruitment_id');
        $is_resume = D("Recruitment")->is_resume($this->user_info['user_id']);
        if(empty($is_resume)){
            $this->error("请先完善你的简历信息",U('Recruitment/resume_info'));
        }
        $info = D("Recruitment")->info($id);
        if(!$info){
            $this->error("没有此岗位信息");
        }
        $is_cast = M("ResumeCast")->where("recruitment_id=$id AND resume_id=".$is_resume['id']." AND type=1 AND user_id=".$this->user_info['user_id'])->find();
        if(!$is_cast){
            $data = array("user_id"=>$this->user_info['user_id'],"recruitment_id"=>$id,"r_user_id"=>$info['user_id'],"add_time"=>time(),"resume_id"=>$is_resume['id'],"type"=>1);
            M("ResumeCast")->data($data)->add();
            M("Recruitment")->where(['id'=>$id])->save(['new_count'=>1]);
        }else{
            // M("ResumeCast")->where("recruitment_id=$id AND user_id=".$this->user_info['user_id'])->save(array("add_time"=>time()));
        }
        $this->success("发送成功","javascript:history.go(-2);");
    }

    public function cast_resume2(){
        //简历投放
        $rules = array (
          array('recruitment_id','number','请选择岗位',1),
          array('resume_id','number','请选择简历',1),
        );
        if(!M("ResumeCast")->validate($rules)->create()){
          $this->error(M("ResumeCast")->getError());
        }
        $id = $this->_post('recruitment_id');
        $resume_id = $this->_post('resume_id');

        $info = D("Recruitment")->resume_info(['id'=>$resume_id]);
        if(!$info){
            $this->error("没有此简历信息");
        }
        $is_cast = M("ResumeCast")->where("recruitment_id=$id AND resume_id=".$resume_id." AND type=2 AND user_id=".$this->user_info['user_id'])->find();
        if(!$is_cast){
            $data = array("user_id"=>$info['user_id'],"recruitment_id"=>$id,"r_user_id"=>$this->user_info['user_id'],"add_time"=>time(),"resume_id"=>$resume_id,"type"=>2);
            M("ResumeCast")->data($data)->add();
            M("Resume")->where(['id'=>$resume_id])->save(['new_count'=>1]);
        }else{
            // M("ResumeCast")->where("recruitment_id=$id AND user_id=".$this->user_info['user_id'])->save(array("add_time"=>time()));
        }
        $this->success("发送成功");
    }


    public function resume_info(){
        //我的简历
        $info = D("Recruitment")->resume_info(array("user_id"=>$this->user_info['user_id']));
        $region_all = D('Address')->region_all(['region_type'=>2]);
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->assign("info",$info);
        $this->display();
    }

    public function resume_cast_list(){
        //我的投放
        $M_Recruitment = D("Recruitment");
        $filter = ["user_id"=>$this->user_info['user_id']];
        $count = $M_Recruitment->resume_cast_count($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Recruitment->resume_cast_list($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:resume_cast_list_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function resume_save(){
      //简历保存
      $M_Resume = M("Resume");
      $rules = array (
        // array('name','require','请填写姓名！'),
        // array('age','number','请填写年龄！'),
        // array('height','number','请填写身高！'),
        // array('marriage','require','请填写婚姻状况！'),
        // array('phone','number','请填写手机！'),
        // array('email','email','请填写邮箱！'),
        // array('province[]','require','请选择工作地点！'),
        // array('city[]','require','请选择工作地点！'),
        // // array('district[]','require','请选择工作地点！'),
        // array('education','require','请填写学历！'),
        // array('profession','require','请填写专业！'),
        // array('working_age','require','请填写工作年龄！'),
        // array('experience','require','请填写工作经验！'),
        // array('education_information','require','请填写教育信息！'),
        // array('talent','require','请填写技能专长！'),
        // array('self_evaluation','require','请填写自我评价！'),
        // array('protocol','require','请同意协议内容！'),
        // array('position','require','请填写期望职位！'),
      );
      if(!$M_Resume->validate($rules)->create()){
        $this->error($M_Resume->getError());
      }
      $data = $M_Resume->create();
      if(!is_phone($data['phone'])){
        $this->error("请填写正确的手机号码");
      }

      
        $province = $this->_post('province');
        $city = $this->_post('city');
        // $district = $this->_post('district');
        $data['province'] = implode(",",array_filter($province));
        $data['city'] = implode(",",array_filter($city));
        // $data['district'] = implode(",",array_filter($district));


      $data['user_id'] = $this->user_info['user_id'];
      $data['is_open'] = $this->_post('is_open')?$this->_post('is_open'):0;
      $data['add_time'] = time();

      // if($_FILES['file']['error']===0){
      //   $originalPath='Uploads/resume/'.$date;
      //   $upfile=parent::upload(array('jpg', 'gif', 'png', 'jpeg', "doc", "docx"),$originalPath,'uniqid');
      //   if($upfile[0]['savename']){
      //     $data['file']  = $originalPath.$upfile[0]['savename'];
      //   }
      // }
      
        $is_resume = D("Recruitment")->is_resume($this->user_info['user_id']);
        if(empty($is_resume)){
            $saveId=$M_Resume->data($data)->add();
        }else{
            if(!empty($data['file'])&&$is_resume['file']!=$data['file']){
                unlink($is_resume['file']);
            }
            $saveId = $is_resume['id'];
            $M_Resume->where("user_id=".$this->user_info['user_id'])->save($data);
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
            $ResumeImg = M("ResumeImg")->where("id NOT IN (".implode(",",$file_id).") AND resume_id=$saveId")->select();
            foreach($ResumeImg as $v){
                @unlink($v['original_img']);
                @unlink($v['thumb_img']);
            }
            $ResumeImg = M("ResumeImg")->where("id NOT IN (".implode(",",$file_id).") AND resume_id=$saveId")->delete();
        }
        foreach($upfile as $k=>$v){
            $con = [
                'thumb_img'=>$thumbPath."thumb_".$upfile[$k]['savename'],
                'original_img'=>$thumbPath."original_".$upfile[$k]['savename'],
                'resume_id'=>$saveId,
                'user_id'=>$this->user_info['user_id'],
            ];
            // if($file_id[$k]>0){
            //   M("ResumeImg")->where(array('id'=>$file_id[$k]))->save($con);
            // }else{
              M("ResumeImg")->data($con)->add();
            // }
        }
        
        $this->success('提交成功',U('Recruitment/lists'));
    }






    public function my_kindergarten(){
        //我的园区列表
        $M_Recruitment = D("Recruitment");
        $filter = array("user_id"=>$this->user_info['user_id']);
        $count = $M_Recruitment->count2($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,999);
        $this->assign("list",$M_Recruitment->lists2($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        // if(IS_AJAX){
        //   $data = $this->fetch('Recruitment:lists2_ajax');
        //   if($page->nowPage>=$page->totalPages){
        //     $this->ajaxReturn($data,"完结了",500);
        //   }else{
        //     $this->ajaxReturn($data,"获取成功",200);
        //   }
        // }
        $this->display();
    }
    public function my_kindergarten_info(){
        //我的园区详情
        $id = $this->_get('id');
        $info = D("Recruitment")->info2($id,$this->user_info['user_id']);
        $this->assign("info",$info);
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    public function my_del2(){
        //删除
        $id = $this->_get("id");
        $is = M("Kindergarten")->where("id=$id AND user_id=".$this->user_info['user_id'])->delete();
        if($is){
            M("KindergartenSignup")->where("kindergarten_id=$id")->delete();
        }
        $this->success('删除成功',U('Recruitment/my_kindergarten'));
    }
    public function my_info_save2(){
      //保存
      $M_Recruitment = M("Kindergarten");
      $rules = array (
        array('title','require','请填写园区名称！'),
        array('address','require','请填园区地址！'),
        array('province','require','请选择园区地点！'),
        array('city','require','请选择园区地点！'),
        array('district','require','请选择园区地点！'),
        array('text','require','请填写园区描述！'),
        array('protocol','require','请同意协议内容！'),
        array('name','require','请填写联系人！'),
        array('phone','require','请填写联系人电话！'),
        array('end_time','require','请填有效时间！'),
      );
      if(!$M_Recruitment->validate($rules)->create()){
        $this->error($M_Recruitment->getError());
      }
      $id = $this->_post('id');
      $data = $M_Recruitment->create();
      // if(!empty($id)){
      //   $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
      //   if($info['is_open']==1){
      //       $data = array();
      //   }
      // }
      $data['user_id'] = $this->user_info['user_id'];
      $data['status'] = $this->_post('status')!==null?$this->_post('status'):0;
      $data['end_time'] = strtotime($this->_post('end_time'));
        if(empty($id)){
            if(!is_phone($data['phone'])){
              $this->error("请填写正确的手机号码");
            }
            $data['add_time'] = time();
            $M_Recruitment->data($data)->add();
        }else{
            $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
            if(!$info){
                $this->error("找不到此记录");
            }
            $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->save($data);
        }
        $this->success('提交成功',U('Recruitment/my_kindergarten'));
    }
    public function index2(){
        //招聘中心
        // $this->assign("recruitment_list",D("Recruitment")->lists(0,3));
        // $this->assign("resume_list",D("Recruitment")->resume_lists(0,3));
        /************************banner*****************************/
        $banner = D('Ads')->lists(7,10);
        $this->assign('banner',$banner);

        $this->assign("kindergarten_list",D("Recruitment")->lists2(0,3));
        $this->assign("kindergarten_xuqiu_list",D("Recruitment")->lists3(0,3));
        // import('ORG.Net.IpLocation');// 导入IpLocation类
        // $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        // $area = $Ip->getlocation(); // 获取某个IP地址所在的位置
        // var_dump($area);

        // 按钮
        $btn_list = D('Ads')->lists(13,4);
        $this->assign('btn_list',$btn_list);

        $this->display();
    }
    public function lists2(){
        //园区列表
        $M_Recruitment = D("Recruitment");
        $keyword = $this->_get("keyword");
        $filter = array("keyword"=>$keyword);
        $count = $M_Recruitment->count2($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Recruitment->lists2($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("keyword",$keyword);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:lists2_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function info2(){
        //园区详情
        $id = $this->_get('id');
        M("kindergarten")->where("id=$id")->setInc("view_count","1");
        $info = D("Recruitment")->info2($id);
        $this->assign("info",$info);
        $this->display();
    }
    public function kindergarten_signup(){
        //意向提交
        $M_KindergartenSignup = M("KindergartenSignup");
        $rules = array (
          array('name','require','请填写联系人！'),
          array('phone','require','请填写手机号码！'),
          array('kindergarten_id','require','请选择园区！'),
        );
        if(!$M_KindergartenSignup->validate($rules)->create()){
          $this->error($M_KindergartenSignup->getError());
        }
        $data = $M_KindergartenSignup->create();
        if(!is_phone($data['phone'])){
          $this->error("请填写正确的手机号码");
        }
        $data['add_time'] = time();
        $data['user_id'] = $this->user_info['user_id'];
        M("kindergarten")->where(['id'=>$this->_post('kindergarten_id')])->save(['new_count'=>1]);
        $M_KindergartenSignup->data($data)->add();
        $this->success('提交成功',U('Recruitment/lists2'));
    }
    public function lists3(){
        //园区列表
        $M_Recruitment = D("Recruitment");
        $keyword = $this->_get("keyword");
        $filter = array("keyword"=>$keyword);
        $count = $M_Recruitment->count3($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,8);
        $this->assign("list", $M_Recruitment->lists3($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        $this->assign("keyword",$keyword);
        if(IS_AJAX){
          $data = $this->fetch('Recruitment:lists3_ajax');
          if($page->nowPage>=$page->totalPages){
            $this->ajaxReturn($data,"完结了",500);
          }else{
            $this->ajaxReturn($data,"获取成功",200);
          }
        }
        $this->display();
    }
    public function info3(){
        //需求详情
        $id = $this->_get('id');
        M("kindergartenXuqiu")->where("id=$id")->setInc("view_count","1");
        $info = D("Recruitment")->info3($id);
        $this->assign("info",$info);
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    public function kindergarten_signup3(){
        //意向提交
        $M_KindergartenSignup = M("KindergartenSignup");
        $rules = array (
          array('name','require','请填写联系人！'),
          array('phone','require','请填写手机号码！'),
          array('kindergarten_xuqiu_id','require','请选择需求！'),
        );
        if(!$M_KindergartenSignup->validate($rules)->create()){
          $this->error($M_KindergartenSignup->getError());
        }
        $data = $M_KindergartenSignup->create();
        if(!is_phone($data['phone'])){
          $this->error("请填写正确的手机号码");
        }
        $data['add_time'] = time();
        $data['user_id'] = $this->user_info['user_id'];
        M("kindergartenXuqiu")->where(['id'=>$this->_post('kindergarten_xuqiu_id')])->save(['new_count'=>1]);
        $M_KindergartenSignup->data($data)->add();
        $this->success('提交成功',U('Recruitment/lists3'));
    }
    public function my_kindergarten_xuqiu(){
        //我的园区列表
        $M_Recruitment = D("Recruitment");
        $filter = array("user_id"=>$this->user_info['user_id']);
        $count = $M_Recruitment->count3($filter);
        import("ORG.Util.Page");       //载入分页类
        $page = new Page($count,999);
        $this->assign("list",$M_Recruitment->lists3($page->firstRow, $page->listRows,$filter));
        $this->assign("listRows",$page->listRows);
        // if(IS_AJAX){
        //   $data = $this->fetch('Recruitment:lists2_ajax');
        //   if($page->nowPage>=$page->totalPages){
        //     $this->ajaxReturn($data,"完结了",500);
        //   }else{
        //     $this->ajaxReturn($data,"获取成功",200);
        //   }
        // }
        $this->display();
    }
    public function my_kindergarten_xuqiu_info(){
        //我的园区详情
        $id = $this->_get('id');
        $info = D("Recruitment")->info3($id,$this->user_info['user_id']);
        $this->assign("info",$info);
        $region_all = D('Address')->region_all();
        $this->assign("region_all",json_encode($region_all['0']['child'],JSON_UNESCAPED_UNICODE));
        $this->display();
    }
    
    public function my_del3(){
        //删除
        $id = $this->_get("id");
        $is = M("KindergartenXuqiu")->where("id=$id AND user_id=".$this->user_info['user_id'])->delete();
        if($is){
            M("KindergartenSignup")->where("kindergarten_xuqiu_id=$id")->delete();
        }
        $this->success('删除成功',U('Recruitment/my_kindergarten_xuqiu'));
    }
    public function my_info_save3(){
      //保存
      $M_Recruitment = M("KindergartenXuqiu");
      $rules = array (
        array('title','require','请填写需求名称！'),
        array('province','require','请选择需求地点！'),
        array('city','require','请选择需求地点！'),
        array('district','require','请选择需求地点！'),
        array('text','require','请填写需求描述！'),
        array('protocol','require','请同意协议内容！'),
        array('name','require','请填写联系人！'),
        array('phone','require','请填写联系人电话！'),
      );
      if(!$M_Recruitment->validate($rules)->create()){
        $this->error($M_Recruitment->getError());
      }
      $id = $this->_post('id');
      $data = $M_Recruitment->create();

      $data['user_id'] = $this->user_info['user_id'];
      $data['status'] = $this->_post('status')!==null?$this->_post('status'):0;
      if(!is_phone($data['phone'])){
        $this->error("请填写正确的手机号码");
      }
        if(empty($id)){
            $data['add_time'] = time();
            $M_Recruitment->data($data)->add();
        }else{
            $info = $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->find();
            if(!$info){
                $this->error("找不到此记录");
            }
            $M_Recruitment->where("id=$id AND user_id=".$this->user_info['user_id'])->save($data);
        }
        $this->success('提交成功',U('Recruitment/my_kindergarten_xuqiu'));
    }
}
?>