<?php

class FieldSelect extends FieldType {    
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Options', 'options', '', 'Use comma to separate items');
    }
    
    /**
     * Render form element
     * @param array $data
     * @return string
     */
    public function render($data){
        $element = $this->_setLabel($data['label'], $data['required']); 
        $element .= '<select name="field_' . $data['id'] . '"';
        $element .= $this->_isRequired($data['required']). '>';
        $data['values'] = explode('|', $data['values']);
        if(!empty($data['values'])){
            foreach($data['values'] as $option){
                $option = trim($option);
                $element .= '<option value="' . $option . '"';
                if($option == $data['default']){
                    $element .= " selected";
                }    
                $element .= '>' . $option . '</option>';
            }
        }
        $element .= '</select>';
        
        return $element;
    }
}

