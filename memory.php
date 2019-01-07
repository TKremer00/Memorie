<?php
session_start();

class Memory{

    private $size, $turns;
    private $turnt, $turnt_image, $image_id  = [];
    private $notTurnt_Image = '';
    private $allowedToSwitch = true;

    function __construct(array $images = [] , $notTurnt_Image)
    {
        $this->turnt_image = array_merge($images, $images);
        $this->notTurnt_Image = $notTurnt_Image;
        $this->size = count($this->turnt_image);
        $this->image_id = $this->turnt = array_fill(0, $this->size, 0);

        $this->sessionExist('turnt_image', $this->turnt_image);
        $this->sessionExist('turnt' , $this->turnt);
        $this->sessionExist('turns', $this->turns);
        $this->needShaking();
    }

    //Check if you need to shake the cards.
    private function needShaking()
    {
        if(isset($_SESSION['image_id'], $_SESSION['turnt_image'])){
            $this->image_id = $_SESSION['image_id'];
            $this->turnt_image = $_SESSION['turnt_image'];
        }else {
            $this->shakeCards();
        }
    }

    //if not isset session fill session with the value
    private function sessionExist(string $nameSession , $value)
    {
        if(!isset($_SESSION[$nameSession]))
            $_SESSION[$nameSession] = $value;
    }

    //random the position of the cards
    private function shakeCards()
    {
        $half = floor($this->size / 2);
        //set the id's for the cards
        for ($i=0; $i < $half; $i++) {
            $this->image_id[$i] = $this->image_id[$half + $i] = $i;
        }

        $this->turnt_image = $this->changeImagePosition($this->turnt_image);
        $this->image_id = $this->imageIdSamePos($this->image_id);
        $this->sessionExist('image_id', $this->image_id);
        $this->sessionExist('turnt_image', $this->turnt_image);
    }

    //shuffle the images but keep the keys
    private function changeImagePosition(array $list = [])
    {
        $keys = array_keys($list);
        shuffle($keys);
        $random = [];

        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

    //sets the keys of the turnt_images as the image_id.
    private function imageIdSamePos(array $list=[])
    {
        $keys = array_keys($this->turnt_image);
        $random = [];

        foreach($keys as $index) {
            array_push($random, $list[$index]);
        }
        return $random;
    }

    //sets an array to the keys of image_id
    private function getKeys(array $list=[])
    {
        $array = [];

        foreach ($list as $key => $value){
            array_push($array,$key);
        }
        return $array;
    }

    //Make the cards visible
    public function loadField()
    {
        $temp_array_ids = $this->getKeys($this->image_id);
        $this->allowedToSwitch && isset($_SESSION['turnt']) ? $this->turnt = $_SESSION['turnt'] : $this->allowedToSwitch = true;

        $html = "<form action='index.php' method='post'>\n";
        for ($i=0; $i < $this->size; $i++) {
            $html .= "\n<div id='images'> \n <button class='image' name=".$temp_array_ids[$i];
            $html .= $this->turnt[$i] != 0 ? " disabled>" : ">";
            $html .= "\n   <img src='";
            $html .= $this->turnt[$i] != 0 ? $this->turnt_image[$this->image_id[$temp_array_ids[$i]]] : $this->notTurnt_Image;
            $html .= "'/>\n </button> \n</div>\n";
        }
        $html .= "\n<div id='button'>\n" . '<input type="submit" name="again" value="Restart">' . "\n</div>\n </form>\n";
        return $html;
    }

    //Check if user clicked the same image. and if its the same one.
    public function turn(array $post = [])
    {
        $_SESSION['turnt'][key($post)] = 1;
        $this->turnt = $_SESSION['turnt'];
        $this->turns = $_SESSION['turns']++;

        if(isset($_SESSION['lastNumber']) && $this->image_id[key($post)] != $this->image_id[$_SESSION['lastNumber']]){
            $this->allowedToSwitch = false;
            $_SESSION['turnt'][key($post)] = $_SESSION['turnt'][$_SESSION['lastNumber']] = 0;
        }
        $_SESSION['lastNumber'] = isset($_SESSION['lastNumber']) ? null : key($post);
    }

    //Check if the user won the game
    public function wonTheGame()
    {
        if(isset($_SESSION['turnt']) && count(array_keys($_SESSION['turnt'], 0)) === 0){
            return ', You won the game';
        }
    }

    //Get amount of turns
    public function getTurns(){
        return $this->turns;
    }

    //Get the % of the completed part.
    public function getCompletion(){
        $ret = count(array_keys($_SESSION['turnt'], 0)) % 2 == 0 ?
            round(($this->size - count(array_keys($_SESSION['turnt'], 0))) / $this->size * 100 , 1) :
            round(($this->size - count(array_keys($_SESSION['turnt'], 0)) -1) / $this->size * 100 , 1);
        $ret .= "%";
        return $ret;
    }

    //restart the game
    public static function restart()
    {
        session_unset();
    }

    //returns the size variable
    public function getSize()
    {
        return $this->size;
    }
}
?>
