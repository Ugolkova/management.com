<?php

interface CRUD {
    
    public function index();

    public function add();
    
    public function edit( $id = NULL );
    
    public function get_entries( $param1 = null, $param2 = null );
    
}

