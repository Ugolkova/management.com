<?php

class Fields extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function add(){
        $this->view->setTitle("Add Field");
        $this->view->render("fields/add");
    }
}
