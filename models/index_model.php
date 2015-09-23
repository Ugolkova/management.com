<?php

class Index_model extends Model{

    function __construct() {
        parent::__construct();
    }

    /**
     * Get list of fields according to owner id
     * 
     * @param integer $ownerId
     */
    public function getFieldsList($ownerId){
        $fields = array();
        
        if($ownerId == 1){
            $fields[] = array(
                "id" => 1,
                "label" => "Name",
                "placeholder" => "",
                "default" => "",
                "type" => "text",
                "required" => true                
            );

            $fields[] = array(
                "id" => 2,
                "label" => "Phone",
                "placeholder" => "phone",
                "default" => "",
                "type" => "text",
                "required" => false
            );
            
            $fields[] = array(
                "id" => 3,
                "label" => "Type",
                "values" => "owner|manager|user",
                "default" => "owner",
                "type" => "select",
                "required" => true
            ); 
            
            $fields[] = array(
                "id" => 4,
                "label" => "Avatar",
                "type" => "image",
                "required" => false,
                "value" => URL . "files/download.jpg"
            );
        } else {
            $fields[] = array(
                "id" => 5,
                "label" => "Your age",
                "placeholder" => "25",
                "default" => "",
                "type" => "text",
                "required" => false
            );

            $fields[] = array(
                "id" => 6,
                "label" => "Your pet",
                "values" => "cat|dog|bird",
                "default" => "dog",
                "type" => "select",
                "required" => false                
            );
            
            $fields[] = array(
                "id" => 7,
                "label" => "Fruits",
                "values" => "apple|orange|lemon",
                "default" => "apple|lemon",
                "type" => "checkbox",
                "required" => false                
            );
            
            $fields[] = array(
                "id" => 8,
                "label" => "Resume",
                "type" => "file",
                "required" => false,
                "value" => URL . "files/download.jpg"
            );
            
        }
        
        return $fields;
    }

}

