<?php

class Model {

    function __construct() {
        $this->db       = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        //$this->validate = new Validate();
    }

    function add(){
        echo "Model<br />";
        if($this->request->post('submit')){
            
        } else {
            
        }
    }
}

