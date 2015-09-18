<?php

class Users extends Controller {
    private $_userTypes = array("lm", "pm");
    
    function __construct() {
        parent::__construct();
        Session::set('user_id', 1);
    }
    
    function index(){
        $this->get_list();
    }
    
    protected function _validateData( $id = FALSE ){
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
            $field_id = "";
        }
        
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
                                                    'required|max_length[35]|min_length[3]');
        $user['user_skype'] = $this->form->validate('user_skype' . $id, 
                                                    'Field Instructions', 
                                                    'string', 
                                                    'required');
        
        $ownerFields = $this->model->getFieldsData();
        foreach($ownerFields as $field){
            $user['field_' . $field['field_id']] = $this->form->validate('field_' . $field['field_id'] . $id, 
                                                    $field['field_label'], 
                                                    'string', 
                                                    $field['field_required'] ? 'required' : '');
        }
        
        
        return $user;
    }
    
    
    
    
    /**
     * This function we use to get list of users according to two parameters -
     * page and list type (LM list, PM list)
     * get_list
     * get_list/p12
     * get_list/pm/p45
     * get_list/lm/p2
     * @param type $param1
     * @param type $param2
     */
    public function get_list( $param1 = null, $param2 = null ){        
        $page = 1;
        $userType = null;

        if( !is_null($param1) ){
            if(preg_match('/^p(\d+)$/', $param1, $matches) === 1){
                $page = (int)$matches[1];
            } else {
                $param1 = strtolower($param1);
                if(in_array($param1, $this->_userTypes)){
                    $userType = $param1;
                    if(preg_match('/^p(\d+)$/', $param2, $matches) === 1){
                        $page  = (int)$matches[1];
                    }
                }    
            }
        }
        
        $usersArr   = $this->model->getList($page, $userType);
        $usersCount = $this->model->getRowsCount();
        
        $this->view->users      = $usersArr;
        $this->view->usersCount = $usersCount;
        
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );         
        
        $pagination = new Pagination();
        $this->view->pagination = $pagination->createLinks($page, $usersCount);
        
        $this->view->render("users/list");
    }
    
    public function add(){
        if(isset($_POST['submit'])){
            if( parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== HOST ){
                header('location: ' . URL . 'users');
                exit;
            } else {
                $this->model->add();
            }
        } else {
            $this->model->add();
            $this->view->render("users/add");
        }    
    }
    
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

            $countEntries = COUNT($_POST['user_id']);
            for( $i = 0; $i < $countEntries; $i++ ){
                $this->form->errorMessages = array();
                
                $user = $this->_validateData( $i );
                                
                // If we have any validation errors we can save data
                if( empty( $this->form->errorMessages ) ){
                    try{
                        $this->model->save( $user, $user['user_id'] );
                        $msg_success[] = "User #" . $user['user_id'] . " successfully updated";
                    } catch( Exception $e ){
                        $msg_error[] = $e->getMessage();
                    }
                } else {
                    $errorMessages = array_merge( $errorMessages, $this->form->errorMessages );
                }
               

                
                
                $users[] = $user;
            }            
                        
            $fieldsErrors = array_keys( array_flip( $errorMessages ) );
            
            if( !empty( $fieldsErrors ) ){
                $this->view->setErrorFields( $this->form->errorFields );
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
                header( "location: " . URL . "users/get_list/" );
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
                
                //header( "location: " . URL . "users/get_list/" );
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
                $u_fields[] = $u_field->render($f_data); 
            }

            $this->view->user_fields[$k] = $u_fields;            
        }
        
        
        Session::set( 'token', md5( uniqid( mt_rand(), true ) ) );
        $this->view->token = Session::get( 'token' );  
        
        $this->view->users = $users;
        $this->view->user_id = $user_id;
        $this->view->render("users/edit");
        
    }
}

