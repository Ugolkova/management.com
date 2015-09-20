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
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );  
        
        
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

        $this->view->searchKey = $this->model->searchKey;        
        
        if( $this->model->searchAutocomplete ){
            $autocompleteArr = [];
            foreach($fieldsArr as $field){
                $autocompleteArr[] = array( 'link' => URL . 'fields/edit/' . 
                                                      $field['field_id'] . '/',
                                             'name' => $field['field_label']);
            }
            echo json_encode($autocompleteArr);
        } else {
            $this->view->render("fields/list");
        }
    }
    
    
    protected function _validateField( $field_id = FALSE ){
        try{
            $this->form = new Form();
        } catch (Exception $e) {
            header( "location: " . $_SERVER['HTTP_REFERER'] );
            Session::set( 'msg_error', array($e->getMessage()) );
            exit();
        }
        
        
        if( isset( $_POST['field_id'][$field_id] ) ){
            $field_id = "[" . $field_id . "]";
            
            $field['field_id'] = $this->form->validate('field_id' .  $field_id, 
                                                       'Field ID',
                                                       'integer', 
                                                       'required');   
        } else {
            $field_id = "";
        }
        
        $field['field_type'] = $this->form->validate('field_type' . $field_id, 
                                                      'Field Type',
                                                      'string', 
                                                      'required');
        $field['field_label'] = $this->form->validate('field_label' . $field_id, 
                                                      'Field Label', 
                                                      'string', 
                                                      'required|max_length[35]|min_length[3]');
        $field['field_instruction'] = $this->form->validate('field_instruction' . $field_id, 
                                                             'Field Instruction', 
                                                             'string', 
                                                             '');
        $field['field_required'] = $this->form->validate('field_required' . $field_id, 
                                                         'Field Required', 
                                                         'boolean');
        $field['owner_id'] = $this->form->validate('owner_id' . $field_id, 
                                                   'Owner ID', 
                                                   'integer', 
                                                   'required');

        $ft = $this->_getFieldType( $field['field_type'] );

        $field_settings = [];
        foreach( $ft['options'] as $option ){
            $required = $option['required'] ? 'required' : '';
            $field_settings[$option['short_name']] = 
                $this->form->validate( $option['short_name'] . $field_id,
                                       $option['label'],
                                       'string',
                                       $required);
        }

        if( isset($field_settings['options']) ){
            $options = explode(FIELD_OPTIONS_DELIMITER, $field_settings['options']);
            foreach($options as &$option){
                $option = trim( $option );
            }
            $field_settings['options'] = $options;
        }            

        $field['field_settings'] = serialize($field_settings);
        
        return $field;
    }
    
    /**
     * Add field
     * 
     * Firstly we check if we have post data and then save it
     */
    public function add(){  
        Session::init();
        require_once LIBS . 'FieldTypes/FieldType.php';

        if( isset( $_POST['submit'] ) ){
            $field = $this->_validateField();
            
            // If we have any validation errors we can save data
            if( empty( $this->form->errorMessages ) ){
                try{
                    $field_id = $this->model->save( $field );
                    Session::set( 'msg_success', "Field successfully added" );
                    header( "location: " . URL . "fields/edit/" . $field_id );
                } catch( Exception $e ){
                    Session::set( 'msg_error', $e->getMessage() );
                    header( "location: " . URL . "fields/get_list/" );
                }
            } else {
                Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
                Session::set( 'msg_error', $this->form->errorMessages );
                $this->view->token = Session::get( 'token' );  
                $this->view->setErrorFields( $this->form->errorFields );
            }            
            
            if( isset( $field['field_settings']['options'] ) ){
                $field['field_settings']['options'] = 
                        implode( FIELD_OPTIONS_DELIMITER, 
                                 $field['field_settings']['options'] );
            }    
        } else {  
            Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
            $this->view->token = Session::get( 'token' );  
            
            $field = array(
                'field_type' => '',
                'field_label' => '',
                'field_instruction' => '',
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
        require_once LIBS . 'FieldTypes/FieldType.php';
        
        if( isset( $_POST['submit'] ) ){
            // Check POST data
            try{
                $this->form = new Form();
            } catch (Exception $e) {
                header( "location: " . $_SERVER['HTTP_REFERER'] );
                Session::set( 'msg_error', array($e->getMessage()) );
                exit();
            }
            
            $msg_success = [];
            $msg_error = [];    
            $errorMessages = [];
            
            $countEntries = COUNT($_POST['field_id']);
            for( $i = 0; $i < $countEntries; $i++ ){
                $this->form->errorMessages = array();
                
                $field = $this->_validateField( $i );
                                
                // If we have any validation errors we can save data
                if( empty( $this->form->errorMessages ) ){
                    try{
                        $this->model->save( $field, $field['field_id'] );
                        $msg_success[] = "Field #" . $field['field_id'] . " successfully updated";
                    } catch( Exception $e ){
                        $msg_error[] = $e->getMessage();
                    }
                } else {
                    $errorMessages = array_merge( $errorMessages, $this->form->errorMessages );
                }
                
                
                $field['field_settings'] = unserialize($field['field_settings']);
                
                if( isset( $field['field_settings']['options'] ) ){
                    $field['field_settings']['options'] = 
                            implode( FIELD_OPTIONS_DELIMITER, 
                                     $field['field_settings']['options'] );
                }
                
                $fields[] = $field;
            }            
                        
            $fieldsErrors = array_keys( array_flip( $errorMessages ) );
            
            if( !empty( $fieldsErrors ) ){
                $this->view->setErrorFields( $this->form->errorFields );
            }    
            
            Session::set( 'msg_success', $msg_success );
            Session::set( 'msg_error', $fieldsErrors ); 
            
        } else {
            if( $field_id == null ){
                $field_ids = (array)$_POST['field_id'];
            } else {
                $field_ids[] = $field_id;
            }
            
            if( empty( $field_ids ) ){
                header( "location: " . URL . "fields/get_list/" );
            }
            
            $fields = array();
            foreach( $field_ids as $f_id ){
                $field = $this->model->getEntry( $f_id );
                if( !$field || empty($field) ){
                    continue;
                }
                
                $field = $field[0];
                
                $field['field_settings'] = unserialize($field['field_settings']);
                if( isset( $field['field_settings']['options'] ) ){
                    $field['field_settings']['options'] = 
                            implode( FIELD_OPTIONS_DELIMITER, 
                                     $field['field_settings']['options'] );
                }

                if( $field ){
                    $fields[] = $field;
                }
            }
            
            if( empty($fields) ){
                Session::delete( 'msg_error' );
                Session::delete( 'msg_success' );
                
                header( "location: " . URL . "fields/get_list/" );
                die();
            }
        }

        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );            
                
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
