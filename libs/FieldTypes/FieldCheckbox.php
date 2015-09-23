<?php

class FieldCheckbox extends FieldType {    
    
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Options', 'options', '', FALSE, 'Use following ' .
                'sign <b>"' . MAIN_DELIMITER . '"</b> to separate items');
    }
    
    /**
     * Render form element
     * 
     * @param array $data
     * @return Array Contains keys ['label', 'instruction', 'tag']
     */
    public function render($data){
        $element['label'] = $this->_setLabel($data['field_label'], $data['field_required']); 
        $element['instruction'] = $data['field_instruction'];
        $element['tag'] = '';
        $element['tag'] .= "<div>";
        
        $data['field_value'] = explode( MAIN_DELIMITER, $data['field_value']);
        
        if(!empty($data['field_settings']['options'])){
            foreach($data['field_settings']['options'] as $option){
                $option = trim( $option );
                $element['tag'] .= '<input type="checkbox" name="' . $data['field_name'] . '[]" value="' . $option . '"'; 
                if( in_array( $option, $data['field_value'] ) ){
                    $element['tag'] .= " checked";
                }
                $element['tag'] .= ' /><label>' . $option . '</label><br />';
            }
        }
        
        return $element;
    }

}

