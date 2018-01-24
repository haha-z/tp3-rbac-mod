<?php
namespace Home\Model;
use Think\Model;
class ActionLogModel extends Model {

    protected $trueTableName = 'app_access_log'; 
	
    // protected $_auto = array(
    //     array('created_time','time',1,'function'),         
    //     array('ua','getUA',1,'callback'), 
    //     array('ip','getIP',1,'callback'), 
    //     array('target_url','getURL',1,'callback'), 
    // );


    // function getIP(){
    //     return $_SERVER['REMOTE_ADDR']?$_SERVER['REMOTE_ADDR']:'';
    // }

    // function getUA(){
    //     return $_SERVER['HTTP_USER_AGENT']?$_SERVER['HTTP_USER_AGENT']:'';
    // }

    // function getURL(){
    //     return $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:'';
    // }
}
