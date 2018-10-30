<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `contestant_id`, `contestant_name` FROM `contestants` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old contestant_name</td>
<td>New contestant_name</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["contestant_id"];
$contestant_name = stripslashes($row["contestant_name"]);
$newcontestant_name = utf8_encode($contestant_name);
echo "
<tr>
<td>$ID</td>
<td>$contestant_name</td>
<td>$newcontestant_name</td>
</tr>
";
$sql2 = "UPDATE `contestants` SET `contestant_name` = '".addslashes($newcontestant_name)."' WHERE `contestant_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());
}
echo '</tbody>
</table>'
?>