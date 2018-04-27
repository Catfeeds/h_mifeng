<?php

$config_arr1 = include_once WEB_ROOT . 'Common/config.php';
$config_arr2 = array(
	'SHOW_PAGE_TRACE'=>false,
	'TMPL_PARSE_STRING'=>array(
		'__IMG__'=>__ROOT__.'/Public/Home/images',
		'__JS__'=>__ROOT__.'/Public/Home/js',
		'__CSS__'=>__ROOT__.'/Public/Home/css',
	),
    'OUTPUT_ENCODE'=>true,
    'URL_HTML_SUFFIX'=>'html',//伪静态
    //'URL_PATHINFO_DEPR'=>'-',
    'URL_MODEL' => 2,//URL重写 隐藏index.php用
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    // 'VAR_FILTERS' => 'htmlspecialchars',
    

);
return array_merge($config_arr1, $config_arr2);

?>