<?php
 
//height and width of the captch image
$width = 60;
$height = 30;
 
//amount of background noise to add in captcha image
$noise_level = 15;
 
//generate the random code
$code=rand(1000,9999);
 
//save it in SESSION for furhter form validation
session_start();
$_SESSION['captcha']=$code;
 
//create the image resource 
$im = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($im, 230, 80, 0); //background color
$fg = imagecolorallocate($im, 255, 255, 255);//text color
$ns = imagecolorallocate($im, 200, 200, 200);//noise color
 
//fill the image resource with the bg color
imagefill($im, 0, 0, $bg);
 
//Add the random code of string to the image
imagestring($im, 5, 10, 8,  $code, $fg);//imagestring 
 
// Add some noise to the image.
for ($i = 0; $i < $noise_level; $i++) {
	for ($j = 0; $j < $noise_level; $j++) {
		imagesetpixel(
			$im,
			rand(0, $width), 
			rand(0, $height),//make sure the pixels are random and don't overflow out of the image
			$ns
		);
	}
}
 
//tell the browser that this is an image
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
 
//generate the png image
imagepng($im);
 
//destroy the image
imagedestroy($im);
?>