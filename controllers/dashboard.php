<?php

class Dashboard extends Controller {
    
    function logout()
    {
        Session::destroy();
        header('location: ' . URL .  'login/');
        exit;
    }

}

