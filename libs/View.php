<?php

class View {
    public $title = SITE_NAME;
    public $header = '';
    
    private $_errorFields = array();
    
    function __construct() {
        // ...
    }
    
    public function setTitle( $prefix ){
        $this->title = $prefix . " | " . $this->title;
    }

    public function setHeader( $header ){
        $this->title = $header;
    }    
    
    public function setErrorFields( $fields ){
        $this->_errorFields = $fields;
    }
    
    public function isErrorField( $name ){
        if(in_array($name, $this->_errorFields) ){
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function render($name){
        require "views/header.php";
        require "views/" . $name . ".php";
        require "views/footer.php";
    }
}

