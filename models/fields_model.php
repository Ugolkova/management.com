<?php

class Fields_model extends Model {

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
}

