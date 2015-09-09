<?php

class FieldText extends FieldType {        
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Maxlength', 'maxlength', 20);
        $this->_setOptions('Minlength', 'minlength', 5);
        $this->_setOptions('Placeholder', 'placeholder');
    }
        
    /**
     * Render form element
     * 
     * @param array $data
     * @return string
     */
    public function render($data){
        $element = $this->_setLabel($data['label'], $data['required']);
        $element .= '<input type="text" name="field_' . $data['id'] 
                    . '" value="' . $data['default'] . '" placeholder="' 
                    . $data['placeholder'] . '" ' 
                    . $this->_isRequired($data['required']) .' />';
        
        return $element;
    }
    
}

