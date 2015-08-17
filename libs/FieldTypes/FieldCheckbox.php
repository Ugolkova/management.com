<?php

class FieldCheckbox extends FieldType {    
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
        $data['values'] = explode('|', $data['values']);
        if(!empty($data['values'])){
            $defaultValues = explode("|", $data['default']);
            $element .= "<div>";
            foreach($data['values'] as $option){
                $option = trim($option);
                $element .= '<input type="checkbox" name="field_' . $data['id'] . '[]" value="' . $option . '"';
                if(in_array($option, $defaultValues)){
                    $element .= " checked ";
                }    
                $element .= '/><label>' . $option . '</label><br />';
            }
            $element .= "</div>";
        }
        
        return $element;
    }
}

