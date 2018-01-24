<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class PublicController extends BaseController {

	protected function _initialize(){
		header('Content-Type:text/html; charset=utf-8');
	}

	public function Vlogin(){
		if (IS_GET){
			$uid = I('get.uid',0);
			
			if(!$uid){
				// $this->error('登陆失败！',U('Home/User/login'));
				
				die('请检查用户名！');
			}else{
				$map['id'] = $uid;
				$user_info = M('user')->where($map)->find();
				if (!$user_info) {
					// $this->error('登陆失败！',U('Home/Publlic/vlogin'));
					die('登陆失败！');
				}
			}
			//保存用户登陆状态
			$this->createLoginStatus($user_info);
			$this->success('登陆成功！',U('Home/Index/index'));
		}
    }

    public function forbidden(){
    	// layout(false); // 临时关闭当前模板的布局功能
    	$this->display('error');
    }

    public function logout(){
    	cookie('rbac_token',null);
    	$this->success('退出成功！',U('Home/Public/vlogin'));

    }



}