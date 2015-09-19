<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->title; ?></title>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        
        <script src="<?php echo URL; ?>public/js/default.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/default.css" />
    </head>
    <body>    
    <?php echo $this->printMessage(); ?>

    <a id="auth" href="<?php echo URL; ?>dashboard/logout/" title="logout">Logout</a>
   
    <menu>
        <li>
            <a href="<?php echo URL; ?>users/get_list" title="">Users</a>
            <ul>
                <li>
                    <a href="<?php echo URL; ?>users/get_list" title="Users">Users</a>
                </li>
                <li>
                    <a href="<?php echo URL; ?>users/add" title="Add User">Add User</a>
                </li>

            </ul>
        </li>
        <li>
            <a href="<?php echo URL; ?>fields/get_list" title="">Fields</a>
            <ul>
                <li class="active">
                    <a href="<?php echo URL; ?>fields/get_list" title="Fields">Fields</a>
                </li>
                <li>
                    <a href="<?php echo URL; ?>fields/add" title="Add Field">Add Field</a>
                </li>

            </ul>
        </li>        
        <li>
            <a href="<?php echo URL; ?>projects/get_list" title="">Projects</a>
            <ul>
                <li>
                    <a href="<?php echo URL; ?>projects/get_list" title="Projects">Projects</a>
                </li>
                <li>
                    <a href="<?php echo URL; ?>projects/add" title="Add Project">Add Project</a>
                </li>

            </ul>
        </li>         
    </menu>    
        
    <section>            
        <h1><?php echo $this->header; ?></h1>   
            <div>