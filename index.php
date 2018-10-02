<?php
require_once 'memorie.php';

//Gets all images in a folder.
//Make sure the image for the back of the cards is not in the folder.
$images = glob('images/*.{jpeg,jpg,gif,png}', GLOB_BRACE);

//Place your image for the back of the card as seccond prameter.
$memorie = new Memorie($images ,'background.jpg');

if(isset($_POST['again']) ) {
  $memorie->restart();
}

//send the clicked one to the turn methode.
for ($i=0; $i < $memorie->getSize(); $i++) {
  if(isset($_POST[$i])){
    $memorie->turn($_POST);
  }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Memory</title>
    <link rel="stylesheet" href="Css/style.css">
	 <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="JS/Javascript.js"> </script>
  </head>
  <body>
    <h2>Memorie <?php echo $memorie->wonTheGame(); ?></h2>
    <?php $memorie->loadField(); ?>

  </body>
</html>
