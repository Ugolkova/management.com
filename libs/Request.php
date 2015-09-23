<?php

class Request {  
    protected $_fLabel = '';
    protected $_fName = '';
    protected $_fValue = '';    
    
    function __construct() {
        $_POST  = $this->_stripslashes($_POST);
        $_GET   = $this->_stripslashes($_GET);
    }
    
    /**
     * Recursive function. Will be used for stripping off the backslashes in a string
     * @param string $var
     * @return string|array 
     */
    private function _stripslashes($var){
        if(is_array($var)){
            foreach($var as $k=>$v){
                $var[stripcslashes($k)] = $this->_stripslashes($v);
            }
        } else {
            $var = stripslashes($var);
        }
        
        return $var;
    }
    
    /**
     * Set data to right type
     * 
     * @param string $type
     */
    protected function _filter( $type ){
        switch ($type){
            case "string":
                $this->_fValue = strval( $this->_fValue );
                break;
            case "integer":
                $this->_fValue = intval( $this->_fValue );
                
                break;
            case "boolean":
                $this->_fValue = !empty( $this->_fValue );

                break;
            default:
                
                break;
        } 
    }    
    
    /**
     * Validate data
     * 
     * @param String $name Example: $_GET[$name]
     * @param String $type Filter type
     * 
     * @desc Use it for $_GET[]
     * @return String Value
     */
    public function validate( $name, $type ){
        if( isset($_GET[$name]) ){
            $this->_fValue = $_GET[$name];
        } else {
            $this->_fValue = '';
        }
        
        $this->_filter($type);
        
        return $this->_fValue;
    }
}
