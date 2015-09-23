<?php

class FieldImage extends FieldType {    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Render form element
     * 
     * @param array $data
     * @return Array Contains keys ['label', 'instruction', 'tag']
     */    
    public function render($data){
        $element['label'] = '';
        $element['instruction'] = '';
        $element['tag'] = '';
        
        return $element;
    }
}

