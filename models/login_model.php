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

        $login      = $this->form->validate( 'login', 'Login Field', 
                                             'string', 'required' );
        $password   = $this->form->validate( 'password', 'Password Field', 
                                             'string', 'required' );
        $token      = $this->form->validate( 'token', 'Token', 'string' );
        
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
        
        $users = $this->db->select( 'SELECT user_id, user_name, user_type, user_is_lm, ' .
                                        'user_is_pm FROM users WHERE ' .
                                        "user_login=:login AND user_password=:password", 
                                    $data );
        if( COUNT( $users ) !== 1 ){
            Session::set( 'msg_error', 'Invalid username or password.' );
            Session::set( 'token', $token );
            header( 'location:' . URL . 'login/' );
        } else {
            Session::init();
            Session::set( 'user_id', $users[0]['user_id'] );        
            Session::set( 'user_name', $users[0]['user_name'] );        
            Session::set( 'user_type', $users[0]['user_type'] );    
            
            $lm_id = 0;
            $pm_id = 0;
            
            if( $users[0]['user_is_lm'] ){
                $lm_id = $users[0]['user_id'];
            }

            if( $users[0]['user_is_pm'] ){
                $pm_id = $users[0]['user_id'];
            }

            Session::set( 'lm_id', $lm_id );                        
            Session::set( 'pm_id', $pm_id );                                    

            Session::delete( 'msg_error' );
            Session::delete( 'msg_success' );
            
            header( 'location:' . URL . 'users/' );            
            
        }        
    }
}

