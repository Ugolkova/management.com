<?php

class Form extends Request{
    
    public $errorFields = array();
    public $errorMessages = array();
    
    function __construct() {
        parent::__construct();
        
        if($_POST){
            $url = parse_url($_SERVER['HTTP_REFERER']);
            if($url['host'] !== HOST){
                throw new Exception("Unknown host");
            }
        }

        Session::init();
        if( (string)$_POST['token'] !== Session::get( 'token' )){
            throw new Exception("Wrong token");
        }
    }
    
    /**
     * Set data to private property $this->_data
     * if $name contains [\d] we use $_POST[$name][\d]
     * 
     * @param string $name
     */
    private function _input( $name ){        
        preg_match('/(?<name>\w+)\[(?<index>\d+)\]/', $name, $matches);
        
        if($matches){
            $name = $matches['name'];
            $index = $matches['index'];
            
            if( isset($_POST[$name][$index]) ){
                $this->_fValue = $_POST[$name][$index];
            }
        } else {
            if( isset($_POST[$name]) ){
                $this->_fValue = $_POST[$name];
            }    
        }  
        
        $this->_fValue = trim($this->_fValue);        
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
        
    private function _setRule( $param ){
        $val = '';
        preg_match( '/(?<param>\w+)\[(?<val>[\w\,\s]+)\]$/', $param, $matches);
        if( $matches ){
            $param = $matches['param'];
            $val = $matches['val'];
        }
        
        switch($param){
            case 'maxlength':
                if( strlen( $this->_fValue ) > (int)$val ){
                    $this->errorMessages[] = $this->_fLabel . ': Max Length - ' . $val;
                    $this->errorFields[] = $this->_fName;
                }
                
                break;
            case 'minlength':
                if( strlen( $this->_fValue ) < (int)$val ){
                    $this->errorMessages[] = $this->_fLabel . ': Min Length - ' . $val;
                    $this->errorFields[] = $this->_fName;
                }
                
                break;                
            case 'required':
                if( $this->_fValue == ''){
                    $this->errorMessages[] = $this->_fLabel . ': is required';
                    $this->errorFields[] = $this->_fName;
                }
                
                break;
            case 'in_array':
                $availableValues = explode( MAIN_DELIMITER, $val );
                
                if( !in_array( $this->_fValue, $availableValues ) ){
                    $this->errorMessages[] = $this->_fLabel . ': must be in array - ' .
                                             $val;
                }
                
                break;
            default:
                break;
        }

        return $this;
    }
        
    public function validate( $name, $label, $type, $rules = '' ){
        $this->_fLabel = $label;
        $this->_fName = $name;
        
        $this->_input( $name );
        
        // Set to needed type, e.g. we get '12 years old' for field 'age'
        // but we need just digits ($type == 'integer')
        $this->_filter( $type );
        
        $rules = explode('|', $rules);
        foreach ( $rules as $rule ){
            $this->_setRule( $rule );
        }
        
        return $this->_fValue;
    }
}
