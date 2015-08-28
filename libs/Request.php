<?php

class Request {
    
    function __construct() {
        if($_POST){
            $url = parse_url($_SERVER['HTTP_REFERER']);
            if($url['host'] !== HOST){
                throw new Exception("Unknown host");
            }
        }
        // Use stripslashes for both POST and GET arrays
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
     * This function returns $_GET[$name] variable, which is filtered according to the type
     * the function will be used like this:
     * $age = $this->request->get('age', 'integer');
     * so the value of variable $age equals (int)$_GET['age']
     * @param string $name
     * @param string $type We use three types: string | integer | boolean
     * @return string | array
     */
    function get($name, $type = null){
        $val = null;
        if(isset($_GET[$name]))
            $val = $_GET[$name];
        
        if(!empty($type) && is_array($val)){
            $val = reset($val);
        }
        
        switch ($type){
            case "string":
                $val = strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $val));
                break;
            case "integer":
                $val = intval($val);
                break;
            case "boolean":
                $val = !empty($val);
                break;
        }
        
        return $val;
    }
    
    /**
     * This function returns $_POST[$name] variable, which is filtered according to the type
     * the function will be used like this:
     * $age = $this->request->get('age', 'integer');
     * so the value of variable $age equals (int)$_POST['age']
     * @param string $name
     * @param string $type We use three types: string | integer | boolean
     * @return string | array
     */
    function post($name = null, $type = null){
        $val = null;
        
        if(!empty($name) && isset($_POST[$name])){
            $val = $_POST[$name];
        } else if(empty($name)){
            $val = file_get_contents('php://input');
        }
        
        switch ($type){
            case "string":
                $val = strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $val));
                break;
            case "integer":
                $val = intval($val);
                break;
            case "boolean":
                $val = !empty($val);
                break;
        }
        
        return $val;        
    }
    
    /**
     * By using this function we can get the name of the file, if the second parameter is defined
     * or an array according to the first parameter. 
     * @param string $name
     * @param string $name1
     * @return array|string|null
     */
    function files($name, $name1 = null){
        if(!empty($name1) && !empty($_FILES[$name][$name1])){
            return $_FILES[$name][$name1];
        } else if(empty($name1) && !empty($_FILES[$name])) {
            return $_FILES[$name];
        }
        return null;
    }
    
}
