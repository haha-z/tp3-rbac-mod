<?php
 namespace Common\Tag;
 use Think\Template\TagLib;

/**
* 自定义标签
*/
class My extends TagLib{
	
	protected $tags   =  array(
	 	// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'ueditor'   => array('attr'=>'name,content','close'=>0),
        'recommend' => array('attr'=>'limit','level'=>1),
        'jquery' => array('close'=>0),
        'bootstrap' => array('close'=>0)

 	);


    /**
    *引入ueidter编辑器
    *@param string $tag  name:表单name content：编辑器初始化后 默认内容
    */
    public function _ueditor($tag){
        $name=$tag['name'];
        $content=$tag['content'];
        $link=<<<php
<script id="container" name="$name" type="text/plain">
    $content
</script>
<script type="text/javascript" src="__PUBLIC__/static/ueditor-1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/ueditor-1.4.3/ueditor.all.js"></script>
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>
php;
        return $link;
    }


    public function _jquery(){
        return '<script src="__PUBLIC__/static/js/jquery-2.1.3.min.js"></script>';
    }

    public function _bootstrap(){
        return '  <link rel="stylesheet" href="__PUBLIC__/static/css/bootstrap.min.css">';
    }

}