<?php ini_set('display_errors', 1); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <style>
            a, strong{
                display: inline-block;
                margin: 0px 5px;
            }
            a{
                color: #E8496A;
            }
            a:first-child, a:last-child{
                background-color: #E8496A;
                color: #FFF;
                padding: 2px 5px;
            }
        </style>
    </head>
    <body>
        <?php
            require_once 'libs/Pagination.php';
            $pagination = new Pagination();
        ?> 
    </body>
</html>