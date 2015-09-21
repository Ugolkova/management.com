<?php

class Login extends Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Standard method
     * 
     * @desc For security reasons we use token
     */
    function index(){
        Session::init();
        if( !Session::get( 'token' ) ){
            Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );            
        }
        $this->view->token = Session::get( 'token' );
        $this->view->render("login/index");
    }
    
    /**
     * Run Login
     * 
     * @desc If user types valid data application will be available for him  
     */
    function run(){
        $this->model->run();
    }
        
}