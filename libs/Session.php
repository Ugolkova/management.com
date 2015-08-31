<?php

class Session{
    public static function init(){
        @session_start();
        
        if( !isset( $_SESSION['message'] ) ){
            self::set( 'message', array() );
        } 
    }
    
    public static function destroy(){
        session_destroy();
    }
    
    public static function set( $name, $value ){
        $_SESSION[$name] = $value;
    }
    
    public static function get( $name ){
        return $_SESSION[$name];
    }
}

