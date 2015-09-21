<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * Standard method
     * 
     * @desc redirect user on Get Users page
     */
    function index(){
        header('location: ' . URL . 'users/get_entries/');        
    }
        
    /**
     * Remove session messages
     * 
     * @desc Use this method for AJAX-calls
     */
    public function removeMsg(){
        Session::init();
        
        Session::delete( 'msg_success' );
        Session::delete( 'msg_error' );
    }
    
    /**
     * Change count entries on page 
     * 
     * @desc Use this method for AJAX-calls
     */
    public function changeEntriesCount(){
        Session::init();
        $count = (int)$_GET['count'];
        
        if( in_array( $count, $GLOBALS['COUNTS_ENTRIES_AVAILABLE'] ) ){
            Session::set( 'COUNT_ENTRIES_ON_PAGE', $count );
        }
    }
    
    /**
     * Log out
     * 
     * @desc Destroys session and redirect user on login page
     */
    function logout()
    {
        Session::destroy();
        header('location: ' . URL .  'login/');
        exit;
    }    
}

