<?php

class FieldText extends FieldType {        
    function __construct() {
        parent::__construct();
        
        $this->_setOptions( 'Maxlength', 'maxlength', 20, FALSE, 'Max length of the field', TRUE );
        $this->_setOptions( 'Minlength', 'minlength', 5, TRUE, '', TRUE );
        $this->_setOptions( 'Placeholder', 'placeholder' );
    }
        
    /**
     * Render form element
     * 
     * @param array $data
     * @return Array Contains keys ['label', 'instruction', 'tag']
     */ 
    public function render($data){
        if( !isset( $data['field_error'] ) ){
            $data['field_error'] = FALSE;
        }
        
        $element['label'] = $this->_setLabel($data['field_label'], $data['field_required']);
        $element['instruction'] = $data['field_instruction'];
        $element['tag'] = '<input type="text" name="' . $data['field_name'] 
                    . '" value="' . $data['field_value'] . '" placeholder="' 
                    . $data['field_settings']['placeholder'] . '" ' 
                    . ' minlength="' . $data['field_settings']['minlength'] . '" ' 
                    . ' maxlength="' . $data['field_settings']['maxlength'] . '" '
                    . ' class="' . ($data['field_error'] === TRUE ? 'error' : '') . '"'
                    . 'data-required="' . $data['field_required'] . '" />';
        
        return $element;
    }
    
}

