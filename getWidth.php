<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
    </head>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function getSize(){
            $('#inp_width').val($(window).width() / 100 * 85);
            $('#form_size').submit();
        }
    </script>
    <body <?php if(empty($_POST['width'])){echo 'onload="getSize()"';} ?>>
        <form method='post' id='form_size'>
            <input type='hidden' name='width' id='inp_width'/>
        </form>
        <?php
        if(!empty($_POST['width'])) {
            setcookie('seconds', 0, time()+3600, '/');
            setcookie('minutes', 0, time()+3600, '/');
            $_SESSION['screenWidth'] = $_POST['width'];
            header('Location: index.php');
            exit;
        }
        ?>
    </body>
</html>
