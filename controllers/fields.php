<?php

class Fields extends Controller {
    
    public $availableFieldTypes = [];

    function __construct() {
        parent::__construct();
        
        $this->availableFieldTypes = array("text", "select", "file");
    }

    public function add(){
        if($this->request->post('submit')){
            print_r($_POST);
        } else {
            
        }
        
        try {
            $fieldTypes = $this->_getFieldTypes();
            
            $this->view->fieldTypes = $fieldTypes;
            $this->view->setTitle("Add Field");
            $this->view->render("fields/add");            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    private function _getFieldTypes(){
        require_once LIBS . 'FieldTypes/FieldType.php';
        $fieldTypes = [];

        foreach($this->availableFieldTypes as $type){
            if(file_exists(LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php')){
                require_once LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php';
                
                $className = 'Field' . ucfirst($type);
                
                if(class_exists($className)){
                    $field = new $className;
                    $fieldTypes[] = array(
                        'type' => $type,
                        'options' => $field->getOptions()
                    );
                } else {
                    throw new Exception("Class " . $className . " doesn't exist");
                }
            } else {
                throw new Exception("Field Type " . $type . " doesn't exist.");
            }
        }
        
        return $fieldTypes;
    }
}
