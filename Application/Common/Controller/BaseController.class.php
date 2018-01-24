<?php
namespace Common\Controller;
use Think\Controller;
//所有控制器的基类，集成常用公用方法
class BaseController extends Controller {
	
	protected $auth_cookie_name = "hahaz";
	//当前登录人信息
	protected $current_user = null;
	
	//需要权限访问的控制器方法，格式：控制器/方法名。
	protected $allow_action =[
		// 'User/login',
		'Index/index'
	];

	//不要权限访问的控制器方法
	protected $ignore_urls  = [
		'Public/forbidden' ,
		'Public/vlogin',
		'Public/login',
		'Public/index'
	];

	//保存对应用户所拥有权限中的url
	public $privilege_urls = [ ];

	//登录检查，所有控制器执行之前都要经过这里进行统一的处理
	protected function _initialize(){
		//登陆状态检查
		$login_status = $this->checkLoginStatus();
		// var_dump($login_status);
		
		//获取url控制器和方法
		$action = CONTROLLER_NAME."/".ACTION_NAME;//获取控制器方法名
		
		//没有登录和访问url不在允许的范围
		if ( !$login_status && !in_array($action, $this->allow_action )  ) {
			if(IS_GET){
				// $data['req_id']  = uniqid();
			 //    $data['status']  = '-302';
			 //    $data['content'] = '未登录/拒绝访问的地址';

			 //    $this->ajaxReturn($data);
			 	$this->redirect('Home/Public/vlogin',array('uid'=>0));//返回到登录页面
			 	// $this->show('请登陆！');
			}else{
				$this->error('登陆失败！',U('Home/Public/vlogin'));//返回到登录页面
				// die('Fobbiden');
			}
			return false;
		}

		//保存所有的访问数据到数据库当中
		$get_params = I('get.');
		$post_params = I('post.');
		$model_log = D('ActionLog');

	     // 验证通过 写入新增数据
		$model_log->uid = $this->current_user?$this->current_user['id']:0;
		$model_log->query_params = json_encode( array_merge( $post_params, $get_params ) );
		$model_log->target_url = isset( $_SERVER['REQUEST_URI'] )?$_SERVER['REQUEST_URI']:'';
		$model_log->query_params = json_encode( array_merge( $post_params,$get_params ) );
		$model_log->ua = isset( $_SERVER['HTTP_USER_AGENT'] )?$_SERVER['HTTP_USER_AGENT']:'';
		$model_log->ip = isset( $_SERVER['REMOTE_ADDR'] )?$_SERVER['REMOTE_ADDR']:'';
		$model_log->created_time = time();
	    $model_log->add();

		


		/**
		 * 判断权限的逻辑是
		 * 取出当前登录用户的所属角色，
		 * 在通过角色 取出 所属 权限关系
		 * 在权限表中取出所有的权限链接
		 * 判断当前访问的链接 是否在 所拥有的权限列表中
		 */

		//判断当前访问链接是否在角色拥有的权限列表中
		if( !$this->checkPrivilege( $action ) ){
			$this->redirect( "Public/forbidden" );
			return false;
		}

	}

	//检查是否有访问指定链接的权限
	public function checkPrivilege( $url ){
		//如果是超级管理员，不需要权限判断
		if( $this->current_user && $this->current_user['is_admin'] ){
		 	return true;
		}
		
		//不需要进行权限判断的页面
		if( in_array( $url, $this->ignore_urls ) ) {
			return true;
		}

		return in_array( $url, $this->getRolePrivilege( ) );
	}

	/*
	* 获取某用户的所有权限
	* 取出指定用户的所属角色，
	* 在通过角色 取出 所属 权限关系
	* 在权限表中取出所有的权限链接
	*/
	public function getRolePrivilege($uid = 0){
		if (!$uid) $uid = $GLOBALS['info']['id'];

		//取出对应用户所属的角色
		$role_ids = M('user_role')->where(['uid'=>$uid])->getField('role_id', true);
		
		if ( $role_ids ) {
			foreach ($role_ids as $k1 => $v1) {
				//取出对应角色所属的权限
				$access_ids = M('role_access')->where(['role_id'=>$v1])->getField('access_id', true);

				//取出对应权限的可以访问链接
				foreach ($access_ids as $k2 => $v2) {
					$link = M('access')->where(['id'=>$v2])->getField('url', true);
				}
				
				if( $link ){
					foreach( $link as $_item  ){
						$tmp_urls = json_decode(  $_item );
						$this->privilege_urls = array_merge( $this->privilege_urls, $tmp_urls );
					}
				}

			}
			return $this->privilege_urls ;

		}
	}


	//验证登陆是否有效，返回true 后者false
    protected function checkLoginStatus(){
    	//取出cookie的name与$auth_cookie_name对比
    	$auth_cookie = cookie('rbac_token');
    	// var_dump($auth_cookie);
		if(!$auth_cookie) return false;

		//使用特殊字符分割cookie参数
		list($auth_token,$uid) = explode("#",$auth_cookie['value']);

		if(!$auth_token || !$uid) return false; 

		if( $uid && preg_match("/^\d+$/",$uid) ){
			$map['id'] = $uid;
			$user_info = M('user')->where($map)->find();
			if(!$user_info) return false;
			
			//校验码
			if($auth_token != $this->createAuthToken($user_info['id'],$user_info['name'],$user_info['email'],$_SERVER['HTTP_USER_AGENT'])){
				return false;
			}
			
			$this->current_user = $user_info;
			
			$current_user = $GLOBALS;
			$current_user['info'] = $user_info;

			return true;
		}

		return false;
	
    }

    //设置登陆状态cookie
    //保存用户登录状态,cookie必须加密保存，否则易被篡改。
    public function createLoginStatus($user_info){
   		$user_auth_token = $this->createAuthToken($user_info['id'].$user_info['name'].$user_info['email'].$_SERVER['HTTP_USER_AGENT']);
   		$user_cookie = array(
   			'name'=>$this->auth_cookie_name,
   			'value'=>$user_auth_token."#".$user_info['id'],
   		);
   		
   		cookie('token',$user_cookie,array('expire'=>7200,'prefix'=>'rbac_'));
    }

    //使用用户信息生成加密校验码
    public function createAuthToken($uid, $name, $email, $user_agent){
		return md5($uid.$name.$email.$user_agent);
    }



	// /*
	//  * 封装json返回值，主要用于js ajax 和 后端交互返回格式
	//  * data:数据区 数组
	//  * msg: 此次操作简单提示信息
	//  * code: 状态码 200 表示成功，http 请求成功 状态码也是200
	//  */
	// public function renderJSON($data=array(), $msg ="ok", $code = 200){
	// 	header('Content-type: application/json');//设置头部内容格式
	// 	echo json_encode(array(
	// 		"code" => $code,
	// 		"msg"   =>  $msg,
	// 		"data"  =>  $data,
	// 		"req_id" =>  uniqid(),	
	// 	));
	// }

}