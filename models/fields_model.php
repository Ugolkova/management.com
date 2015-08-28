<?php

class Fields_model extends Model {

    function __construct() {
        parent::__construct();
        @session_start();
        $_SESSION['lm_id'] = 2;
        $_SESSION['pm_id'] = 3;
    }

    public function add(){
        
    }
}

