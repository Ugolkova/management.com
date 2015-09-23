<?php

/**
 * Auth Class
 * 
 */
class Auth {
    
    /**
     * Check if user is logged in
     * If he isn't redirect him to Login Page
     */
    public static function handleLogin()
    {
        Session::init();
        $logged = Session::get('user_id');
        if ($logged === FALSE) {
            $token = Session::get('token');
            
            $countEntriesOnPage = COUNT_ENTRIES_ON_PAGE;
            if( Session::get('COUNT_ENTRIES_ON_PAGE') ){
                $countEntriesOnPage = Session::get('COUNT_ENTRIES_ON_PAGE');
            }    
            
            Session::destroy();
            
            Session::init();
            Session::set( 'token', $token );
            Session::set( 'COUNT_ENTRIES_ON_PAGE', $countEntriesOnPage );
            
            header('location: ' . URL . 'login/');
            exit;
        }
    }
}
