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
    
    <section>            
        <h1><?php echo $this->header; ?></h1>   
            <div>