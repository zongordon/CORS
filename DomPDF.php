<?php 
//Added code to make it site independent 
/* DOMPDF 0.8.2
 * https://github.com/dompdf/dompdf
 * *
 */
namespace Dompdf;
// include autoloader
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf as Dompdf;

//Fetch the class id from previous page
$class_id = filter_input(INPUT_GET,'class_id');
$dompdf = new DOMPDF();
$dompdf->set_option('enable_css_float', true);
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('enable_remote', true);

//Site independant code
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("HTTP_PATH_ROOT", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_'));
$my_path = HTTP_PATH_ROOT;
$html = 'http://'.$my_path.'/ElimLadder.php?class_id='.$class_id;

//Load file and render PDF
$dompdf->load_html_file($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("ElimLadder.pdf");
?>    
 