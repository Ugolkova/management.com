<?php

/**
 * Bootstrap Class
 * 
 */
class Bootstrap {
    public $segments = null;
    private $_url = null;
    private $_controller = null;
    
    private $_controllerPath = "controllers/";
    private $_modelPath = "models/";
    private $_errorFile = "error.php";
    private $_defaultFile = "index.php";

    /**
     * Initialize
     * 
     * @return boolean
     */
    public function init(){
        $this->_getUrl();
        
        $this->segments = $this->_url;
        
        // Load the default controller if no URL is set (http://management.com)
        if(empty($this->_url[0])){
            $this->_loadDefaultController();
            return false;
        }
        
        $this->_loadExistingController();
        $this->_callControllerMethod();
    }
    
    /**
     * Get URL and assign it as array to private variable $this->_url
     */
    private function _getUrl(){
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        $this->_url = explode("/", $url);
    }
    
    /**
     * Load default controller (Index)
     */
    private function _loadDefaultController(){
        require $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new Index();
        $this->_controller->index();
        $this->_controller->loadModel("index", $this->_modelPath);
    }
    
    /**
     * Load existing controller
     * 
     * @desc Check if controller exists and then assign new object to $this->_controller
     * @return boolean
     */
    private function _loadExistingController(){
        $file = $this->_controllerPath . $this->_url[0] . ".php";
        if(file_exists($file)){
            require $file;
            if(class_exists($this->_url[0])){
                $this->_controller = new $this->_url[0];
                $this->_controller->loadModel($this->_url[0], $this->_modelPath);
            } else {
                $this->_error();
                return false;
            }
        }
    }
    
    /**
     * Error
     * 
     * @desc Creates Error class
     */
    private function _error(){
        require $this->_controllerPath . $this->_errorFile;
        $this->_controller = new Error();
        $this->_controller->index();
        exit;
    }
    
    /**
     * Call controller method
     */
    private function _callControllerMethod(){
        $length = COUNT( $this->_url );
        
        if( $length > 1 ){
            if(!method_exists( $this->_controller, $this->_url[1] ) ){
                $this->_error();
            }
        }
        
        switch( $length ){
            case 5:
                $this->_controller->{$this->_url[1]}( $this->_url[2], 
                                                      $this->_url[3], 
                                                      $this->_url[4] );
                break;
            case 4:
                $this->_controller->{$this->_url[1]}( $this->_url[2], 
                                                      $this->_url[3] );
                break;
            case 3:
                $this->_controller->{$this->_url[1]}( $this->_url[2] );
                break;
            case 2: 
                $this->_controller->{$this->_url[1]}();
                break;
            case 1:
                $this->_controller->index();
                break;
        }      
    }
    
    /**
     * Set controller path
     * 
     * @param String $path
     */
    public function setControllerPath( $path ){
        $this->_controllerPath = trim( $path, "/" ) . "/";
    }
    
    /**
     * Set model path
     * 
     * @param String $path
     */
    public function setModelPath($path){
        $this->_modelPath = trim($path, "/") . "/";
    }
    
    /**
     * Set error file
     * 
     * @param String $path
     */
    public function setErrorFile($path){
        $this->_errorFile = trim($path, "/") . "/";
    }
    
    /**
     * Set default file
     * 
     * @param String $path
     */
    public function setDefaultFile($path){
        $this->_defaultFile = trim($path, "/") . "/";
    }
}

