<?php

abstract class FieldType {
    
    function __construct() {
        $this->_options = array("default", "label", "name", "owner_id");
    }
    
    abstract function render($data);

    /**
     * Render label in right style
     * @param string $value
     * @return string
     */
    protected function _setLabel($value, $required){
        return '<label>' . $value . ($required == true ? '<s>*</s>' : '') . '</label>';
    }
    
    /**
     * Get required attribute for element
     * @param boolean $value
     * @return string
     */
    protected function _isRequired($value){
        return $value == true ? 'required' : '';
    }
    
    public function addField(){
        $data['field_name'] = $_POST['field_name'];
        $data['field_name'] = $_POST['field_name'];
        $data['field_name'] = $_POST['field_name'];
        $data['field_name'] = $_POST['field_name'];
        $data['field_name'] = $_POST['field_name'];
        $data['field_name'] = $_POST['field_name'];
        
        $this->db->insert("fields", $data);
    }
    
    public function updateField($data){
        
    }
    
    public function deleteField($fieldId){
        
    }
}

