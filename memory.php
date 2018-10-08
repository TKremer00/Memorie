<?php
session_start();

class Memory{

  private $size;
  private $turnt ,$turnt_image , $image_id  = [];
  private $notTurnt_Image = '';
  private $allowedToSwitch = true;

  function __construct(array $images = [] , $notTurnt_Image){
    //make sure the cardes are double
    $this->turnt_image = array_merge($images, $images);
    $this->size = count($this->turnt_image);
    $this->notTurnt_Image = $notTurnt_Image;
    $this->image_id = $this->turnt = array_fill(0, $this->size, 'false');

    $this->sessionExist('turnt_image', $this->turnt_image);
    $this->sessionExist('turnt' , $this->turnt);

    $this->needShaking();
  }

  //Check if you need to shake the cards.
  private function needShaking(){
    if(isset($_SESSION['image_id']) && isset($_SESSION['turnt_image'])){
      $this->image_id = $_SESSION['image_id'];
      $this->turnt_image = $_SESSION['turnt_image'];
    }else{
      $this->shakeCards();
    }
  }

  //if not isset session fill session whit the value
  private function sessionExist(string $nameSession , $value){
    if(!isset($_SESSION[$nameSession]))
        $_SESSION[$nameSession] = $value;
  }

  //random the position of the cards
  private function shakeCards(){
    $_SESSION['Message'] =  "Shaking the cards";
    $half = floor($this->size / 2);

    //set the id's for the cards;
    for ($i=0; $i < $half; $i++) {
      $this->image_id[$i] = $i;
      $this->image_id[$half + $i] = $i;
    }

    $this->turnt_image = $this->changeImagePosition($this->turnt_image);
    $this->image_id = $this->imageIdSamePos($this->image_id);
    $this->sessionExist('image_id', $this->image_id);
    $this->sessionExist('turnt_image', $this->turnt_image);
  }

  //shuffle the images but keep the keys
  private function changeImagePosition(array $list = []) {
    $keys = array_keys($list);
    shuffle($keys);
    $random = [];

    foreach ($keys as $key) {
      $random[$key] = $list[$key];
    }
    return $random;
  }

  //sets the keys of the turnt_images as the image_id.
  private function imageIdSamePos(array $a=[]){
    $b = array_keys($this->turnt_image);
    $c = [];

    foreach($b as $index) {
      array_push($c, $a[$index]);
    }
    return $c;
  }

  //sets an array to the keys of image_id
  private function getKeys(array $a=[]){
    $array = [];

    foreach ($a as $key => $value) {
      array_push($array,$key );
    }

    return $array;
  }

  //Make the cards visible
  public function loadField(){
    $temp_array_ids = $this->getKeys($this->image_id);
    $this->allowedToSwitch && isset($_SESSION['turnt']) ? $this->turnt = $_SESSION['turnt'] : $this->allowedToSwitch = true;

    echo "<div> \n <form action='' method='post'>\n";

    for ($i=0; $i < $this->size; $i++){
      echo "<div id='images'>\n";
      echo '<button class="image" name="'.$temp_array_ids[$i].'"';
      echo $this->turnt[$i] != 'false' ? 'disabled><img src="' .$this->turnt_image[$this->image_id[$temp_array_ids[$i]]] : '><img src="' .$this->notTurnt_Image;
      echo '" ></button>';
      echo "</div>\n";
    }

    echo "<div id='button'>\n" . '<input type="submit" name="again" value="Restart">' . "</div>\n </form>\n";
  }

  //Check if user clicked the same image. and if its the same one.
  public function turn(array $post = []){
    $_SESSION['turnt'][key($post)] = 'true';
    $this->turnt = $_SESSION['turnt'];

    if(!isset($_SESSION['lastNumber'])){
      $_SESSION['lastNumber'] = key($post);
    }else{
      if($this->image_id[key($post)] != $this->image_id[$_SESSION['lastNumber']]){
        //Anser was wrong.
        $this->allowedToSwitch = false;
        $_SESSION['turnt'][key($post)] = $_SESSION['turnt'][$_SESSION['lastNumber']] = 'false';
      }
      unset($_SESSION['lastNumber']);
    }
  }

  //Check if user won the game
  public function wonTheGame(){
    $message = '';
    if(isset($_SESSION['turnt'])){
      for ($i=0; $i < $this->size; $i++) {
        $message = $_SESSION['turnt'][$i] == 'false' ? 'n' : $message;
      }
      return $message == 'n' ? '' : ', U won the game';
    }
  }

  //restart the game
  public static function restart(){
    session_unset();
  }

  //Get the size variable
  public function getSize(){
    return $this->size;
  }

}

?>
