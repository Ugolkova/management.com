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
    
    public function getEntry( $user_id ){
        $owner_fields = $this->_getOwnerFields();
        $fields = '';
        foreach($owner_fields as $field){
            $fields .= 'uf.' . $field['name'] . ',';
        }
        $fields = rtrim( $fields, ',' );
        
        $user = $this->db->select( 'SELECT u.*, ' . $fields .' FROM users as u 
                                    INNER JOIN user_fields uf ON uf.user_id = u.user_id
                                    WHERE u.user_id=:user_id 
                                    AND u.owner_id=:owner_id AND uf.owner_id = u.owner_id', 
                                    array('user_id' => $user_id,
                                          'owner_id' => Session::get('user_id')   ) );
        
        if( !$user ){
            return FALSE;
        }
            
        return $user;
    }
    
    private function _getOwnerFields(){
        $fields = $this->db->select( 'SELECT CONCAT("field_", field_id) as name FROM fields WHERE owner_id=:owner_id', 
                                     array('owner_id' => Session::get('user_id') ) );
        return $fields;
    }
    
    public function getFieldsData(){
        $fields = $this->db->select( 'SELECT * FROM fields WHERE owner_id=:owner_id', 
                                     array('owner_id' => Session::get('user_id') ) );  
        return $fields;
    }
    
    public function save( $user ){
        if( isset( $user['user_id'] ) ){
            $user_id = $user['user_id'];
            unset( $user['user_id'] );
        }

        // Data Array for table 'users'
        $u_table_data = array();
        // Data Array for table 'user_fields'
        $uf_table_data = array();
        
        $user_keys = array_keys( $user );
        foreach( $user_keys as $key ){
            if( preg_match('/^field_\d+$/', $key) ){
                $uf_table_data[$key] = $user[$key];
            } else {
                $u_table_data[$key] = $user[$key];
            }
        }

       if( $user_id ){
            if( !$this->db->update( 'users', $u_table_data, 'user_id=' . $user_id )){
                throw new Exception("Wrong data for user #" . $user_id);
            }
            if( !$this->db->update( 'user_fields', 
                                    $uf_table_data, 
                                    'user_id=' . $user_id . ' AND owner_id=' . 
                                        Session::get('user_id')  )){
                throw new Exception("Wrong data for user fields #" . $user_id);
            } 
        } else {
            $user_id = $this->db->insert( 'users', $user );
            if( !$user_id ){
                throw new Exception( "Can't add the user" );
            }            
        }  
        
        return $user_id;
    }
    
}

