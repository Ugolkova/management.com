<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index(){
        require_once LIBS . 'FieldTypes/FieldTypeFactory.php';
        require_once LIBS . 'FieldTypes/FieldType.php';
                
        try{
            $fields = $this->model->getFieldsList(1);
            $userFields = $this->model->getFieldsList(12);
            foreach($userFields as $userField){
                array_push($fields, $userField);
            }    
            
            $formFields = array();
            foreach($fields as $field){
                $formField = FieldTypeFactory::build($field['type']);
                $formFields[] = $formField->render($field);
            }            
            $this->view->formFields = $formFields;
        } catch (Exception $e){
            echo $e->getMessage();
        }    
        
        $this->view->title  = "Home Page";        
        $this->view->render("index/index");        
    }
    
    public function add_user(){
        $user = $this->model->addUser();
        echo $user;
        /*if(isset($this->request->post('abc'))){
            echo TRUE; 
        } else {
            echo FALSE;
        }*/
    }
}
