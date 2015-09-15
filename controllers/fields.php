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
        Session::init();
        require_once LIBS . 'FieldTypes/FieldType.php';

        if( isset( $_POST['submit'] ) ){
            try{
                $this->form = new Form();
            } catch (Exception $e) {
                header( "location: " . $_SERVER['HTTP_REFERER'] );
                Session::set( 'message', array('error' => array($e->getMessage())) );
                exit();
            }
            

            $field['field_type'] = $this->form->validate('field_type', 
                                                          'Field Type',
                                                          'string', 
                                                          'required');
            $field['field_label'] = $this->form->validate('field_label', 
                                                          'Field Label', 
                                                          'string', 
                                                          'required|max_length[35]|min_length[3]');
            $field['field_instructions'] = $this->form->validate('field_instructions', 
                                                                 'Field Instructions', 
                                                                 'string', 
                                                                 'required');
            $field['field_required'] = $this->form->validate('field_required', 
                                                             'Field Required', 
                                                             'boolean');
            $field['owner_id'] = $this->form->validate('owner_id', 
                                                       'Owner ID', 
                                                       'integer', 
                                                       'required');

            $ft = $this->_getFieldType( $field['field_type'] );

            $field_settings = [];
            foreach( $ft['options'] as $option ){
                $required = $option['required'] ? 'required' : '';
                $field_settings[$option['short_name']] = 
                    $this->form->validate( $option['short_name'],
                                           $option['label'],
                                           'string',
                                           $required);
            }

            $field['field_settings'] = serialize($field_settings);
            
            // If we have any validation errors we can save data
            if( empty( $this->form->errorMessages ) ){
                try{
                    $field_id = $this->model->save( $field );
                    Session::set( 'message', array('success' => "Field successfully added") );
                    header( "location: " . URL . "fields/edit/" . $field_id );
                } catch( Exception $e ){
                    Session::set( 'message', array('error' => $e->getMessage()) );
                    header( "location: " . URL . "fields/get_list/" );
                }
            } else {
                Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
                Session::set( 'message', array('error' => $this->form->errorMessages) );
                $this->view->token = Session::get( 'token' );  
                $this->view->setErrorFields( $this->form->errorFields );
            }            
        } else {  
            Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
            $this->view->token = Session::get( 'token' );  
            
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
        require_once LIBS . 'FieldTypes/FieldType.php';

        if( isset( $_POST['submit'] ) ){
            try{
                $this->form = new Form();
            } catch (Exception $e) {
                header( "location: " . $_SERVER['HTTP_REFERER'] );
                Session::set( 'message', array('error' => array($e->getMessage())) );
                exit();
            }
            
            $msg_success = [];
            $msg_error = [];    
            
            $countEntries = COUNT($_POST['field_id']);
            for( $i = 0; $i < $countEntries; $i++ ){
                /*$f_id = $this->form->setToRightType("field_id[$i]", 'integer');
                
                $field['field_type'] = $this->form
                                            ->setToRightType("field_type[$i]", 'string');
                $field['field_label'] = $this->form
                                             ->setToRightType("field_label[$i]", 'string');
                $field['field_instructions'] = $this->form
                                                    ->setToRightType("field_instructions[$i]", 'string');
                $field['field_required'] = $this->form
                                                ->setToRightType("field_required[$i]", 'boolean');
                $field['owner_id'] = $this->form->setToRightType("owner_id[$i]", 'integer');                
                
                $ft = $this->_getFieldType( $field['field_type'] );
                
                $field_settings = [];
                foreach( $ft['options'] as $option ){
                    $field_settings[$option['short_name']] = 
                            $this->form->setToRightType( $option['short_name'] . "[$i]" );
                }

                $field['field_settings'] = serialize($field_settings);
               
                $fields[] = $field;
                try{
                    $this->model->save( $field, $f_id );
                    $msg_success[] = "Field #" . $f_id . " successfully updated";
                } catch( Exception $e ){
                    $msg_error[] = $e->getMessage();
                }    
                */

                $field['field_id'] = $this->form->validate('field_id[' . $i . ']', 
                                                           'Field ID',
                                                           'integer', 
                                                           'required');
                
                $field['field_type'] = $this->form
                                            ->validate('field_type[' . $i . ']', 
                                                       'Field Type',
                                                       'string', 
                                                       'required');
                $field['field_label'] = $this->form
                                             ->validate('field_label[' . $i . ']', 
                                                        'Field Label', 
                                                        'string', 
                                                        'required|max_length[35]|min_length[3]');
                $field['field_instructions'] = $this->form
                                                    ->validate('field_instructions[' . $i . ']', 
                                                               'Field Instructions', 
                                                               'string', 
                                                               'required');
                $field['field_required'] = $this->form
                                                ->validate('field_required[' . $i . ']', 
                                                           'Field Required', 
                                                           'boolean');
                $field['owner_id'] = $this->form->validate('owner_id[' . $i . ']', 
                                                           'Owner ID', 
                                                           'integer', 
                                                           'required');

                $ft = $this->_getFieldType( $field['field_type'] );

                $field_settings = [];
                foreach( $ft['options'] as $option ){
                    $required = $option['required'] ? 'required' : '';
                    $field_settings[$option['short_name']] = 
                        $this->form->validate( $option['short_name'] . "[$i]",
                                               $option['label'],
                                               'string',
                                               $required);
                }

                $field['field_settings'] = serialize($field_settings);
                
                $fields[] = $field;
                
                /*try{
                    $this->model->save( $field, $f_id );
                    $msg_success[] = "Field #" . $f_id . " successfully updated";
                } catch( Exception $e ){
                    $msg_error[] = $e->getMessage();
                }*/
                
                // If we have any validation errors we can save data
                if( empty( $this->form->errorMessages ) ){
                    try{
                        $this->model->save( $field, $field['field_id'] );
                        $msg_success[] = "Field #" . $field['field_id'] . " successfully updated";
                    } catch( Exception $e ){
                        $msg_error[] = $e->getMessage();
                    }
                } else {
                    print_r($this->form->errorMessages);
                    array_merge($msg_error, $this->form->errorMessages);
                } 
            }
            echo 'test:<pre>';
            print_r($msg_error);
            echo '</pre>';
            
            
            
            if( !empty( $msg_success ) ){
                Session::set( 'message', array('success' => $msg_success) );
            } 
            elseif( !empty( $msg_error ) ) {
                Session::set( 'message', array('error' => $msg_error) );     
            } 
            
            if( $field_id === NULL ) {
                //header( "location: " . URL . "fields/get_list/" );
            } else {
                header( "location: " . $_SERVER['REQUEST_URI'] );
            }
            
        } else {
            Session::init();
            Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
            $this->view->token = Session::get( 'token' );            

            $fields = array();
            
            /*
             * If we have field id in URL we use it for getting $field,
             * otherwise we use (array)$_POST['field_id']
             */
            if( $field_id == null ){
                $field_ids = (array)$_POST['field_id'];
                if( !is_array( $field_ids ) || empty( $field_ids ) ){
                    header( "location: " . URL . "fields/get_list/" );
                }
                foreach( $field_ids as $f_id ){
                    $field = $this->model->getField( $f_id )[0];
                    $field['field_settings'] = unserialize($field['field_settings']);
                    
                    if( $field ){
                        $fields[] = $field;
                    }
                }
            } else {
                $field_ids[] = $field_id; 
                $field = $this->model->getField( $field_id );  
                if( $field ){
                    $field = $field[0];
                    $field['field_settings'] = unserialize($field['field_settings']);
                    if( $field ){
                        $fields[] = $field;
                    }
                }    
            }
            
            if( empty($fields) ){
                Session::set('message', array('error' => 'Fields ( #' . implode(', #', $field_ids) . ' ) not found.'));
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
