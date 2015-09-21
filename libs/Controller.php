<?php

class Controller {
    protected $_errorMessage = '';
    protected $_successMessage = '';

    function __construct() {
        $this->view = new View(); 

        if( !in_array( get_called_class(), array('Login', 'Error') ) ){
            Auth::handleLogin();
        }
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

    public function delete(){
        if( isset( $_POST['submit_action'] ) ){
            // Check POST data
            try{
                echo "delete";
                $this->form = new Form();
            } catch (Exception $e) {
                Session::set( 'msg_error', array($e->getMessage()) );
                header( "location: " . $_SERVER['HTTP_REFERER'] );
                exit();
            }

            $ids = [];
            $url_segment = '';

            if( is_a( $this, 'Users' ) && isset( $_POST['user_id'] ) ){
                $ids = $_POST['user_id'];
                $url_segment = 'users';
            } else if( is_a($this, 'Fields') && isset( $_POST['field_id'] ) ){
                $ids = $_POST['field_id'];
                $url_segment = 'fields';
            } else if( is_a($this, 'Projects') && isset( $_POST['project_id'] ) ){
                $ids = $_POST['project_id'];
                $url_segment = 'projects';
            }

            if( COUNT($ids) ){
                foreach($ids as $id){
                    $this->model->delete( $id );
                    Session::set('msg_success', 'Entries are successfully deleted.');
                }    
            } else {
                Session::set('msg_error', 'Any entries');
            }
            header( "location: " . URL . $url_segment . "/get_entries/" );
        } else {
            echo 'FALSE';
        }
    }
    
    public function search(){
        if(method_exists( $this, 'get_entries' ) ){
            $list = $this->get_entries();
            return json_encode($list);
        }
    }
}
