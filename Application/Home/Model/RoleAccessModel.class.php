<?php
namespace Home\Model;
use Think\Model;
class RoleAccessModel extends Model {
	protected $tableName = 'role_access'; 
	
	protected $_auto = array ( 
        array('create_time','time',1,'function'), 
    );

}

