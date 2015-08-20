<?php

class Users extends Controller {
    private $_userTypes = array("lm", "pm");
    
    function __construct() {
        parent::__construct();
    }
    
    function index(){
        $this->full_list();
    }
    
    function get_list( $param1 = null, $param2 = null, $param3 = null ){
        $page = 1;
        $userType = null;

        if( !is_null($param1) ){
            if(preg_match('/^p(\d+)$/', $param1, $matches) === 1){
                $page       = (int)$matches[1];
                $userType   = $param2;
            } else {
                $userType   = $param1;
            }
            
            if( !in_array($userType, $this->_userTypes) ){
                $userType   = null;
            }            
        }
        
        $usersArr = $this->model->getList($page, $userType);
        
        $this->view->users = $usersArr;
        $this->view->render("users/list");
    }
    
}

