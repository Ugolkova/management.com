<?php

class Users_model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function full_list(){
        //$this->db->select("SELECT ")
    }
    
    public function get_list(){
        $sql = "SELECT t1.user_id, t1.user_name, t2.lm_id, t3.user_name as lm_name, t4.pm_id, t5.user_name as pm_name
        FROM users as t1
        LEFT JOIN lm_users as t2 ON t2.user_id = t1.user_id 
        LEFT JOIN users as t3 ON t3.user_id = t2.lm_id
        LEFT JOIN pm_users as t4 ON t4.user_id = t1.user_id 
        LEFT JOIN users as t5 ON t5.user_id = t4.pm_id";
    }
}

