<?php

class Users extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index(){
        $this->full_list();
    }
    
    function full_list($page = null){
        $this->view->page = $page;
        $this->view->render("users/list");
    }
    
}

