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
        
    <?php   
    $message = Session::get( 'message' );
    
    if( !empty($message) ):
        if( isset( $message['error'] ) && $message['error'] != '' ): ?>
        <div class="message error">
            <i>x</i>
            <?php echo $message['error']; ?>
        </div>        
        <?php elseif( isset( $message['success'] ) && $message['success'] != '' ): ?>
        <div class="message success">
            <i>x</i>
            <?php echo $message['success']; ?>
        </div>    
    <?php endif; endif; ?>

    <section>            
        <h1><?php echo $this->header; ?></h1>   
            <div>