<?php 
/*
 * DOMPDF 0.8.2
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
$html = 'http://testsite.karateklubben.com/ElimLadder.php?class_id='.$class_id;
/*
$path = getcwd();
$html = $path.'/ElimLadder.php?class_id='.$class_id;
echo $html;
*/
$dompdf->load_html_file($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("ElimLadder.pdf");
?>    
 