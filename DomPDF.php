<?php 
//Upgraded from DOMPDF 0.8.3 to 2.0.3, changed site independent code and made the PDF rendering work again
/* DOMPDF 2.0.3
 * https://github.com/dompdf/dompdf
 * *
 */

ob_start();

ini_set('display_errors',1); // enable php error display for easy trouble shooting
error_reporting(E_ALL); // set error display to all
//Set timezone
date_default_timezone_set("Europe/Stockholm");

// include autoloader
require_once __DIR__ .'/dompdf/vendor/autoload.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

//Fetch the class id from previous page
$class_id = filter_input(INPUT_GET,'class_id');
// instantiate and use the dompdf class
$dompdf = new DOMPDF();
$dompdf->set_option('enable_css_float', true);
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('enable_remote', true);

//Site independent code to get the file to render
$my_path = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_');

//Load file and get content from file
$file = 'https://'.$my_path.'/ElimLadder.php?class_id='.$class_id;
$html = file_get_contents($file);

//Load html and render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("ElimLadder.pdf");

ob_end_flush();
?> 
