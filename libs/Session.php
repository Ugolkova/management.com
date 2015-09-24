<?php

class Session{
    /**
     * Static function to initialize session
     */
    public static function init(){
        @session_start();
        
        if( !isset( $_SESSION['message'] ) ){
            self::set( 'message', array() );
        } 
    }
    
    /**
     * 
     * @return String
     */
    public function __toString() {
        return http_build_query( $_SESSION );
    }
    
    /**
     * Destroy session
     */
    public static function destroy(){
        session_destroy();
    }
    
    /**
     * Set data to $_SESSION[]
     * 
     * @param String $name
     * @param String|Integer|Boolean $value
     */
    public static function set( $name, $value ){
        if( $name !== 'token' || 
            ( $name === 'token' && (!isset( $_SESSION['token'] ) || $_SESSION['token'] == '' ) ) ){
            $_SESSION[$name] = $value;
        }    
    }

    /**
     * Unset variable from $_SESSION[]
     * 
     * @param String $name
     */
    public static function delete( $name ){
        if( isset( $_SESSION[$name] ) ){
            unset( $_SESSION[$name] );
        }
    }
    
    /**
     * Get variable value
     * 
     * @param String $name
     * @return Boolean
     */
    public static function get( $name ){
        if( isset( $_SESSION[$name] ) ){
            return $_SESSION[$name];
        } else {
            return FALSE;
        }  
    }
}

