<?php

class FieldText extends FieldType {    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Render form element
     * @param array $data
     * @return string
     */
    public function render($data){
        $element = $this->_setLabel($data['label'], $data['required']);
        $element .= '<input type="text" name="field_' . $data['id'] . '" value="' . $data['default'] . '" placeholder="' . $data['placeholder'] . '" '. $this->_isRequired($data['required']) .' />';
        
        return $element;
    }
}

