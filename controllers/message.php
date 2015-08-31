<?php

class Message extends Controller {

    function __construct() {
        parent::__construct();
    }

    function remove(){
        Session::set( 'message', array() );
    }
}

