<?php

/**
 * Users 
 * 
 * don't forget we must implement all the methods of interface CRUD
 */
class Users extends Controller implements CRUD{
    private $_userTypes = array("lm", "pm");
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Index
     * 
     * @desc Standard method. 
     *       We use it mostly for links like 'http://example.com/users/'
     */    
    function index(){
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
    public function get_entries( $param1 = null, $param2 = null ){        
        $page = 1;
        $user_type = null;

        if( !is_null($param1) ){
            if(preg_match('/^p(\d+)$/', $param1, $matches) === 1){
                $page = (int)$matches[1];
            } else {
                $param1 = strtolower($param1);
                if(in_array($param1, $this->_userTypes)){
                    $user_type = $param1;
                    if(preg_match('/^p(\d+)$/', $param2, $matches) === 1){
                        $page  = (int)$matches[1];
                    }
                }    
            }
        }
        
        $usersArr   = $this->model->getList($page, $user_type);
        $usersCount = $this->model->getRowsCount();        
        
        $this->view->users      = $usersArr;
        $this->view->usersCount = $usersCount;
        
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );         
        
        $pagination = new Pagination();
        $this->view->pagination = $pagination->createLinks($page, $usersCount);
        
        $this->view->searchKey = $this->model->searchKey;        

        if( $this->model->searchAutocomplete ){
            $autocompleteArr = [];
            foreach($usersArr as $user){
                $autocompleteArr[] = array( 'link' => URL . 'users/edit/' . 
                                                      $user['user_id'] . '/',
                                            'name' => $user['user_name'] );
            }
            echo json_encode($autocompleteArr);
        } else {
            $this->view->render("users/list");
        }    
    }

    /**
     * Validate input data
     * 
     * @param int|boolean $id Parameter is set for case we validate array values like $_POST['user_id'][3] instead of $_POST['user_id']
     * @access private
     * @return array User data
     */    
    private function _validateData( $id = FALSE ){
        try{
            $this->form = new Form();
        } catch (Exception $e) {
            header( "location: " . $_SERVER['HTTP_REFERER'] );
            Session::set( 'msg_error', array($e->getMessage()) );
            exit();
        }
        
        if( isset( $_POST['user_id'][$id] ) ){
            $id = "[" . $id . "]";
            
            $user['user_id'] = $this->form->validate('user_id' .  $id, 
                                                     'User ID',
                                                     'integer', 
                                                     'required');   
        } else {
            $id = "";
        }
        
        $user['owner_id'] = $this->form->validate('owner_id' .  $id, 
                                                  'Owner ID',
                                                  'integer', 
                                                  'required');   

        if( $user['owner_id'] == Session::get('user_id') ||
            Session::get('user_type') == 'admin' ){
            
            $user['user_login'] = $this->form->validate('user_login' . $id, 
                                                        'User Login',
                                                        'string', 
                                                        'required');
            $user['user_name'] = $this->form->validate('user_name' . $id, 
                                                        'User Name',
                                                        'string', 
                                                        'required');

            $user['user_email'] = $this->form->validate('user_email' . $id, 
                                                        'User Email', 
                                                        'string', 
                                                        'required|maxlength[35]|minlength[3]');
            $user['user_skype'] = $this->form->validate('user_skype' . $id, 
                                                        'User Skype', 
                                                        'string', 
                                                        'required');
        }
        
        $user['user_type'] = $this->form->validate('user_type' . $id, 
                                                    'User Type', 
                                                    'string', 
                                    'required|in_array[admin, manager, user]');
        
        // Just admin is available to create LMs and PMs
        if( Session::get( 'user_type' ) !== 'admin' ){
            $user['user_type'] = 'user';
        }
        
        $ownerFields = $this->model->getFieldsData();
        foreach($ownerFields as $field){
            $user['field_' . $field['field_id']] = $this->form->validate('field_' . $field['field_id'] . $id, 
                                                    $field['field_label'], 
                                                    'string', 
                                                    $field['validate_action'] . 
                            ( $field['field_required'] ? 'required' : '' ) );    
        }
        
        return $user;
    }
    
    /**
     * Add User
     * 
     * @desc Save User if we have post data or show form to add User
     */    
    public function add(){  
        require_once LIBS . 'FieldTypes/FieldTypeFactory.php';        
        require_once LIBS . 'FieldTypes/FieldType.php';

        $u_fields = [];
        $fields_data = $this->model->getFieldsData();
          
        if( isset( $_POST['submit'] ) ){
            $user = $this->_validateData();
            // If we have any validation errors we can save data
            if( empty( $this->form->errorMessages ) ){
                try{
                    $user_id = $this->model->save( $user );
                    Session::set( 'msg_success', "User successfully added" );
                    header( "location: " . URL . "users/edit/" . $user_id );
                } catch( Exception $e ){
                    Session::set( 'msg_error', $e->getMessage() );
                    header( "location: " . URL . "users/get_entries/" );
                }
            } else {
                Session::set( 'msg_error', $this->form->errorMessages );
                $this->view->setErrorFields( $this->form->errorFields );
            }            
            
        } else {  
            $user = array(
                'owner_id' => Session::get( 'user_id' ),
                'user_login' => '',
                'user_name' => '',
                'user_email' => '',
                'user_skype' => ''
            );  
            foreach($fields_data as $f_data){
                $user['field_' . $f_data['field_id']] = '';
            }
        }

        foreach($fields_data as $f_data){
            $f_data['field_value'] = $user['field_' . $f_data['field_id']];
            $f_data['field_settings'] = unserialize($f_data['field_settings']);
            $u_field = FieldTypeFactory::build($f_data['field_type']);
            $f_data['field_name'] = 'field_' . $f_data['field_id'];
            $u_fields[] = $u_field->render($f_data); 
        }

        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );  
        
        $this->view->user_fields = $u_fields;            
        
        $this->view->setTitle("Add User" );
        $this->view->setHeader( "Add User" );
        $this->view->user = $user;
        $this->view->userId = FALSE;
        $this->view->render("users/add");
    }
    
    /**
     * Edit User Data
     * 
     * @param int|boolean $user_id Use it if you edit just one User
     * @desc If there isn't any $user_id and any $_POST['user_id'] redirect User on get_entries page
     */    
    public function edit( $user_id = NULL ){
        require_once LIBS . 'FieldTypes/FieldTypeFactory.php';        
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
            $errorFields = [];

            $countEntries = COUNT($_POST['user_id']);
            for( $i = 0; $i < $countEntries; $i++ ){
                $this->form->errorMessages = [];
                $this->form->errorFields = [];
                
                $user = $this->_validateData( $i );
                
                // If we have any validation errors we can save data
                if( empty( $this->form->errorMessages ) ){
                    try{
                        $this->model->save( $user );
                        $msg_success[] = "User #" . $user['user_id'] . " successfully updated";
                    } catch( Exception $e ){
                        $msg_error[] = $e->getMessage();
                    }
                } else {
                    $errorMessages = array_merge( $errorMessages, $this->form->errorMessages );
                    $errorFields = array_merge( $errorFields, $this->form->errorFields );
                }
               
                if( $user['owner_id'] != Session::get('user_id') ){ 
                    $user = $this->model->getEntry( $user['user_id'] )[0];
                }
                
                $users[] = $user;
            }            
                        
            $fieldsErrors = array_keys( array_flip( $errorMessages ) );
            if( !empty( $errorFields ) ){
                $this->view->setErrorFields( $errorFields );
            }
            
            Session::set( 'msg_success', $msg_success );
            Session::set( 'msg_error', $fieldsErrors ); 
            
        } else {
            if( $user_id === null ){
                $user_ids = (array)$_POST['user_id'];
            } else {
                $user_ids[] = $user_id;
            }

            if( empty( $user_ids ) ){
                header( "location: " . URL . "users/get_entries/" );
            }
            $users = array();
            foreach( $user_ids as $u_id ){
                $user = $this->model->getEntry( $u_id )[0];

                if( $user ){
                    $users[] = $user;
                }                
            }
                        
            if( empty($users) ){
                Session::delete( 'msg_error' );
                Session::delete( 'msg_success' );
                
                header( "location: " . URL . "users/get_entries/" );
                die();
            }            
        } 
        
        foreach( $users as $k=>$user ){
            $u_fields = [];
            $fields_data = $this->model->getFieldsData();

            foreach($fields_data as $f_data){
                $f_data['field_value'] = $user['field_' . $f_data['field_id']];
                $f_data['field_settings'] = unserialize($f_data['field_settings']);
                $u_field = FieldTypeFactory::build($f_data['field_type']);
                $f_data['field_name'] = 'field_' . $f_data['field_id'] . 
                                        '[' . $k . ']';
                
                // Set error
                if( !empty( $errorFields ) && 
                        in_array( $f_data['field_name'], $errorFields ) ){
                    $f_data['field_error'] = TRUE;
                }    

                $u_fields[] = $u_field->render($f_data);
            }

            $this->view->user_fields[$k] = $u_fields;   
            
            $this->view->disabledStandardFields[$k] = '';
            
            if($user['owner_id'] != Session::get('user_id') &&
                    Session::get('user_type') != 'admin' ){
                $this->view->disabledStandardFields[$k] = 'disabled="disabled" ';
            }
            
            $this->view->lmUsers[$k] = 
                $this->model->getRelatedEntries($user['user_id'], 'lm');

            $this->view->pmUsers[$k] = 
                $this->model->getRelatedEntries($user['user_id'], 'pm');
        }
                
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );  
        
        $title = "Edit User";
        if( COUNT( $users ) > 1 ){
            $title = "Edit Users";
        }

        $this->view->setTitle( $title );
        $this->view->setHeader( $title );
        
        
        $this->view->users = $users;
        $this->view->user_id = $user_id;
        $this->view->render("users/edit");
        
    }    
}

