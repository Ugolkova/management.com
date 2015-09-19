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
        
        if( $this->searchKey !== '' ){
            $where .= " AND t1.user_name LIKE '%" . $this->searchKey . "%'";
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
        
        if( $fields !== '' ){
            $fields = ', ' . $fields;
        }

        $user = $this->db->select( 'SELECT u.*' . $fields .' FROM users as u 
                                    LEFT JOIN user_fields uf 
                                        ON uf.user_id = u.user_id AND uf.owner_id = :owner_id
                                    WHERE u.user_id=:user_id', 
                                    array( 'owner_id' => Session::get( 'user_id' ),
                                            'user_id' => $user_id) );
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
        $user_id = NULL;
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

        if( $user_id !== NULL ){
            $onDuplUpdate = '';
            if( $user['owner_id'] == Session::get('user_id') && 
                    !$this->db->update( 'users', 
                                        $u_table_data, 
                                        'user_id=' . $user_id ) ){
                throw new Exception("Wrong data for user #" . $user_id);
            }

            $uf_table_data_dupl = $uf_table_data;
            foreach( $uf_table_data_dupl as $key=>$value ){
                $onDuplUpdate .= "$key = '$value', ";
            }
            $onDuplUpdate = rtrim($onDuplUpdate, ", "); 

            $uf_table_data['user_id'] = $user_id;
            $uf_table_data['owner_id'] = Session::get( 'user_id' );

            
            $uf_table_data['user_id'] = $user_id;
            $uf_table_data['owner_id'] = Session::get( 'user_id' );

            $this->db->insert( 'user_fields', $uf_table_data, $onDuplUpdate );  

            
        } else {
            $user_id = $this->db->insert( 'users', $u_table_data );
            if( !$user_id ){
                throw new Exception( "Can't add the user" );
            }
            
            $uf_table_data['user_id'] = $user_id;
            $uf_table_data['owner_id'] = Session::get( 'user_id' );
            
            $this->db->insert( 'user_fields', $uf_table_data );
        }  
        
        return $user_id;
    }
    
    public function delete( $user_id ){
        $this->db->delete( 'users', 'user_id=' . $user_id );
    }    
}

