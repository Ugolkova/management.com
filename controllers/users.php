<?php

class Users extends Controller {
    private $_userTypes = array("lm", "pm");
    
    function __construct() {
        parent::__construct();
    }
    
    function index(){
        $this->full_list();
    }
    
    /**
     * This function we use to get list of users according to two parameters -
     * page and list type (LM list, PM list)
     * get_list
     * get_list/p12
     * get_list/pm/p45
     * get_list/lm/p2
     * @param type $param1
     * @param type $param2
     */
    public function get_list( $param1 = null, $param2 = null ){
        $page = 1;
        $userType = null;

        if( !is_null($param1) ){
            if(preg_match('/^p(\d+)$/', $param1, $matches) === 1){
                $page = (int)$matches[1];
            } else {
                $param1 = strtolower($param1);
                if(in_array($param1, $this->_userTypes)){
                    $userType = $param1;
                    if(preg_match('/^p(\d+)$/', $param2, $matches) === 1){
                        $page  = (int)$matches[1];
                    }
                }    
            }
        }
        
        $usersArr = $this->model->getList($page, $userType);
        
        $this->view->users = $usersArr;
        $this->view->render("users/list");
    }
    
    public function add(){
        if(isset($_POST['submit'])){
            if( parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== HOST ){
                header('location: ' . URL . 'users');
                exit;
            } else {
                echo $this->model->add();
            }
        } else {
            $this->view->render("users/add");
        }    
    }
    
    
}

