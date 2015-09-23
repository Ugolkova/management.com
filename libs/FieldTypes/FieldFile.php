<?php

class FieldFile extends FieldType {    
    function __construct() {
        parent::__construct();
        
        $this->_setOptions('Allowed file types', 'filetypes', 'JPG, GIF');
    }
    
    /**
     * Render form element
     * 
     * @param Array $data
     * @return Array Contains keys ['label', 'instruction', 'tag']
     */    
    public function render($data){
        $element['label'] = '';
        $element['instruction'] = '';
        $element['tag'] = '';
        
        return $element;
    }
}

