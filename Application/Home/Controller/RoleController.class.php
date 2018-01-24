<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class RoleController extends BaseController {

    //角色列表
    public function index(){
  		$user  = M('Role'); // 实例化User对象
  		$count = $user->count();// 查询满足要求的总记录数
  		$page  = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
  		$show  = $page->show();// 分页显示输出
  		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
  		$list  = $user->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
  		$this->assign('list',$list);// 赋值数据集
  		$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }

    public function access(){
     
      if (IS_GET) {
        
        $uid = I('get.uid',0);
        
        if (!$uid)  return $this->redirect("Home/role/index/");
        
        $user = D('Role')->getRoleById($uid);

        if (!$user)  return $this->redirect("Home/role/index/");
        
        $this->user = $user;
      
        //取出所有权限
        $access_list = M('access')->where(array('status'=>1))->order('id desc')->select();
        $this->access_list = $access_list;

        //取出已分配的权限
        $select_access_ids = M('role_access')->where([ 'role_id' => $uid ])->getField('access_id', true);
        $this->select_access_ids = $select_access_ids;

      }

      //实现保存选中权限的逻辑
      if (IS_AJAX) {
        
        $id = I('post.id',0);
       
        $access_ids = I('post.access_ids', 0);
        
        //如果是空的，需要组合成数组
        //使用arraf_diff比较时，需要比较双方都是数组。
        if ($access_ids==0) $access_ids = ['0'];

        if( !$id ){
          $data = [
            'status'=> 0,
            'msg' => '该角色不存在！',
          ];
          $this->ajaxReturn($data);
        }

        $info = M('role')->where([ 'id' => $id ])->find();
        
        if( !$info ){
          $data = [
            'status'=> 0,
            'msg' => '该角色不存在！',
          ];
          $this->ajaxReturn($data);
        }
        
        $role_access_list =M('role_access')->where([ 'role_id' => $id ])->select();
        $assign_access_ids = array_column( $role_access_list, 'access_id' );

        $delete_access_ids = array_diff( $assign_access_ids, $access_ids );
        if( $delete_access_ids ){
          foreach ($delete_access_ids as $k => $v) {
            M('role_access')->where([ 'role_id' => $id, 'access_id' => $v ])->delete();
          }
        }

        $new_access_ids = array_diff( $access_ids, $assign_access_ids );
        if( $new_access_ids ){
          foreach( $new_access_ids as $_access_id  ){
            $tmp_model_role_access = M('role_access');
            $tmp_model_role_access->role_id = $id;
            $tmp_model_role_access->access_id = $_access_id;
            $tmp_model_role_access->create_time = time();
            $tmp_model_role_access->add( );
          }
        }

        $data =  [
          'status'=>1,
          'msg'=>"写入成功！",
        ];

        $this->ajaxReturn($data);

      }

      $this->display();
    }


    /*
    * 	添加角色or编辑角色
    *	get展示页面
    *	psot添加角色
    */
    public function set(){
    	if(IS_AJAX) {
    		//添加
    		$role = D('Role');
    		$user = $role->create();

        if (!$user){
			    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    	$data['status']= 0;
    			$data['msg'] = $role->getError();

        }else{

          // 验证通过 可以进行其他数据操作

			    if ($user['id']) {

			    	$res = $role->save($user);

            if ($res===false) {

              $data['status']= 0;
              $data['msg'] = '更新失败！';
            }else{

              $data['status']= 1;
              $data['msg'] = '更新成功！';
            }

			    }else{
            $res = $role->add();

            if ($res===false) {

              $data['status']= 0;
              $data['msg'] = '写入失败！';
            }else{

              $data['status']= 1;
              $data['msg'] = '写入成功！';
            }


			    }


			  }
	      $this->ajaxReturn($data);
		  }

  		//展示单一用户
  		if (IS_GET) {
  			$uid = I('get.uid',0);

  			if ($uid) {
  				$user = D('Role')->getRoleById($uid);
  				$this->user = $user;
  			}
  		}

    	$this->display();
    }

    public function delete(){
    	if (IS_AJAX) {
    		$role = M('role');
    		$map['id'] = I('post.id');
    		$res = $role->where($map)->delete();
    		if ($res ) {
			 	$json = array(
			    	'status' => 1,
			    	'msg' => '删除成功！',
		    	);
    		}else{
			 	$json = array(
			    	'status' => 0,
			    	'msg' => '删除失败！',
		    	);
    		}
    		$this->ajaxReturn($json,'json');
    	}
    }



}
