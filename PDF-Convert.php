<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$colname_rsResult = "";
if (isset($_GET['class_id'])) {
  $colname_rsResult = $_GET['class_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsResult = sprintf("SELECT a.club_name, co.contestant_name, clu.club_startorder, re.contestant_result FROM registration AS re INNER JOIN classes AS cl USING (class_id)  INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = %s AND contestant_result <> 0 ORDER BY contestant_result", GetSQLValueString($colname_rsResult, "int"));
$rsResult = mysql_query($query_rsResult, $DBconnection) or die(mysql_error());
$row_rsResult = mysql_fetch_assoc($rsResult);
$totalRows_rsResult = mysql_num_rows($rsResult);

// Load a PDF document from a file
$fileName = $_GET['class_id'];
$pdf2 = Zend_Pdf::load($fileName);
echo '<table><thead>
<tr><td></td></tr>
</thead>
<tbody>';
echo '</tbody>
</table>'
?>