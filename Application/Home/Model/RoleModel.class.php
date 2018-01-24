<?php
namespace Home\Model;
use Think\Model;
class RoleModel extends Model {

	//自动验证
	protected $_validate = array(
		// array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间])
 		array('name','require','用户名必须！'), // 用户名必须
     	array('name','','名称已经存在1！',1,'unique',1), // 验证用户名是否已经存在
     	
   	);

	protected $_auto = array ( 
        array('status','1'),  // 新增的时候把status字段设置为1
        array('update_time','time',2,'function'), 
        array('create_time','time',1,'function'), 
    );

	public function getDataList(){
		$list = M('role')->order('id desc')->select();
		return $list;
	}
	
	public function getRoleById($id){
		$map['id'] = $id ;
		$data = M('role')->where($map)->find();
		return $data;
	}


}
