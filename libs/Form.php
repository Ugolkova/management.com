<?php

class Form {
    /**
     * We set data to this variable in method input() and then use it in filter()
     * and validate() methods 
     * 
     * @var array
     */
    private $_data = array('name' => '', 'val' => '');
    
    public $requiredFields = array();
    
    function __construct() {
        if($_POST){
            $url = parse_url($_SERVER['HTTP_REFERER']);
            if($url['host'] !== HOST){
                throw new Exception("Unknown host");
            }
        }
        // Use stripslashes for both POST and GET arrays
        $_POST  = $this->_stripslashes($_POST);
        
        // TODO: better use Session::destroy()
        $this->input( 'token' );
        $this->filter( 'string' );
        
        $token = $this->_data['val'];
        
        if( $token !== Session::get( "token" )){
            throw new Exception("Wrong token");
        }
    }
    
    /**
     * Set data to private property $this->_data
     * if $name contains [\d] we use $_POST[$name][\d]
     * 
     * @param string $name
     */
    public function input( $name ){        
        $this->_data['name'] = $name;
        preg_match('/(?<name>\w+)\[(?<index>\d+)\]/', $name, $matches);
        
        if($matches){
            $name = $matches['name'];
            $index = $matches['index'];
            
            if( isset($_POST[$name][$index]) ){
                $this->_data['val'] = $_POST[$name][$index];
            }
        } else {
            if( isset($_POST[$name]) ){
                $this->_data['val'] = $_POST[$name];
            }    
        }  
        
        $this->_data['val'] = trim($this->_data['val']);
        
        return $this;
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
    function filter( $type ){
        switch ($type){
            case "string":
                $this->_data['val'] = strval( preg_replace( '/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', 
                                                     '', 
                                                     $this->_data['val'] ) );
                break;
            case "integer":
                $this->_data['val'] = intval( $this->_data['val'] );
                
                break;
            case "boolean":
                $this->_data['val'] = !empty( $this->_data['val'] );
                
                break;
            default:
                
                break;
        } 

        return $this;
    }
    
    
    function validate( $param, $val ){
        switch($param){
            case 'max_length':
                if( strlen( $this->_data['val'] ) > $val ){
                    throw new Exception( $this->_data['name'] . 'Too long: ' . 
                            strlen( $this->_data['val'] )  );
                }
                
                break;
            default:
                break;
        }

        return $this;
    }
    
    function is_required(){
        if( $this->_data['val'] == '' ){
            $this->requiredFields[] = $this->_data['name']; 
            throw new Exception( $this->_data['name'] . ' is required' );   
        }
        
        return $this;
    }
}
