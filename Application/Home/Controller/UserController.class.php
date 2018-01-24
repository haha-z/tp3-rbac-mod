<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class UserController extends BaseController {

    //角色列表
    public function index(){

		$user  = M('user'); // 实例化User对象
		$count = $user->count();// 查询满足要求的总记录数
		$page  = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list  = $user->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }

    /* 
    * 	添加角色or编辑角色
    *	get展示页面
    *	psot添加角色
    */
    public function set(){

		//展示
		if (IS_GET) {
			
			$uid = I('get.uid',0);
			
			if ($uid) {
				$user = D('User')->getRoleById($uid);
				$this->user = $user;
			}

			//取出所有角色
			$role_list = D('Role')->getDataList();
			$this->role = $role_list;
			
			//取出已经分配的角色
			$select_role_ids = M('user_role')->where([ 'uid' => $uid ])->getField('role_id', true);
			$this->select_role_ids = $select_role_ids;
		}

    	//添加 
    	if(IS_AJAX) {
    		
    		$role = D('User');//实例化表模型
			$user = $role->create();//创建对象
			
			if (!$user){
			    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    	$data =array(
					'status' => 0,
					'msg'    => $role->getError(),
				);

			}else{
 				
			    // 验证通过 可以进行其他数据操作

			    $user['role_ids'] = I('post.role_ids');//接受角色id


			    //接受数据id，用来判断是新增还是修改
			    if ($user['id']) {
					
			    	$res = $role->save($user);//修改

			    	if ($res===false) {
				    	$data =array(
							'status' =>0,
							'msg'=>'更新失败！',
						);
					}else{
					   $data = array(
							'status' =>1,
							'msg'=>'更新成功！',
						);

			 	 	//更新成功，需要执行更新角色权限表
				   	/**
					 * 找出删除的角色
					 * 假如已有的角色集合是A，界面传递过得角色集合是B
					 * 角色集合A当中的某个角色不在角色集合B当中，就应该删除
					 * array_diff();计算补集
					 */

					$user_role_list = M('user_role')->where(array('uid'=>$user['id']))->getField('role_id', true);

					$related_role_ids = [];//定义空数组
					if( $user_role_list ){
						foreach( $user_role_list as $k => $val ){
							$related_role_ids[] = $val;
							if( !in_array( $val, $user['role_ids'] ) ){
								M("user_role")->where(array('role_id'=>$val,'uid'=>$user['id']))->delete();
							}
						}
					}

					/**
					 * 找出添加的角色
					 * 假如已有的角色集合是A，界面传递过得角色集合是B
					 * 角色集合B当中的某个角色不在角色集合A当中，就应该添加
					 */
					if ( $user['role_ids'] ){
						foreach( $user['role_ids'] as $k => $_role_id ){
							if( !in_array( $_role_id, $related_role_ids ) ){
								$model_user_role = M('User_role');
								$model_user_role->uid = $user['id'];
								$model_user_role->role_id = $_role_id;
								$model_user_role->create_time = time();
								$model_user_role->add();
							}
						}
					}


					}

			    }else{

			    	$res = $role->add();//新增
			    	if ($res===false) {
						$data = array(
							'status' =>0,
							'msg'=>'写入失败！',
						);
					}else{

						$data = array(
							'status' =>1,
							'msg'=>'写入成功！',
						);

						// $user['role_ids'] = I('post.role_ids');

						if ($user['role_ids']) {
							foreach ($user['role_ids'] as $key => $value) {
								$model = D('UserRole');
								$role_data['uid'] = $res;
								$role_data['role_id'] = $value;
								$model->add($role_data);
							}
						}
					}
					
			    }

			}

		    $this->ajaxReturn($data);
		}

    	$this->display();
    }


    public function delete(){
    	if (IS_AJAX) {
    		$role = M('User');
    		$map = I('post.id');
    		$res = $role->delete($map);
    		if ($res ) {
			 	$data = array(
			    	'status' => 1,
			    	'msg' => '删除成功！',
		    	);

			 	$role_id_del = M('user_role')->where(array('uid'=>$map))->delete();

    		}else{
			 	$data = array(
			    	'status' => 0,
			    	'msg' => '删除失败！',
		    	);
    		}
    		$this->ajaxReturn($data);
    	}
    }



}