<?php

class Controller {
    protected $_errorMessage = '';
    protected $_successMessage = '';

    function __construct() {
        $this->view = new View();
        $this->request  = new Request();
        
        Session::init();
        Session::set('user_id', 1);
    }
    
    public function loadModel($name, $modelPath = "models/"){
        $file = $modelPath . $name . "_model.php";
        if(file_exists($file)){
            require $modelPath . $name . "_model.php";
            $modelName = $name . "_Model";
            $this->model = new $modelName();
        } 
    }
    
    protected function _getMessage(){
        return array('error' => $this->_errorMessage, 'success' => $this->_successMessage);
    }
}

