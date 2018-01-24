<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class IndexController extends BaseController {
    


    public function index(){
    	// layout(false); // 临时关闭当前模板的布局功能
    	$this->display();
    }




}