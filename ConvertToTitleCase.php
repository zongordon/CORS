<?php
//Changed MB_CASE_TITLE from iso-8859-1
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `contestant_id`, `contestant_name` FROM `contestants` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old Name</td>
<td>New Name</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["contestant_id"];
$name = stripslashes($row["contestant_name"]);$newName = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
echo "
<tr>
<td>$ID</td>
<td>$name</td>
<td>$newName</td>
</tr>
";
$sql2 = "UPDATE `contestants` SET `contestant_name` = '".addslashes($newName)."' WHERE `contestant_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());
}
echo '</tbody>
</table>'
?>