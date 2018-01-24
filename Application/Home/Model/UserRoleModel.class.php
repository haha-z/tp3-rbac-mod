<?php
namespace Home\Model;
use Think\Model;
class UserRoleModel extends Model {
	protected $tableName = 'user_role'; 
	
	protected $_auto = array ( 
        array('create_time','time',1,'function'), 
    );



}

