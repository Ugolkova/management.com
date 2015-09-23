<?php

class View {
    public $title = SITE_NAME;
    public $header = '';
    
    private $_errorFields = array();
    
    function __construct() {
        // ...
    }
    
    public function setTitle( $prefix ){
        $this->title = $prefix . " | " . $this->title;
    }

    public function setHeader( $header ){
        $this->header = $header;
    }    
    
    public function setErrorFields( $fields ){
        $this->_errorFields = $fields;
    }
    
    /**
     * Print message
     * 
     * @return string
     */
    public function printMessage(){
        $msg_block = '';

        if( Session::get('msg_success') || Session::get('msg_error') ){
            $msg_block .= '<div class="message"><i>x</i>';
            if( Session::get( 'msg_success' ) ){
                $msg_success = Session::get( 'msg_success' );
                if( is_string( $msg_success ) ){ 
                    $msg_block .= '<p class="success">' . $msg_success . '</p>';
                } else if( is_array( $msg_success ) ){
                    foreach($msg_success as $m_success){
                        $msg_block .= '<p class="success">' . $m_success . '</p>';
                    }
                }
            }
            
            if( Session::get( 'msg_error' ) ){
                $msg_error = Session::get( 'msg_error' );
                if(is_string( $msg_error ) ){ 
                    $msg_block .= '<p class="error">' . $msg_error . '</p>';
                } else if( is_array( $msg_error ) ){
                    foreach($msg_error as $m_error){
                        $msg_block .= '<p class="error">' . $m_error . '</p>';
                    }
                }
            }
            
            $msg_block .= '</div>';
        }
        
        return $msg_block;
    }
    
    /**
     * Check if it's an error field
     * 
     * @param string $name
     * @desc Use in view
     * @return boolean
     */
    public function isErrorField( $name ){
        if(in_array($name, $this->_errorFields) ){
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Render template
     * 
     * @param string $name
     */
    public function render($name){
        require "views/header.php";
        require "views/" . $name . ".php";
        require "views/footer.php";
    }
}

