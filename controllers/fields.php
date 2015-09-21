<?php

/**
 * Fields 
 * 
 * don't forget we must implement all the methods of interface CRUD
 */
class Fields extends Controller implements CRUD{
    
    /**
     * Available field types
     * 
     * @var  array All available field types
     * @desc If you push a field type to this array you'll see it by 
     *       adding/editing field(s) 
     */
    public $availableFieldTypes = ["text", "select", "file"];

    function __construct() {
        parent::__construct();        
    }
    
    /**
     * Index
     * 
     * @desc Standard method. 
     *       We use it mostly for links like 'http://example.com/fields/'
     */
    public function index(){
        $this->get_entries();
    }
    
    /**
     * Get Users according to user type and page number
     * 
     * @param string|NULL $param1 It can be wheter user type like 'lm' OR 'pm' or page
     * @param string|NULL $param2 When this parameter doesn't equall NULL it's page
     * @desc This method implements method get_entries() interface's CRUD. It's used as for backend so for frontend (autocomplete jQuery function)
     * @access public
     */
    public function get_entries( $param1 = NULL, $param2 = NULL ){  
        $page = $param1;
        
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
        
        // Use json_encode() output when this method is used for frontend autocomplete
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
    
    /**
     * Validate input data
     * 
     * @param int|boolean $id Parameter is set for case we validate array values like $_POST['field_id'][3] instead of $_POST['field_id']
     * @access private
     * @return array Field data
     */
    private function _validateData( $id = FALSE ){
        try{
            $this->form = new Form();
        } catch (Exception $e) {
            header( "location: " . $_SERVER['HTTP_REFERER'] );
            Session::set( 'msg_error', array($e->getMessage()) );
            exit();
        }
        
        
        if( isset( $_POST['field_id'][$id] ) ){
            $id = "[" . $id . "]";
            
            $field['field_id'] = $this->form->validate('field_id' .  $id, 
                                                       'Field ID',
                                                       'integer', 
                                                       'required');   
        } else {
            $id = "";
        }
        
        $field['field_type'] = $this->form->validate('field_type' . $id, 
                                                      'Field Type',
                                                      'string', 
                                                      'required');
        $field['field_label'] = $this->form->validate('field_label' . $id, 
                                                      'Field Label', 
                                                      'string', 
                                                      'required|max_length[35]|min_length[3]');
        $field['field_instruction'] = $this->form->validate('field_instruction' . $id, 
                                                             'Field Instruction', 
                                                             'string', 
                                                             '');
        $field['field_required'] = $this->form->validate('field_required' . $id, 
                                                         'Field Required', 
                                                         'boolean');
        $field['owner_id'] = $this->form->validate('owner_id' . $id, 
                                                   'Owner ID', 
                                                   'integer', 
                                                   'required');

        $ft = $this->_getFieldType( $field['field_type'] );

        $field_settings = [];
        foreach( $ft['options'] as $option ){
            $required = $option['required'] ? 'required' : '';
            $field_settings[$option['short_name']] = 
                $this->form->validate( $option['short_name'] . $id,
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
     * @desc Save field if we have post data or show form to add field
     */
    public function add(){  
        Session::init();
        require_once LIBS . 'FieldTypes/FieldType.php';

        if( isset( $_POST['submit'] ) ){
            $field = $this->_validateData();
            
            // If we have any validation errors we can save data
            if( empty( $this->form->errorMessages ) ){
                try{
                    $field_id = $this->model->save( $field );
                    Session::set( 'msg_success', "Field successfully added" );
                    header( "location: " . URL . "fields/edit/" . $field_id );
                } catch( Exception $e ){
                    Session::set( 'msg_error', $e->getMessage() );
                    header( "location: " . URL . "fields/get_entries/" );
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
     * Edit field data
     * 
     * @param int|boolean $field_id Use it if you edit just one field
     * @desc If there isn't any $id and any post data redirect user on get_entries page
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
                
                $field = $this->_validateData( $i );
                                
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
                header( "location: " . URL . "fields/get_entries/" );
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
                
                header( "location: " . URL . "fields/get_entries/" );
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
     * @return array This method returns us array we can use to show user field options
     * @access private
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
     * Get field types
     * 
     * @desc We use this method by adding or editing fields to show user all available options of different field types
     * @return array
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


// PATH: controllers/fields.php