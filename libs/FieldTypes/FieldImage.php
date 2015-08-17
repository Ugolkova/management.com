<?php

class FieldImage extends FieldType {    
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
        $element .= '<input type="file" name="field_' . $data['id'] . '" value="" '. $this->_isRequired($data['required']) .' />';
        if(isset($data['value'])){
            $element .= '<img class="imgField" src="' . $data['value'] . '" alt="Image" />';
        }
        
        return $element;
    }
}

