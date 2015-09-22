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
     * @param boolean $validate
     */
    protected function _setOptions($label, $short_name, $value = '', $required = FALSE, $instruction = '', $validate = FALSE){
        $this->_options[] = array(
            'label'         => $label,
            'short_name'    => $short_name,
            'value'         => $value,
            'instruction'   => $instruction,
            'required'      => $required,
            'validate'      => $validate
        );
    }    
    
    public function getOptions(){
        return $this->_options;
    }

    public function getOptionsToValidate(){
        $options = [];
        foreach( $this->_options as $option ){
            if( $option['validate'] ){
                $options[] = $option['label'];
            }
        }
    }    
    
    abstract function render($data);

    /**
     * Render label in right style
     * @param string $value
     * @return string
     */
    protected function _setLabel($value, $required){
        return '<label class="' . ($required == true ? 'required' : '') . '">' . 
                $value . '</label>';
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

