<?php

class Login extends Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function index(){
        Session::init();
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );
        $this->view->render("login/index");
    }
    
    function run(){
        $this->model->run();
    }
        
}