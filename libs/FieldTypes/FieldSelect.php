<?php

class FieldSelect extends FieldType {    
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Options', 'options', '', FALSE, 'Use comma to separate items');
    }
    
    /**
     * Render form element
     * @param array $data
     * @return string
     */
    public function render($data){
        $element['label'] = $this->_setLabel($data['field_label'], $data['field_required']); 
        $element['instruction'] = $data['field_instruction'];
        $element['tag'] = '';
        $element['tag'] .= '<select name="' . $data['field_name'] . '"';
        $element['tag'] .= $this->_isRequired($data['field_required']). '>';
        if(!empty($data['field_settings']['options'])){
            foreach($data['field_settings']['options'] as $option){
                $option = trim($option);
                $element['tag'] .= '<option value="' . $option . '"';  
                if($option == $data['field_value']){
                    $element['tag'] .= " selected";
                }  
                $element['tag'] .= '>' . $option . '</option>';
            }
        }
        $element['tag'] .= '</select>';
        
        return $element;
    }
}

