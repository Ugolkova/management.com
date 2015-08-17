<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->title; ?></title>
    </head>
    <body>
        <h1>Home Page</h1>
        <style type="text/css">
            body{
                padding: 50px;
            }
            form{
                background: #F1F1F1;
                border: none;
                border-radius: 20px;
                padding: 50px;
                vertical-align: top;
                width: 300px;
            }
            input[type="text"], input[type="submit"], select{
                background: #FFF;
                border: 1px solid #F1F1F1;
                border-radius: 4px;
                margin-top: -4px;
                padding: 5px;
                width: 170px;
            }
            input[type="text"]:focus, select:focus{
                background: lightyellow;
                border: 1px solid red;
            }
            input[type="submit"]{
                background: blue;
                border-color: blue;
                color: #FFF;
                display: inline-block;
                float: right;
                position: relative;
                right: 50px;
                width: 60px;
            }
            input[type="submit"]:hover{
                background-color: lightblue;
                border-color: lightblue;
                cursor: pointer;
            }
            label{
                display: inline-block;
                margin-right: 20px;
                position: relative;
                text-align: right;
                vertical-align: top;
                width: 70px;
            }
            label > s{
                color: red;
                position: absolute;
                right: -13px;
                text-decoration: none;
                vertical-align: top;
            } 
            div div > label{
                margin: 0px;
                text-align: left;
            }
            
            form div{
                margin: 15px 0px;
            }
            form div > div{
                display: inline-block;
                margin: 0px;
                width: auto;
            }
            img.imgField{
                height: 50px;
                width: auto;
            }
        </style>    
        <form action="/index/add_user" method="POST">
        
        <?php 
        if(!empty($this->formFields)){
            foreach($this->formFields as $field){
                echo "<div>" . $field . "</div>";
            }
        }
        ?>
        
        <p><input type="submit" name="submit" value="Send" /></p>
        
        </form>
    </body>
</html>