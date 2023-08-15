<?php 
//Added code to provide streaming pdf (ob_start() ) and setting proper timezone
/* DOMPDF 0.8.3
 * https://github.com/dompdf/dompdf
 * *
 */

ob_start();

ini_set('display_errors',1); // enable php error display for easy trouble shooting
error_reporting(E_ALL); // set error display to all
//Set timezone
date_default_timezone_set("Europe/Stockholm");

// include autoloader
require_once '../dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

//Fetch the class id from previous page
$class_id = filter_input(INPUT_GET,'class_id');
// instantiate and use the dompdf class
$dompdf = new DOMPDF();
$dompdf->set_option('enable_css_float', true);
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('enable_remote', true);

//Site independant code to get the file to render
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("HTTP_PATH_ROOT", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_'));
$my_path = HTTP_PATH_ROOT;

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
