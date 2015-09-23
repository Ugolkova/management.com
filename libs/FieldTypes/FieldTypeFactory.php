<?php

class FieldTypeFactory {
    
    /**
     * Builds new Object according to Field Type
     * 
     * @param String $fieldType Field Type
     * 
     * @return Object New field 
     * 
     * @throws Exception
     */
    public static function build($fieldType){
        $className = "Field" . ucfirst(strtolower($fieldType));
        $filePath = LIBS . "FieldTypes/" . $className . ".php";
        if(file_exists($filePath)){
            require_once $filePath;
            if(class_exists($className)){
                return new $className;
            } else {
                throw new Exception("Class " . $className . " doesn't exist", 404);
            }
        } else {
            throw new Exception("File " . $className . ".php doesn't exist", 404);
        }
    }

}