<?php

class FieldFile extends FieldType {    
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Allowed file types', 'file-types', 'JPG | GIF');
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
            $element .= '<a href="' . $data['value'] . '" title="Link" target="_blank">Download File</a>';
        }
        
        return $element;
    }
}

