<?php
return array(
	//'配置项'=>'配置值'
	//模板路径
	// 'VIEW_PATH'=>'./Tpl/Home/',
	//开启全局公共模版
	'LAYOUT_ON'=>true,
	'LAYOUT_NAME'=>'Public/index',

	// 定义常用路径
	'TMPL_PARSE_STRING'    => array(
    	'__HOME_CSS__'         => __ROOT__.trim('/Public/static/css'),
    	'__HOME_JS__'          => __ROOT__.trim('/Public/static/js'),
    	'__HOME_IMG__'         => __ROOT__.trim('/Public/static/img'),
	),

);