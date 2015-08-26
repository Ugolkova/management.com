<?php

class View {
    public $title = SITE_NAME;
    
    function __construct() {
        // ...
    }
    
    public function setTitle($prefix){
        $this->title = $prefix . " | " . $this->title;
    }
    
    public function render($name){
        require "views/header.php";
        require "views/" . $name . ".php";
        require "views/footer.php";
    }
}

