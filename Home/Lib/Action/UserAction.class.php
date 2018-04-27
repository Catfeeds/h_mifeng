<?php
class UserAction extends CommonAction {
    public function __construct() {
        parent::__construct();
    }
    /*
    * 登录页
     */
    public function registered(){
        //登陆的跳转个人中心
        $userInfo = session('userInfo');
        //检测cookie记录 有的话登陆
        $user_login = cookie('user_login');
        if($user_login['openid']){
            $userInfo = M('User')->where("openid='".$user_login['openid']."'")->find();
            if(!empty($userInfo)){
                session('userInfo',$userInfo);
            }
        }elseif($user_login['phone']){
            $userInfo = M('User')->where(array("phone"=>$user_login['phone']))->find();
            if(!empty($userInfo)&&$user_login['password']==$userInfo['password']){
                session('userInfo',$userInfo);
            }
        }
        $back_url = session('back_url');
        if(empty($back_url)){
            $back_url = U('Member/index');
            session('back_url',$back_url);
        }
        if($userInfo){
            $this->redirect('Member/index');
        }
        $this->display('registered');
    }
    public function registered2(){
        //登陆的跳转个人中心
        $userInfo = session('userInfo');
        //检测cookie记录 有的话登陆
        $user_login = cookie('user_login');
        if($user_login['openid']){
            $userInfo = M('User')->where("openid='".$user_login['openid']."'")->find();
            if(!empty($userInfo)){
                session('userInfo',$userInfo);
            }
        }elseif($user_login['phone']){
            $userInfo = M('User')->where(array("phone"=>$user_login['phone']))->find();
            if(!empty($userInfo)&&$user_login['password']==$userInfo['password']){
                session('userInfo',$userInfo);
            }
        }
        if($userInfo){
            $this->redirect('Member/index');
        }
        $back_url = session('back_url');
        if(empty($back_url)){
            $back_url = U('Member/index');
            session('back_url',$back_url);
        }
        $this->display();
    }
    /**
     * 提交手机后判断
     */
    public function phone_registered(){
        $M_User = D("User");
        $phone = trim($this->_post('phone'));
        if(!is_phone($phone)){
            $this->ajaxReturn(0,"请填写正确的手机号码",500);
        }
        $users = $M_User->where("phone = $phone")->find();
        session('user_phone',trim($phone));
        if(!$users){
            //跳转验证码输入
            $this->ajaxReturn(0,"验证码",202);
            // $this->display("registered_code");
        }else{
            //跳转密码登陆
            $this->ajaxReturn(0,"密码",201);
            // $this->display("login");
        }
    }
    //验证码发送
    public function code(){
        $phone = $this->_post('phone')!==null?$this->_post('phone'):session('user_phone');
        //修改绑定手机号
        $is_rg = $this->_post('is_rg');
        if($is_rg==2){
            //发送现登陆的手机号码
            parent::check_login();
            $phone = $this->user_info['phone'];
        }
        //是否为注册的
        if($is_rg==1){
            $count = M("User")->where("phone=$phone")->count();
            if($count){
                $this->ajaxReturn(500,"该手机号码已被使用",500);
            }
        }
        //绑定新手机号码
        if($is_rg==4){
            parent::check_login();
            $User = M("User")->where("phone=$phone AND user_id<>".$this->user_info['user_id'])->find();
            if($User){
                // if(empty($this->user_info['phone'])&&!empty($User['openid'])){
                //     $this->ajaxReturn(500,"该手机号码已被使用",500);
                // }else{
                    $this->ajaxReturn(500,"该手机号码已被使用",500);
                // }
            }
        }
        if(!is_phone($phone)){
            $this->ajaxReturn(500,"请填写正确的手机号码",500);
        }
        // if($this->_post('phone')!==null){
        //     $user_info = session('userInfo');
        //     if(!empty($user_info)){
        //         $count = M("User")->where("phone=$phone AND user_id<>".$user_info['user_id'])->count();
        //         if($count){
        //             $this->ajaxReturn(500,"该手机号码已被使用",500);
        //         }
        //     }
        // }
        $time = time()-60;
        $code = M("code")->where("add_time>$time AND phone=$phone")->find();
        if(!$code){
            //60秒内没发送，发送验证码
            $ip = get_client_ip();
            //限制每天10次
            $today = strtotime(date('Y-m-d'));
            $code_count = M("code")->where("(ip='$ip' OR phone=$phone) AND add_time>$today ")->count();
            if($code_count>10){
                $this->ajaxReturn(500,"您今天发送的验证码次数过多，请稍后重试!",500);
            }else{
                $data['code'] = rand(100000,999999);
                $data['ip'] = $ip;
                $data['phone'] = $phone;
                $data['add_time'] = time();
                $add_id = M('code')->add($data);
                $html = "验证码为".$data['code'].",请在30分钟内填写，如非本人操作，请忽略本短信";
                // $this->send_msg($phone,$html);
                $this->ajaxReturn($data['code'],"发送成功!",200);
            }
        }else{
            $this->ajaxReturn(501,"验证码发送间隔60秒!",500);
        }
    }
    //登陆验证
    // public function do_login(){
    //     $M_User = D("User");
    //     $phone    = session('user_phone');
    //     $password =  $this->_post('password','md5','');
    //     $back_url = session('back_url');

    //     $userInfo = $M_User->where(array("phone"=>$phone))->find();
    //     if(!$userInfo) $this->error('此会员不存在！');
    //     if($userInfo['password'] != $password) $this->error('密码不正确！');
    //     //记录最后登录时间和ip地址
    //     $data['last_login_time']    = time();
    //     $data['last_login_ip']      = get_client_ip();

    //     $affected_rows = $M_User->where(array('user_id'=>$userInfo['user_id']))->save($data);

    //     if($affected_rows>0){
    //         //10天内免登录
    //         $time = 3600*24*10;
    //         cookie('user_login',array("phone"=>$userInfo['phone'],"password"=>$userInfo['password']),$time);
    //         session('userInfo',$userInfo);
    //         //清除
    //         session('back_url',null);
    //         session('user_phone',null);

    //         $this->ajaxReturn($back_url,"登陆成功",200);
    //     }else{
    //         $this->ajaxReturn('',"网络错误，请稍候再试！",500);
    //     }
    // }
    public function do_login(){
        $M_User = D("User");
        $phone    = $this->_post('user_phone');
        $password =  $this->_post('password','md5','');
        $back_url = session('back_url');
        $userInfo = $M_User->where(array("phone"=>$phone))->find();
        if(!$userInfo) $this->error('此会员不存在！');
        if($userInfo['password'] != $password) $this->error('密码不正确！');
        //记录最后登录时间和ip地址
        $data['last_login_time']    = time();
        $data['last_login_ip']      = get_client_ip();
        $affected_rows = $M_User->where(array('user_id'=>$userInfo['user_id']))->save($data);
        // if($affected_rows>0){
        // 
        //10天内免登录
        $time = 3600*24*10;
        cookie('user_login',array("phone"=>$userInfo['phone'],"password"=>$userInfo['password']),$time);
        session('userInfo',$userInfo);
        //清除
        session('back_url',null);
        session('user_phone',null);
        if($back_url){
            $this->success('成功',$back_url);
        }else{
            $this->redirect('Member/index');
        }

        // }else{
        //     $this->ajaxReturn('',"网络错误，请稍候再试！",500);
        // }
    }
    //注册
    public function do_register(){
        $M_User = D("User");
        $rules = array (
            array('code','require','短信验证码不能为空',1),
            array('password','require','密码不能为空',1),
            array('password','6,21','密码长度请设置在6-21字符串内',1,'length'),
            array('phone','require','手机号码不能为空',1),
        );
        if (!$M_User->validate($rules)->create()){
            $this->error($M_User->getError());
        }
        $data['phone']    = $this->_post('phone');
        if(!is_phone($data['phone'])){
            $this->error("请填写正确的手机号码");
        }
        $count = M("User")->where("phone=".$data['phone'])->count();
        if($count){
            $this->error("该手机号码已被使用");
        }

        $data['code']     = $this->_post('code');
        $data['password'] =  $this->_post('password','md5','');
        //验证验证码
        $time = time()-1800;
        $code = M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$data['phone']."'")->count();
        if($code){
            $invite_uid = session('invite_uid');
            if(!empty($invite_uid)){
                $data['invite_uid'] = $invite_uid;
            }
            $data['reg_time']        = time();
            $data['last_login_time'] = time();
            $data['last_login_ip']   = get_client_ip();
            $data['user_name']       = $data['phone'];
            $add_id = $M_User->add($data);
            if(!empty($invite_uid)){
                D("Integral")->integral_zs($invite_uid,2);
            }
            if($add_id){
                M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$data['phone']."'")->delete();
                //10天内免登录
                $userInfo = M("User")->where(array("user_id"=>$add_id))->find();
                $time = 60*60*24*10;
                cookie('user_login',array("phone"=>$userInfo['phone'],"password"=>$userInfo['password']),$time);
                session('userInfo',$userInfo);

                $back_url = session('back_url');
                session('back_url',null);
                session('user_phone',null);
                if($back_url){
                    $this->success('成功',$back_url);
                }else{
                    $this->redirect('Member/index');
                }
            }else{
                $this->error("注册失败");
            }
        }else{
            $this->error("验证码错误！");
        }
    }
    //安全退出
    public function logout(){
        cookie('user_login',null);
        session('userInfo',null);
        $this->redirect('User/registered');
    }

    public function oauth(){
        $userInfo = session('userInfo');
        if($userInfo){
            $this->redirect('Member/index');
        }
        //微信登录
        // $appid = 'wx22d6c4e5154c99e0';
        // $appsecret = 'af96f169beb932430c7150903313e61a';
        $appid = 'wx1558a054621bf22b';
        $appsecret = '9187dba323d549273797fcf4ad25477b';
        $url = urlencode('http://mifengbeibei.com/User/oauth');
        $code = $_GET['code'];
        $state = $_GET['state'];

        if(!$code){
            $back_url = $_SERVER['HTTP_REFERER'];
            if(!$back_url||strpos($back_url, 'logout')){
                $back_url = U('Member/index');
            }
            session('back_url',$back_url);
            header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect');
        }
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $token = json_decode(file_get_contents($token_url));
        if (isset($token->errcode)) {
         echo '<h1>错误：</h1>'.$token->errcode;
         echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
         exit;
        }
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
        //转成对象
        $access_token = json_decode(file_get_contents($access_token_url));
        if (isset($access_token->errcode)) {
         echo '<h1>错误：</h1>'.$access_token->errcode;
         echo '<br/><h2>错误信息：</h2>'.$access_token->errmsg;
         exit;
        }
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN'; //开源软件:phpfensi.com
        //转成对象
        $user_info = json_decode(file_get_contents($user_info_url));
        if (isset($user_info->errcode)) {
         echo '<h1>错误：</h1>'.$user_info->errcode;
         echo '<br/><h2>错误信息：</h2>'.$user_info->errmsg;
         exit;
        }
        //打印用户信息
        $M_User = D("User");
        $userInfo = M('User')->where("openid='".$user_info->openid."'")->find();
        $back_url = session('back_url');
        //记录cookie
        $time = 60*60*24*10;
        // cookie('user_login',array("openid"=>$openid),$time);
        if($userInfo){
            //登录
            // $data = array();
            // $data['user_name'] = $user_info->nickname;
            // $pic = substr($user_info->headimgurl,0,-1)."96";
            // $data['pic'] = $pic;
            // M('User')->where('openid='.$user_info->openid)->save($data);
            // $userInfo = M('User')->where('openid='.$user_info->openid)->find();
            cookie('userInfo',array("phone"=>$phone,"password"=>$password),$time);
            session('userInfo',$userInfo);
            // session(['userInfo'=>$userInfo,'expire'=>$time]);
            if($back_url){
                $this->success('成功',$back_url);
            }else{
                $this->redirect('Member/index');
            }
            
        }else{
            //注册
            $data = array();
            $openid = $user_info->openid;
            $invite_uid = session('invite_uid');
            if(!empty($invite_uid)){
                $data['invite_uid'] = $invite_uid;
            }
            $data['openid'] = $openid;
            $data['user_name'] = $user_info->nickname;
            $data['reg_time']        = time();
            $data['last_login_time'] = time();
            $data['last_login_ip']   = get_client_ip();
            // $pic = substr($user_info->headimgurl,0,-1)."96";
            $pic = $user_info->headimgurl;
            // var_dump($pic);exit;
            $filename='Uploads/user/thumb_img/'.date('Y-m-d').'/'.uniqid().".png";
            $data['pic'] = $this->http_down($pic,$filename,60);
            $add_id = M('User')->add($data);
            $userInfo = M('User')->where(array("user_id"=>$add_id))->find();
            cookie('userInfo',array("phone"=>$phone,"password"=>$password),$time);
            session('userInfo',$userInfo);
            if(!empty($invite_uid)){
                D("Integral")->integral_zs($invite_uid,2);
            }
            if($back_url){
                $this->success('成功',$back_url);
            }else{
                $this->redirect('Member/index');
            }
        }
        
    }

    function http_down($url, $filename, $timeout = 60) {
        $path = dirname($filename);
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            return false;
        }
        $fp = fopen($filename, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $filename;
    }

    public function forget_password(){
        //忘记密码
        $user_info = session('userInfo');
        if(!empty($user_info)){
            session('user_phone',trim($user_info['phone']));
        }
        $this->display();
    }
    public function forget_password_save(){
        //忘记密码-设置密码
        $M_User = D("User");
        $rules = array (
            array('code','require','短信验证码不能为空',1),
            array('phone','require','手机号码不能为空',1),
            array('password','require','密码不能为空',1),
            array('password','6,21','密码长度请设置在6-21字符串内',1,'length'),
        );
        if (!$M_User->validate($rules)->create()){
            $this->error($M_User->getError());
        }
        $data['phone']    = $this->_post('phone');
        $data['code']     = $this->_post('code');
        $data['password'] =  $this->_post('password','md5','');
        //验证验证码
        $time = time()-1800;
        $code = M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$data['phone']."'")->count();
        if($code){
            $M_User->where("phone=".$data['phone'])->save(array("password"=>$data['password']));
            M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$data['phone']."'")->delete();
            $this->success('修改成功',U('User/registered'));
        }else{
            $this->error("验证码错误！");
        }
    }
    public function exit_password(){
        //修改密码
        parent::check_login();
        if(empty($this->user_info['phone'])){
            $this->error("请先绑定手机！",U('User/edit_phone2'));
        }
        $this->display();
    }
    public function exit_password_save(){
        //忘记密码-设置密码
        parent::check_login();
        $M_User = D("User");
        $rules = array (
            array('code','require','短信验证码不能为空',1),
            array('password','require','密码不能为空',1),
            array('password','6,21','密码长度请设置在6-21字符串内',1,'length'),
        );
        if (!$M_User->validate($rules)->create()){
            $this->error($M_User->getError());
        }
        $data['code']     = $this->_post('code');
        $data['password'] =  $this->_post('password','md5','');
        //验证验证码
        $time = time()-1800;
        $code = M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$this->user_info['phone']."'")->count();
        if($code){
            $M_User->where("user_id=".$this->user_info['user_id'])->save(array("password"=>$data['password']));
            M("code")->where("code='".$data['code']."' AND add_time>'".$time."' AND phone='".$data['phone']."'")->delete();
            $this->success('修改成功',U('Member/index'));
        }else{
            $this->error("验证码错误！");
        }
    }
    public function edit_phone(){
        //修改手机
        parent::check_login();
        if(empty($this->user_info['phone'])){
            $this->redirect('User/edit_phone2');
        }
        session('user_phone',trim($this->user_info['phone']));
        $this->display();
    }
    public function edit_phone2(){
        //修改手机
        parent::check_login();
        session('user_phone',trim($this->user_info['phone']));
        $code      = $this->_post('code');

        $time = time()-1800;
        if(!empty($this->user_info['phone'])){
            $is_code = M("code")->where("code='$code' AND add_time>'".$time."' AND phone='".$this->user_info['phone']."'")->count();
            if(!$is_code){
                $this->error("手机验证码错误！");
            }
        }
        $this->assign('code',$code);
        $this->display();
    }
    public function edit_phone_save(){
        parent::check_login();
        $M_User = D("User");
        $rules = array (
            // array('code','require','旧手机验证码不能为空',1),
            array('code2','require','新手机验证码不能为空',1),
            array('phone','require','新手机号码不能为空',1),
        );
        $code      = $this->_post('code');
        $old_phoen = $this->user_info['phone'];
        $code2     = $this->_post('code2');
        $phone     = $this->_post('phone');
        if(!is_phone($phone)){
            $this->error("请填写正确的手机号码",U('User/edit_phone'));
        }
        $User = M("User")->where("phone=$phone AND user_id<>".$this->user_info['user_id'])->find();
        if($User){
            // if(empty($this->user_info['phone'])&&!empty($User['openid'])){
            //     $this->ajaxReturn(500,"该手机号码已被使用",500);
            // }else{
                $this->ajaxReturn(500,"该手机号码已被使用",500);
            // }
        }
        $time = time()-1800;
        if(!empty($old_phoen)){
            //微信绑定手机
            $is_code = M("code")->where("code='$code' AND add_time>'".$time."' AND phone='".$old_phoen."'")->count();
            if(!$is_code){
                $this->error("旧手机验证码错误！",U('User/edit_phone'));
            }
        }
        $is_code2 = M("code")->where("code='$code2' AND add_time>'".$time."' AND phone='$phone'")->count();
        if(!$is_code2){
            $this->error("新手机验证码错误！");
        }
        $M_User->where("user_id=".$this->user_info['user_id'])->save(array("phone"=>$phone));
        // if(!empty($old_phoen)&&$User){
        //     //微信绑定手机  转换原有手机号记录
        //     D("User")->where("user_id=".$this->user_info['id'])->setInc('integral',$User['integral']);
        //     M("Collect")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("Address")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("Attention")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("Attention")->where("attention_id=".$User['id'])->update(['attention_id'=>$this->user_info['id']]);
        //     M("Comment")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("Comment")->where("parent_id=".$User['id'])->update(['parent_id'=>$this->user_info['id']]);
        //     M("Company")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("ContactUs")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("CourseOrder")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        //     M("Information")->where("user_id=".$User['id'])->update(['user_id'=>$this->user_info['id']]);
        // }
        
        $this->success('修改成功',U('Member/index'));
    }
}