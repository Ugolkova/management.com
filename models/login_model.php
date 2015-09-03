<?php

class Login_model extends Model{

    function __construct() {
        parent::__construct();
    }

    public function run(){
        $login      = $this->request->post( "login" );
        $password   = $this->request->post( "password" );
        $token      = $this->request->post( "token" );

        if( $token !== Session::get( "token" )){
            header( "location:" . URL . "login/");
            exit;
        }
        
        if( $login == "" ){
            Session::set( "message", array( "error" => "The login field is required." ) );
        }
        
        if( $password == "" ){
            Session::set( "message", array( "error" => "The password field is required." ) );
        }

        if( $login == "" || $password == "" ){
            header( "location:" . URL . "login/" );
            exit;
        }

        
        $data = array(
            ":login" => $login,
            ":password" => Hash::create( "sha256", $password, HASH_PASSWORD_KEY )
        );
        
        $usersCount = (int)$this->db->select( "SELECT COUNT(*) as users_count FROM users " .
            "WHERE user_login=:login AND user_password=:password", 
            $data, PDO::FETCH_NUM )[0][0];

        if( $usersCount !== 1 ){
            Session::set( "message", array("error" => "Invalid username or password.") );
            header("location:" . URL . "login/");
        } else {
            Session::set( "message", array() );
            header("location:" . URL . "users/");            
        }
    }
}

