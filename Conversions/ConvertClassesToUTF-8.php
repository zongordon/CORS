<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `class_id`, `class_category`, `class_gender_category`, `class_weight_length`, `class_age` FROM `classes` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old class_category</td>
<td>New class_category</td>
<td>Old class_gender_category</td>
<td>New class_gender_category</td>
<td>Old class_weight_length</td>
<td>New class_weight_length</td>
<td>Old class_age</td>
<td>New class_age</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["class_id"];
$class_category = stripslashes($row["class_category"]);
$newclass_category = utf8_encode($class_category);
$class_gender_category = stripslashes($row["class_gender_category"]);
$newclass_gender_category = utf8_encode($class_gender_category);
$class_weight_length = stripslashes($row["class_weight_length"]);
$newclass_weight_length = utf8_encode($class_weight_length);
$class_age = stripslashes($row["class_age"]);
$newclass_age = utf8_encode($class_age);
echo "
<tr>
<td>$ID</td>
<td>$class_category</td>
<td>$newclass_category</td>
<td>$class_gender_category</td>
<td>$newclass_gender_category</td>
<td>$class_weight_length</td>
<td>$newclass_weight_length</td>
<td>$class_age</td>
<td>$newclass_age</td>
</tr>
";
$sql2 = "UPDATE `classes` SET `class_category` = '".addslashes($newclass_category)."',`class_gender_category` = '".addslashes($newclass_gender_category)."',`class_weight_length` = '".addslashes($newclass_weight_length)."',`class_age` = '".addslashes($newclass_age)."' WHERE `class_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());
}
echo '</tbody>
</table>'
?>