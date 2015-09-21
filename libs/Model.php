<?php

class Model {
    public $searchKey = '';
    public $searchAutocomplete = FALSE;
    
    function __construct() {
        $this->db       = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $this->request  = new Request();
        
        $this->searchKey = $this->request->validate( 'key', 'string'); 
        $this->searchAutocomplete = $this->request->validate( 'autocomplete', 'boolean');
    }

}

