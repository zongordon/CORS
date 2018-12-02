<?php
/*
The CAPTCHA  is a very very useful test to prevent abuse on the websites. When you create a web form like registration, 
login, contact us, blog comment etc..., We are suffering day by day with unwanted email or web spam abuse. 
So if you use CAPTCHA on your website forms, this can help in stopping Guestbook Spam, Blog Spam, Wiki Spam, 
Comment Spam, Feedback Form Spam, Other Types of Web Form Spam.
The goal of this tutorial is to demonstrate how to make your own simple CAPTCHA protection using PHP. 
For this we need to enable gd library, you can create a captcha code for your registration form or any web forms using PHP.
Create a one php page like: Captcha.php & paste all the below PHP code in that file.*/

//Start session and clear the old captchaâ€™s session value if it set.
session_start();

if(isset($_SESSION['captcha']))
{
unset($_SESSION['captcha']);
}
//The number of captcha characters which will dispaly as a image and total available characters, 
//here I am only using all the lower and upper case alphabets and all numerics. Then we shuffle the characters.
$num_chars=6;//number of characters for captcha image
$characters=array_merge(range(0,9),range('A','Z'),range('a','z'));//creating combination of numbers & alphabets
shuffle($characters);//shuffling the characters

//Getting the required random 5 characters
//Generate the required captcha code in a random manner from the available character array, 
$captcha_text="";
for($i=0;$i<$num_chars;$i++)
{
$captcha_text.=$characters[rand(0,count($characters)-1)];
}

//Assign the value to session variable.
$_SESSION['captcha'] =$captcha_text;// assigning the text into session

header("Content-type: image/png");// setting the content type as png
$captcha_image=imagecreatetruecolor(140,30);

$captcha_background=imagecolorallocate($captcha_image,225,238,221);//setting captcha background colour
$captcha_text_colour=imagecolorallocate($captcha_image,58,94,47);//setting cpatcha text colour

imagefilledrectangle($captcha_image,0,0,140,29,$captcha_background);//creating the rectangle

$font='Arial.ttf';//setting the font path

//Draw the image. For this we needed to enable GD library.
imagettftext($captcha_image,20,0,11,21,$captcha_text_colour,$font,$captcha_text);
imagepng($captcha_image);
imagedestroy($captcha_image);


/*How to display above code as a captcha image?
It's simple, in your registration form or web form put this part of code:
<img src="Captcha.php">
Now you can add a text box to enter the captcha value, and then you can compare the entered captcha value and 
the assigned captcha session value.*/
?>