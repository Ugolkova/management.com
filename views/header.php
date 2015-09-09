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
            <?php if(is_string($message['error']) ) : ?>
            <p><?php echo $message['error']; ?></p>               
            <?php elseif( is_array($message['error']) ) :
                foreach($message['error'] as $error): ?>
            <p><?php echo $error; ?></p>   
            <?php endforeach; endif; ?>
        </div>        
        <?php elseif( isset( $message['success'] ) && $message['success'] != '' ): ?>
        <div class="message success">
            <i>x</i>
            <?php if(is_string($message['success']) ) : ?>
            <p><?php echo $message['success']; ?></p>               
            <?php elseif( is_array($message['success']) ) :
                foreach($message['success'] as $success): ?>
            <p><?php echo $success; ?></p>   
            <?php endforeach; endif; ?>
        </div> 
    <?php endif; endif; ?>

    <section>            
        <h1><?php echo $this->header; ?></h1>   
            <div>