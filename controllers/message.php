<?php

class Message extends Controller {

    function __construct() {
        parent::__construct();
    }

    function remove(){
        Session::delete( 'msg_success' );
        Session::delete( 'msg_error' );
    }
}

