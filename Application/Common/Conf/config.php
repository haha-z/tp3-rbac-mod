<?php
return array(
	//'配置项'=>'配置值'
	
	'LOAD_EXT_CONFIG' => 'user,db',//加载自定义配置文件
	'SHOW_PAGE_TRACE' => false,
	'TAGLIB_BUILD_IN'       =>  'Cx,Common\Tag\My',   //加载自定义标签

	/* URL配置 */
  	'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
  	'URL_MODEL'            => 2, //URL模式
  	'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
  	'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
	'URL_HTML_SUFFIX'      =>'html|xhtml|shtml|htm',


    // 开启路由，路由配置格式放到项目文件中
    'URL_ROUTER_ON'   => true, 

);