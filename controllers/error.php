<?php

/**
 * Error Class
 * 
 * use to show 404 Error
 */

class Error extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index(){
        $this->view->render("error/index");
    }
}

// PATH: controllers/error.php