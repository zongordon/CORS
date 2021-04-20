<?php
//path to the image directory 
$directory = "../img/rotating/"; 
  
//get all image files with a .jpg extension. 
$images = glob("" . $directory . "*.jpg"); 

// get random image index 
$rand_img = array_rand($images);

// display the images randomly 
echo '<img height="199" width="300" alt="Images from competition" src="'.$images[$rand_img].'"/>'; 
?>
