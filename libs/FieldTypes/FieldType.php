<?php

abstract class FieldType {
    private $_options = [];
    
    function __construct() {
        
    }

    /**
     * Set field options
     * 
     * @paramstring    $label          Label   
     * @param string    $short_name     Short name  
     * @param string    $value          Value
     * @param boolean   $required       Is required
     * @param string    $instruction    Instruction
     * @param boolean   $validate       Validate 
     * 
     * @access protected
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
    
    /**
     * Get options array which contains all the settings
     * 
     * @return Array Options array
     */
    public function getOptions(){
        return $this->_options;
    }

    /**
     * Render form element
     * 
     * @param Array $data
     * 
     * @return Array Contains keys ['label', 'instruction', 'tag']
     */    
    abstract function render($data);

    /**
     * Render label in right style
     * 
     * @param String    $value      Value
     * @param Boolean   $required   Is required
     * 
     * @return String Element <label>
     */
    protected function _setLabel( $value, $required ){
        return '<label class="' . ( $required == true ? 'required' : '' ) . '">' . 
                $value . '</label>';
    }
    
    /**
     * Get required attribute for element
     * 
     * @param Boolean $value TRUE or FALSE 
     * @return string
     */
    protected function _isRequired( $value ){
        return $value == TRUE ? 'required' : '';
    }
    
}

