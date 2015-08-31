<?php

class Fields extends Controller {
    
    public $availableFieldTypes = [];

    function __construct() {
        parent::__construct();
        
        $this->availableFieldTypes = array("text", "select", "file");
    }

    /**
     * Add field
     * 
     * Firstly we check if we have post data and then save it
     */
    public function add(){        
        if( $this->request->post( 'submit' ) ){            
            $field['field_type'] = $this->request->post('field_type', 'string');
            $field['field_label'] = $this->request->post('field_label', 'string');
            $field['field_instructions'] = $this->request
                                                ->post('field_instructions', 'string');
            $field['field_required'] = $this->request
                                            ->post('field_required', 'boolean');
            $field['owner_id'] = $this->request->post('owner_id', 'integer');                

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
    public function edit( $field_id ){        
        if( $this->request->post( 'submit' ) ){            
            $field['field_type'] = $this->request->post('field_type', 'string');
            $field['field_label'] = $this->request->post('field_label', 'string');
            $field['field_instructions'] = $this->request
                                                ->post('field_instructions', 'string');
            $field['field_required'] = $this->request
                                            ->post('field_required', 'boolean');
            $field['owner_id'] = $this->request->post('owner_id', 'integer');                

            $field = $this->_save( $field, $field_id );    
        } else {
            $field = $this->model->getField( $field_id )[0];
            if( !$field ){
                header( "location: " . URL . "users/get_list/" );
                die();
            }
        }
                
        // Set settings value we already have
        $fieldTypes = $this->_getFieldTypes();
        $field_settings = unserialize($field['field_settings']);
        foreach($fieldTypes as &$ft){
            if( !empty( $ft['options'] ) ){
                foreach( $ft['options'] as &$option ){
                    if( !empty( $field_settings ) ){
                        foreach( $field_settings as $k=>$v ){
                            if( $option['short_name'] == $k ){
                                $option['value'] = $v;
                            }
                        }
                    }
                }
            }    
        }
        
        $this->view->setTitle("Edit Field" );
        $this->view->setHeader( "Edit Field" );
        $this->view->field = $field;
        $this->view->fieldTypes = $fieldTypes;
        $this->view->fieldId = $field_id;
        $this->view->render("fields/add");
    }
    
    /**
     * Save Field
     * 
     * @param array $field
     * @param integer|boolean $field_id
     * @return array
     */
    private function _save( $field, $field_id = FALSE ){
        try{
            require_once LIBS . 'FieldTypes/FieldType.php';
            $ft = $this->_getFieldType( $field['field_type'] );
            foreach( $ft['options'] as $option ){
                $field_settings[$option['short_name']] = 
                        $this->request->post( $option['short_name'] );
            }

            $field['field_settings'] = serialize($field_settings);
            
            $this->model->save( $field, $field_id );
            if( $field_id ){
                Session::set( 'message', array('success' => 'Field is updated') );
            } else {
                Session::set( 'message', array('success' => 'Field is added') );
            }
            
            header("location: " . URL . 'fields/get_list');                
        } catch(Exception $e) {
            Session::set( 'message', array('error' => $e->getMessage()) );
        }
        
        return $field;
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
    
    public function get_list(){
        echo 'Get list';
    }

}
