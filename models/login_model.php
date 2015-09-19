<?php

class Login_model extends Model{

    function __construct() {
        parent::__construct();
    }

    public function run(){
        try{
            $this->form = new Form();
        } catch (Exception $e) {
            header( "location: " . $_SERVER['HTTP_REFERER'] );
            Session::set( 'msg_error', array($e->getMessage()) );
            exit();
        }
        
        $login      = $this->form->validate( 'login', 'Login Field', 'string', 'required' );
        $password   = $this->form->validate( 'password', 'Password Field', 'string', 'required' );
        $token      = $this->form->validate( 'token', 'Token', 'string' );

        Session::init();
        if( $token !== Session::get( 'token' ) ){
            header( 'location:' . URL . 'login/');
            exit;
        }
        
        if( $login == "" ){
            Session::set( 'msg_error', 'The login field is required.' );
        }
        
        if( $password == "" ){
            Session::set( 'msg_error', 'The password field is required.' );
        }

        if( $login == "" || $password == "" ){
            header( 'location:' . URL . 'login/' );
            exit;
        }

        
        $data = array(
            ":login" => $login,
            ":password" => Hash::create( 'sha256', $password, HASH_PASSWORD_KEY )
        );
        
        $users = $this->db->select( 'SELECT user_id, user_type FROM users WHERE ' .
                                        "user_login=:login AND user_password=:password", 
                                    $data );

        if( COUNT( $users ) !== 1 ){
            Session::set( 'msg_error', 'Invalid username or password.' );
            header( 'location:' . URL . 'login/' );
        } else {
            header( 'location:' . URL . 'users/' );            
            Session::init();
            Session::set( 'user_id', $users[0]['user_id'] );        
            Session::set( 'user_type', $users[0]['user_type'] );        
        }        
    }
}

