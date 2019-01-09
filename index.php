<?php
require_once 'memory.php';


if(isset($_POST['again']) ) {
  Memory::restart();
}

if(!isset($_SESSION['screenWidth'])){
    header('Location: getWidth.php');
    exit;
}

//Gets all images in a folder.
//Make sure the image for the back of the cards is not in the folder.
$images = glob('images'.DIRECTORY_SEPARATOR.'*.{jpeg,jpg,gif,png}', GLOB_BRACE);

//Place your image for the back of the card as seccond prameter.
$memory = new Memory($images ,'background.jpg');

//Send the clicked one to the turn methode.
for ($i=0; $i < $memory->getSize(); $i++) {
  if(isset($_POST[$i])){
    $memory->turn($_POST);
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Memory</title>
    <link rel="stylesheet" href="Css<?php echo DIRECTORY_SEPARATOR; ?>style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="JS<?php echo DIRECTORY_SEPARATOR; ?>Javascript.js"> </script>
    <style type="text/css">
        .width {
            width: calc(<?php echo $_SESSION['screenWidth'] / $memory->numPerRow();?>px - 4px);
        }
    </style>
  </head>
  <body>
    <h2>Memory <?php echo $memory->wonTheGame(); ?></h2>
    <?php echo $memory->loadField(); ?>
    <p class="textCenter">Turns : <?php echo $memory->getTurns(); ?> | Completion : <?php echo $memory->getCompletion(); ?></p>
    <br>
</body>
</html>
<?php
?>
