<?php

class Fields_model extends Model {
    private $_fieldsCount = 0;
    
    function __construct() {
        parent::__construct();
    }

    /**
     * Save Field
     * 
     * @param array $field
     * @param integer $field_id
     * @throws Exception
     */
    public function save($field, $field_id = FALSE){
        if( isset( $field['field_id'] ) ){
            unset( $field['field_id'] );
        }
        
        // Get field type for DB, table user_fields
        $f_type = 'VARCHAR(255)';
        $enum_field_types = ['select', 'checkboxes', 'radio_buttons'];
        
        if( in_array( $field['field_type'], $enum_field_types ) ){  
            $f_settings = unserialize( $field['field_settings'] );
            $f_type = 'ENUM(';
            foreach( $f_settings['options'] as $option ){
                $f_type .= '"' . $option . '",';
            }
            $f_type = rtrim($f_type, ',');
            $f_type .=  ')';
        }
        
        if( $field_id ){
            if( !$this->db->update( 'fields', $field, 'field_id=' . $field_id )){
                throw new Exception("Wrong data for field #" . $field_id);
            }
            
            $alterTableAction = "MODIFY";
        } else {
            $field_id = $this->db->insert('fields', $field);
            if( !$field_id ){
                throw new Exception("Can't add the field");
            }
            
            $alterTableAction = "ADD";
        }   
        
        $this->db->alterTable( 'ALTER TABLE `user_fields` ' . 
                                $alterTableAction . ' field_' . $field_id . 
                                ' ' . $f_type . ' NOT NULL' );
        
        return $field_id;
    }
    
    public function getEntry( $field_id ){
        $query = 'SELECT * FROM fields WHERE field_id=:field_id';
        $bindArr = array('field_id' => $field_id);
        if( Session::get('user_type') != 'admin' ){
            $query .= ' AND owner_id=:owner_id';
            $bindArr['owner_id'] = Session::get('user_id');
        }
        $field = $this->db->select( $query, $bindArr );
        
        if( !$field ){
            return FALSE;
        }
        
        return $field;
    }
    
    public function getList( $page ){
        $countEntries = COUNT_ENTRIES_ON_PAGE;
        if( Session::get('COUNT_ENTRIES_ON_PAGE') ){
            $countEntries = Session::get('COUNT_ENTRIES_ON_PAGE');
        }
        
        $limit = "LIMIT ";
        if($page > 1){
            $limit .= (($page - 1) * $countEntries) . ", " . $countEntries;
        } else {
            $limit .= $countEntries;
        }
         
        $where = "WHERE 1";
        if( Session::get('user_type') != 'admin' ){
            $where .= " AND f.owner_id=" . Session::get('user_id');
        } 
        
        if( $this->searchKey !== '' ){
            $where .= " AND f.field_label LIKE '%" . $this->searchKey . "%'";
        }
        
        $sql = "SELECT f.field_id, f.field_type, f.field_label, f.owner_id, 
                u.user_name as owner_name 
                FROM fields f
                LEFT JOIN users u ON f.owner_id = u.user_id 
                $where $limit";

        $fieldsArr = $this->db->select( $sql ); 

        $sql = "SELECT COUNT(*) as fields_count FROM fields f $where";
        
        $this->_fieldsCount = $this->db->select( $sql, [], PDO::FETCH_NUM );
        if( !empty( $this->_fieldsCount ) ){
            $this->_fieldsCount = $this->_fieldsCount[0][0];
        } else {
            $this->_fieldsCount = 0;
        }
        
        return $fieldsArr;        
    }
    
    public function getRowsCount(){
        return $this->_fieldsCount;
    }
 
    public function delete( $field_id ){
        $this->db->delete( 'fields', 'field_id=' . $field_id );
        $this->db->alterTable( 'ALTER TABLE `user_fields` DROP field_' . $field_id );
    }
}

