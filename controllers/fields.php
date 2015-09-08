<?php

class Fields extends Controller {
    
    public $availableFieldTypes = [];

    function __construct() {
        parent::__construct();
        
        $this->availableFieldTypes = array("text", "select", "file");
    }

    public function index(  ){
        $this->get_list();
    }
    
    public function get_list( $page = null ){        
        if(preg_match('/^p(\d+)$/', $page, $matches) === 1){
            $page = (int)$matches[1];
        } else {
            $page = 1;
        }
        
        $fieldsArr   = $this->model->getList( $page );
        $fieldsCount = $this->model->getRowsCount();
        
        $this->view->fields         = $fieldsArr;
        $this->view->fieldsCount    = $fieldsCount;
        
        $pagination = new Pagination();
        $this->view->pagination = $pagination->createLinks($page, $fieldsCount);
        $this->view->render("fields/list");
    }
    
    
    /**
     * Add field
     * 
     * Firstly we check if we have post data and then save it
     */
    public function add(){        
        if( $this->form->validate( 'submit' ) ){            
            $field['field_type'] = $this->form->validate('field_type', 'string');
            $field['field_label'] = $this->form->validate('field_label', 'string');
            $field['field_instructions'] = $this->form
                                                ->validate('field_instructions', 'string');
            $field['field_required'] = $this->form
                                            ->validate('field_required', 'boolean');
            $field['owner_id'] = $this->form->validate('owner_id', 'integer');                

            $this->_save( $field );    
        } else {
            $field = array(
                'field_type' => '',
                'field_label' => '',
                'field_instructions' => '',
                'field_required' => false
            );            
        }

        $fieldTypes = $this->_getFieldTypes();
                
        $this->view->setTitle("Add Field" );
        $this->view->setHeader( "Add Field" );
        $this->view->field = $field;
        $this->view->fieldId = FALSE;
        $this->view->fieldTypes = $fieldTypes;
        $this->view->render("fields/add");
    }

    /**
     * Edit Field
     * 
     * @param integer $field_id
     */
    public function edit( $field_id = null ){        
        if( $this->form->validate( 'submit' ) ){  
            require_once LIBS . 'FieldTypes/FieldType.php';

            $msg_success = [];
            $msg_error = [];    
            
            $countEntries = COUNT($_POST['field_id']);
            for( $i = 0; $i < $countEntries; $i++ ){
                $field_id = $this->form->validate("field_id[$i]", 'integer');
                
                $field['field_type'] = $this->form->validate("field_type[$i]", 'string');
                $field['field_label'] = $this->form->validate("field_label[$i]", 'string');
                $field['field_instructions'] = $this->form
                                                    ->validate("field_instructions[$i]", 'string');
                $field['field_required'] = $this->form
                                                ->validate("field_required[$i]", 'boolean');
                $field['owner_id'] = $this->form->validate("owner_id[$i]", 'integer');                
                
                $ft = $this->_getFieldType( $field['field_type'] );
                
                $field_settings = [];
                foreach( $ft['options'] as $option ){
                    $field_settings[] = 
                            $this->form->validate( $option['short_name'] . "[$i]" );
                }
                
                $field['field_settings'] = serialize($field_settings);
               
                $fields[] = $field;
                try{
                    $this->model->save( $field, $field_id );
                    $msg_success[] = "Field #" . $field_id . " successfully updated";
                } catch(Exception $e){
                    $msg_error[] = $e->getMessage();
                }    
                
            }
            
            if( !empty( $msg_success ) ){
                Session::set( 'message', array('success' => $msg_success) );
            } 
            elseif( !empty( $msg_error ) ) {
                Session::set( 'message', array('error' => $msg_error) );     
            } 
            
            //header("location: " . URL . 'fields/get_list');
            
        } else {
            $fields = array();
            
            if( $field_id == null ){
                $field_ids = $_POST['field_id'];
                if( !is_array( $field_ids ) || empty( $field_ids ) ){
                    header( "location: " . URL . "fields/get_list/" );
                }
                foreach( $field_ids as $field_id ){
                    $field = $this->model->getField( $field_id )[0];
                    if( $field ){
                        $fields[] = $field;
                    }
                }
            } else {
                $field = $this->model->getField( $field_id )[0];   
                if( $field ){
                    $fields[] = $field;
                }
            }
            
            if( empty($fields) ){
                header( "location: " . URL . "fields/get_list/" );
                die();
            }
        }
                
        // Set settings value we already have
        $fieldTypes = $this->_getFieldTypes();
        
        $this->view->setTitle("Edit Field" );
        $this->view->setHeader( "Edit Field" );
        $this->view->fields = $fields;
        $this->view->fieldTypes = $fieldTypes;
        $this->view->fieldId = $field_id;
        $this->view->render("fields/edit");
    }
    
    /**
     * Get Field Type
     * 
     * @param string $type
     * @return array
     * @throws Exception
     */
    private function _getFieldType( $type ){
        if(file_exists(LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php')){
            require_once LIBS . 'FieldTypes/Field' . ucfirst($type) . '.php';

            $className = 'Field' . ucfirst($type);

            if(class_exists($className)){
                $field = new $className;
                $fieldType = array(
                    'type' => $type,
                    'options' => $field->getOptions()
                );
            } else {
                throw new Exception("Class " . $className . " doesn't exist");
            }
        } else {
            throw new Exception("Field Type " . $type . " doesn't exist.");
        }  
        
        return $fieldType;
    }

    /**
     * 
     * @return type
     */
    private function _getFieldTypes(){
        require_once LIBS . 'FieldTypes/FieldType.php';
        $fieldTypes = [];

        foreach($this->availableFieldTypes as $type){
            $fieldTypes[] = $this->_getFieldType( $type );
        }
        
        return $fieldTypes;
    }

}
