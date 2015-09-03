<?php

class Fields_model extends Model {
    private $_fieldsCount = 0;
    
    function __construct() {
        parent::__construct();
        @session_start();
        $_SESSION['lm_id'] = 2;
        $_SESSION['pm_id'] = 3;
    }

    /**
     * Save Field
     * 
     * @param array $field
     * @param integer|boolean $field_id
     * @throws Exception
     */
    public function save($field, $field_id = FALSE){
        if( $field_id ){
            $this->db->update( 'fields', $field, 'field_id=' . $field_id );
        } else {
            $id = $this->db->insert('fields', $field);
            if( !$id ){
                throw new Exception("Can't add the field");
            }
        }   
    }
    
    public function getField( $field_id ){
        $field = $this->db->select( 'SELECT * FROM fields WHERE field_id=:field_id', array('field_id' => $field_id) );
        return $field;
    }
    
    public function getList( $page ){
        $limit = "LIMIT ";
        if($page > 1){
            $limit .= (($page - 1) * COUNT_ENTRIES_ON_PAGE) . ", " . COUNT_ENTRIES_ON_PAGE;
        } else {
            $limit .= COUNT_ENTRIES_ON_PAGE;
        }
        
        $where = "WHERE owner_id=" . Session::get('user_id');
        
        $sql = "SELECT field_id, field_type, field_label FROM fields $where $limit";
                
        $fieldsArr = $this->db->select( $sql ); 

        $sql = "SELECT COUNT(*) as fields_count FROM fields $where";
        
        $this->_fieldsCount = $this->db->select( $sql, [], PDO::FETCH_NUM )[0][0];
        
        return $fieldsArr;        
    }
    
    public function getRowsCount(){
        return $this->_fieldsCount;
    }
    
}

