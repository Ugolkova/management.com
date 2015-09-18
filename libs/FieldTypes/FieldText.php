<?php

class FieldText extends FieldType {        
    function __construct() {
        parent::__construct();
        
        $this->_setOptions( 'Maxlength', 'maxlength', 20, FALSE, 'Max length of the field' );
        $this->_setOptions( 'Minlength', 'minlength', 5 );
        $this->_setOptions( 'Placeholder', 'placeholder' );
    }
        
    /**
     * Render form element
     * 
     * @param array $data
     * @return array
     */
    public function render($data){
        $element['label'] = $this->_setLabel($data['field_label'], $data['field_required']);
        $element['instruction'] = $data['field_instruction'];
        $element['tag'] = '<input type="text" name="field_' . $data['field_id'] 
                    . '" value="' . $data['field_value'] . '" placeholder="' 
                    . $data['field_settings']['placeholder'] . '" ' 
                    . $this->_isRequired($data['field_required']) .' />';
        
        return $element;
    }
    
}

