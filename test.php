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
            
            .pagination a.first,
            .pagination a.last,
            .pagination a.prev,
            .pagination a.next{
                background-position: center top;
                background-repeat: no-repeat;
                height: 13px;
                width: 13px;
            }  
            .pagination a.first{
                background-image: url(public/img/pagination_first_button.gif);
            }            
            .pagination a.last{
                background-image: url(public/img/pagination_last_button.gif);
            }      
            .pagination a.prev{
                background-image: url(public/img/pagination_prev_button.gif);
            }      
            .pagination a.next{
                background-image: url(public/img/pagination_next_button.gif);
            }                  
        </style>
    </head>
    <body>
        <?php
            require_once 'libs/Pagination.php';
            $pagination = new Pagination();
            echo $pagination->createLinks($_GET['page'], 'test.php?page=', 182);
        ?> 
    </body>
</html>