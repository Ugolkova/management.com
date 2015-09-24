<?php

class Users_model extends Model {
    private $_usersCount = 0;
    
    function __construct() {
        parent::__construct();
    }

    /**
     * Get list of users according page, userType and userId
     * 
     * @param integer $page
     * @param string $userType 
     * @return array
     */
    public function getList($page, $userType = null){   
        $where = "WHERE 1";
        if( !is_null($userType) ){
            switch( $userType ){
                case "lm":
                    $where .= " AND t1.user_is_lm = TRUE";
                    
                    break;
                case "pm":
                    $where .= " AND t1.user_is_pm = TRUE";
                    
                    break;
                default:
                    break;
            }
        }
        
        if( $this->searchKey !== '' ){
            $where .= " AND t1.user_name LIKE '%" . $this->searchKey . "%'";
        }
                
        $countEntries = COUNT_ENTRIES_ON_PAGE;
        if( Session::get('COUNT_ENTRIES_ON_PAGE') ){
            $countEntries = Session::get('COUNT_ENTRIES_ON_PAGE');
        }
        
        $limit = "LIMIT ";
        if($page > 1){
            $limit .= (($page - 1) * $countEntries) . ", " 
                    . $countEntries;
        } else {
            $limit .= $countEntries;
        }
                
        $sql = "SELECT t1.user_id, t1.user_name, t1.user_email, t1.user_skype, t1.owner_id,  
            t6.avatar_path, t2.lm_id, t3.user_name as lm_name, 
            t4.pm_id, t5.user_name as pm_name, t7.user_name as owner_name 
            FROM users as t1
            LEFT JOIN lm_users as t2 ON t2.user_id = t1.user_id 
            LEFT JOIN users as t3 ON t3.user_id = t2.lm_id
            LEFT JOIN pm_users as t4 ON t4.user_id = t1.user_id 
            LEFT JOIN users as t5 ON t5.user_id = t4.pm_id 
            LEFT JOIN avatars as t6 ON t6.user_id = t1.user_id 
            LEFT JOIN users as t7 ON t7.user_id = t1.owner_id $where $limit";

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
    
    /**
     * Get rows count
     * 
     * @return string
     */
    public function getRowsCount(){
        return $this->_usersCount;
    }

    /**
     * Get related entries
     * 
     * @param integer $user_id
     * @param string $user_type
     * @return array Users Array
     */
    public function getRelatedEntries( $user_id, $user_type ){
        $sql = 'SELECT u.user_id, u.user_name, u.user_email, u.user_skype FROM ' . 
                $user_type . '_users INNER JOIN users u ON ' . 
                $user_type . '_id = u.user_id WHERE ' . $user_type . 
                '_id=:user_id';
        
        $users_arr = $this->db->select( $sql, ['user_id' => $user_id], PDO::FETCH_ASSOC );
        
        return $users_arr;
    }
    
    /**
     * Get entry
     * 
     * @param integer|NULL $user_id
     * @param string|NULL $user_name
     * @return boolean
     */
    public function getEntry( $user_id = '', $user_name = NULL ){
        $owner_fields = $this->_getOwnerFields();
        $fields = '';
        foreach($owner_fields as $field){
            $fields .= 'uf.' . $field['name'] . ',';
        }
        $fields = rtrim( $fields, ',' );
        
        if( $fields !== '' ){
            $fields = ', ' . $fields;
        }
        
        $bindArr['owner_id'] = Session::get( 'user_id' );
        
        if( $user_id !== NULL && $user_name !== NULL ){
            $where = 'u.user_id=:user_id AND u.user_name=:user_name';
            $bindArr['user_id'] = $user_id;
            $bindArr['user_name'] = $user_name;
        } else if( $user_id !== NULL ){
            $where = 'u.user_id=:user_id';
            $bindArr['user_id'] = $user_id;
        } else if( $user_name !== NULL ){
            $where = 'u.user_name=:user_name';
            $bindArr['user_name'] = $user_name;            
        }
        
        $user = $this->db->select( 'SELECT u.*' . $fields .' FROM users as u 
                                    LEFT JOIN user_fields uf 
                                        ON uf.user_id = u.user_id AND uf.owner_id = :owner_id
                                    WHERE ' . $where, $bindArr );
        if( !$user ){
            return FALSE;
        }
            
        return $user;
    }
    
    /**
     * Get owner fields 
     * 
     * @return array Fields
     */
    private function _getOwnerFields(){
        $fields = $this->db->select( 'SELECT CONCAT("field_", field_id) as name '
                                        . 'FROM fields WHERE owner_id=:owner_id', 
                                     array('owner_id' => Session::get('user_id') ) );
        return $fields;
    }
    
    /**
     * Get fields data
     * 
     * @return array Fields
     */
    public function getFieldsData(){
        require_once LIBS . 'FieldTypes/FieldType.php';
        
        $fields = $this->db->select( 'SELECT * FROM fields WHERE owner_id=:owner_id', 
                                     array('owner_id' => Session::get('user_id') ) ); 
        
        /*
         * Use following code to find out validate action
         * 
         * For example:
         * FieldText has option maxlength which we must validate (validate == TRUE)
         * but we have to find out the value for this action => maxlength[15]
         */
        $fieldType = [];
        if( !empty( $fields ) ){
            foreach( $fields as &$field ){
                $fType = $field['field_type'];
                if( !isset( $fieldType[$fType] ) ){
                    $fieldType[$fType] = $this->_getFieldType($fType);
                }
                $f_settings = unserialize($field['field_settings']);

                $validate_options = [];
                foreach( $fieldType[$fType]['options'] as $option ){
                    if( $option['validate'] ){
                        $validate_options[$option['short_name']] = 
                                $f_settings[$option['short_name']];
                    }
                }

                $validate_action = '';
                foreach( $validate_options as $key=>$value ){
                    $validate_action .= $key . '[' . $value . ']|';
                }
                
                $field['validate_action'] = $validate_action;
            }
            
        }

        return $fields;
    }
    
    /**
     * Get field type. Use to get Field Options
     * 
     * @param string $type
     * @return array Array with keys ['type', 'options']
     * @access private
     * @throws Exception
     */
    private function _getFieldType( $type ){
        if(file_exists(LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php')){
            require_once LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php';

            $className = 'Field' . ucfirst($type);

            if(class_exists($className)){
                $field = new $className;
                $fieldType = array(
                    'type' => $type,
                    'options' => $field->getOptions()
                );
            } else {
                throw new Exception("Class " . $className . " doesn't exist");
            }
        } else {
            throw new Exception("Field Type " . $type . " doesn't exist.");
        }  
        
        return $fieldType;
    }
    
    /**
     * Save user data
     * 
     * @param array $user
     * @return integer
     * @throws Exception
     */
    public function save( $user ){
        $user_id = NULL;
        if( isset( $user['user_id'] ) ){
            $user_id = $user['user_id'];
            unset( $user['user_id'] );
        }

        if( isset( $user['user_name'] ) && $user_id === NULL ){
            $existingUser = $this->getEntry( NULL, $user['user_name'] );  
            
            if( $existingUser ){
                throw new Exception( 'User <a href="' . URL . 'users/edit/' . $existingUser[0]['user_id'] . '/">' . $existingUser[0]['user_name'] . '</a> already exists' );
            }
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
    
    /**
     * Delete User
     * 
     * @param integer $user_id
     */
    public function delete( $user_id ){
        $this->db->delete( 'users', 'user_id=' . $user_id );
    }    
}

