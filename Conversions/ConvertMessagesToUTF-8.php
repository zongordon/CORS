<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `message_id`, `message_subject`, `message` FROM `messages` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old message_subject</td>
<td>New message_subject</td>
<td>Old message</td>
<td>New message</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["message_id"];
$message_subject = stripslashes($row["message_subject"]);
$newmessage_subject = utf8_encode($message_subject);
$message = stripslashes($row["message"]);
$newmessage = utf8_encode($message);
echo "
<tr>
<td>$ID</td>
<td>$message_subject</td>
<td>$newmessage_subject</td>
<td>$message</td>
<td>$newmessage</td>
</tr>
";
/*$sql2 = "UPDATE `messages` SET `message_subject` = '".addslashes($newmessage_subject)."',`message` = '".addslashes($newmessage)."' WHERE `message_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());*/
}
echo '</tbody>
</table>'
?>