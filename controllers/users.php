<?php

class Users extends Controller {

    function __construct() {
        parent::__construct();
    }

    function addUser(){
        $this->model->addUser();
    }
}

