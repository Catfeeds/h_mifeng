<?php

// 本类设置项目一些常用信息
class MysqlAction extends CommonAction {
    public function _initialize() {
    }
    public function mysql() {
        $DataDir = "databak/";
        mkdir($DataDir);
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
        $mr->backup();
        $lists = $this->MyScandir('databak/',1);
    }

    private function MyScandir($FilePath = './', $Order = 0) {
        $FilePath = opendir($FilePath);
        while (false !== ($filename = readdir($FilePath))) {
            $FileAndFolderAyy[] = $filename;
        }
        if(count($FileAndFolderAyy)>32){
            @unlink($DataDir.$FileAndFolderAyy[2]);
            $FilePath = opendir($FilePath);
            while (false !== ($filename = readdir($FilePath))) {
                $FileAndFolderAyy[] = $filename;
            }
        }
        $Order == 0 ? sort($FileAndFolderAyy) : rsort($FileAndFolderAyy);
        return $FileAndFolderAyy;
    }
}

?>