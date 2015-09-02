<?php

class Users_model extends Model {
    private $_usersCount = 0;
    
    function __construct() {
        parent::__construct();
        @session_start();
        $_SESSION['lm_id'] = 23;
        $_SESSION['pm_id'] = 3;
    }

    /**
     * Get list of users according page, userType and userId
     * 
     * @param int $page
     * @param str $userType 
     * @return array
     */
    public function getList($page, $userType){        
        $where = "WHERE 1";
        if( !is_null($userType) ){
            switch( $userType ){
                case "lm":
                    $where .= " AND t2.lm_id = " . $_SESSION['lm_id'];
                    break;
                case "pm":
                    $where .= " AND t4.pm_id = " . $_SESSION['pm_id'];
            }
        }
        
        $limit = "LIMIT ";
        if($page > 1){
            $limit .= (($page - 1) * COUNT_ENTRIES_ON_PAGE) . ", " 
                    . COUNT_ENTRIES_ON_PAGE;
        } else {
            $limit .= COUNT_ENTRIES_ON_PAGE;
        }
                
        $sql = "SELECT t1.user_id, t1.user_name, t1.user_email, t1.user_skype, 
            t6.avatar_path, t2.lm_id, t3.user_name as lm_name, 
            t4.pm_id, t5.user_name as pm_name FROM users as t1
            LEFT JOIN lm_users as t2 ON t2.user_id = t1.user_id 
            LEFT JOIN users as t3 ON t3.user_id = t2.lm_id
            LEFT JOIN pm_users as t4 ON t4.user_id = t1.user_id 
            LEFT JOIN users as t5 ON t5.user_id = t4.pm_id
            LEFT JOIN avatars as t6 ON t6.user_id = t1.user_id $where $limit";

        $usersArr = $this->db->select( $sql );     
        
        $sql = "SELECT COUNT(*) as users_count FROM users as t1
            LEFT JOIN lm_users as t2 ON t2.user_id = t1.user_id 
            LEFT JOIN users as t3 ON t3.user_id = t2.lm_id
            LEFT JOIN pm_users as t4 ON t4.user_id = t1.user_id 
            LEFT JOIN users as t5 ON t5.user_id = t4.pm_id
            LEFT JOIN avatars as t6 ON t6.user_id = t1.user_id $where";
        
        $this->_usersCount = $this->db->select( $sql, [], PDO::FETCH_NUM )[0][0];
        
        return $usersArr;
    }
    
    public function getRowsCount(){
        return $this->_usersCount;
    }
    
    public function add(){
        return $this->request->post('user_name', 'string');
    }
}

