<?php

// 本类设置项目一些常用信息
class WebinfoAction extends CommonAction {
      public function _initialize() {
        parent::_initialize();  
        parent::admin_priv('Systems');
      }
    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function index() {
        $this->checkSystemConfig();
    }

    /**
      +----------------------------------------------------------
     * 配置网站邮箱信息
      +----------------------------------------------------------
     */
    public function setEmailConfig() {
        $this->checkSystemConfig("SYSTEM_EMAIL");
    }

    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function setSafeConfig() {
        $this->checkSystemConfig("TOKEN");
    }

    /**
      +----------------------------------------------------------
     * 网站配置信息保存操作等
      +----------------------------------------------------------
     */
    private function checkSystemConfig($obj = "SITE_INFO") {
        if (IS_POST) {

            $config = WEB_ROOT . "Common/systemConfig.php";
			
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $config = array_merge($config, array("$obj" => $_POST));
            $str = $obj == "SITE_INFO" ? "网站配置信息" : $obj == "SYSTEM_EMAIL" ? "系统邮箱配置" : "安全设置";
            if (F("systemConfig", $config, WEB_ROOT . "Common/")) {
                delDirAndFile(WEB_CACHE_PATH . "Runtime/Admin/~runtime.php");
                if ($obj == "TOKEN") {
                    unset($_SESSION, $_COOKIE);
					$this->success($str . '已更新，你需要重新登录',__APP__);
                } else {
					$this->success($str . '已更新');
                }
            } else {
				$this->error($str . '失败，请检查',__ACTION__);
            }
        } else {
			
            $this->display();
        }
    }

    /**
      +----------------------------------------------------------
     * 测试邮件账号是否配置正确
      +----------------------------------------------------------
     */
    public function testEmailConfig() {
        C('TOKEN_ON', false);
        $return = send_mail($_POST['test_email'], "", "测试配置是否正确", "这是一封测试邮件，如果收到了说明配置没有问题", "", $_POST);
        if ($return == 1) {
            echo json_encode(array('status' => 1, 'info' => "测试邮件已经发往你的邮箱" . $_POST['test_email'] . "中，请注意查收"));
        } else {
            echo json_encode(array('status' => 0, 'info' => "$return"));
        }
    }


    public function mysql() {
        $DataDir = "databak/";
        mkdir($DataDir);
        if (!empty($_GET['Action'])) {
            require_once "mysql/MySQLReback.class.php";
            $config = array(
                'host' => C('DB_HOST'),
                'port' => C('DB_PORT'),
                'userName' => C('DB_USER'),
                'userPassword' => C('DB_PWD'),
                'dbprefix' => C('DB_PREFIX'),
                'charset' => 'UTF8',
                'path' => $DataDir,
                'isCompress' => 0, //是否开启gzip压缩
                'isDownload' => 0
            );
            $mr = new Admin\Controller\MySQLReback($config);
            $mr->setDBName(C('DB_NAME'));
            if ($_GET['Action'] == 'backup') {
                $mr->backup();
                  $this->success( '数据库备份成功！');
                // echo "<script>document.location.href='" . U("Webinfo/mysql") . "'</script>";
//                $this->success( '数据库备份成功！');
            } elseif ($_GET['Action'] == 'RL') {
                $mysql = $mr->recover($_GET['File']);
                if(isset($mysql['status'])&&!$mysql['status']){
                  $this->error($mysql['message']);
                }else{
                  $this->success( '数据库还原成功！');
                }
                // var_dump($mysql);exit;
                // echo "<script>document.location.href='" . U("Webinfo/mysql") . "'</script>";
//                $this->success( '数据库还原成功！');
            } elseif ($_GET['Action'] == 'Del') {
                if (@unlink($DataDir . $_GET['File'])) {
                    $this->success('删除成功！');
                    // echo "<script>document.location.href='" . U("Webinfo/mysql") . "'</script>";
                } else {
                    $this->error('删除失败！');
                }
            }
            if ($_GET['Action'] == 'download') {

                function DownloadFile($fileName) {
                    ob_end_clean();
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Length: ' . filesize($fileName));
                    header('Content-Disposition: attachment; filename=' . basename($fileName));
                    readfile($fileName);
                }
                DownloadFile($DataDir . $_GET['File']);
                exit();
            }
        }
        $lists = $this->MyScandir('databak/',1);
        $this->assign("datadir",$DataDir);
        $this->assign("lists", $lists);
        $this->display();
    }

    private function MyScandir($FilePath = './', $Order = 0) {
        $DataDir = "databak/";
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        $FilePath = opendir($FilePath);
        $FileAndFolderAyy = array();
        while (false !== ($filename = readdir($FilePath))) {
            if(strlen($filename)>10){
                $FileAndFolderAyy[] = $filename;
            }
        }
        if(count($FileAndFolderAyy)>32){
            $count = count($FileAndFolderAyy)-32;
            foreach($FileAndFolderAyy as $k=>$v){
                if($k<$count){
                    @unlink($DataDir.$FileAndFolderAyy[$k]);
                    unset($FileAndFolderAyy[$k]);
                }
            }
            // $FilePath = opendir($FilePath);
            // while (false !== ($filename = readdir($FilePath))) {
            //     $FileAndFolderAyy[] = $filename;
            // }
        }
        $Order == 0 ? sort($FileAndFolderAyy) : rsort($FileAndFolderAyy);
        return $FileAndFolderAyy;
    }
}

?>