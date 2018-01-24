<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class AccessController extends BaseController {

    //角色列表
    public function index(){
    	$user  = M('Access'); // 实例化User对象
    	$count = $user->count();// 查询满足要求的总记录数
    	$page  = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list  = $user->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
      
      //json个格式转换
      foreach ($list as $k => $v) {
        $list[$k]['url'] = implode("<br/>", json_decode($v['url']));
      }

      //用换行把多个url分开


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

      //展示单一用户
      if (IS_GET) {
        $uid = I('get.uid',0);

        if ($uid) {
          $user = D('Access')->getRoleById($uid);
          $user['url'] = implode("\r\n", json_decode($user['url']));

          $this->user = $user;
        }
      }

    	if(IS_AJAX) {
    		//添加
    		$access = D('Access');
    		$url = I('post.url');
        $urls = explode("\n",$url);
    		$user = $access->create();

			if (!$user){
			    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    	$data =array(
					'status' =>0,
					'msg'=>$access->getError(),
				);

			}else{
			    // 验证通过 可以进行其他数据操作
			    if ($user['id']) {
            $user['url'] = json_encode( $urls );//json格式保存
			    	$res = $access->save($user);

			    	if ($res===false) {
				    	$data = array(
							'status' =>0,
							'msg'=>'更新失败！',
						);
					}else{
					    $data = array(
							'status' =>1,
							'msg'=>'更新成功！',
						);
					}

			    }else{
            $user['url'] = json_encode( $urls );//json格式保存
			    	$res = $access->add($user);
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
					}

			    }
			}

		    $this->ajaxReturn($data);
		}



    	$this->display();
    }


    public function delete(){
    	if (IS_AJAX) {
    		$role = M('Access');
    		$map['id'] = I('post.id');
    		$res = $role->where($map)->delete();
    		if ($res===false ) {
            $json = array(
  			    	'status' => 0,
  			    	'msg' => '删除失败！',
  		    	);
    		}else{
            $json = array(
              'status' => 1,
              'msg' => '删除成功！',
            );
    		}
    		$this->ajaxReturn($json);
    	}
    }



}
