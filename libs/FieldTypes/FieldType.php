<?php

abstract class FieldType {
    private $_options = [];
    
    function __construct() {
        
    }

    /**
     * Set field options
     * 
     * @param string $label
     * @param string $short_name
     * @param string $value
     * @param boolean $required
     * @param string $instruction
     */
    protected function _setOptions($label, $short_name, $value = '', $required = FALSE, $instruction = ''){
        $this->_options[] = array(
            'label'       => $label,
            'short_name'  => $short_name,
            'value'       => $value,
            'instruction' => $instruction,
            'required'    => $required
        );
    }    
    
    public function getOptions(){
        return $this->_options;
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

