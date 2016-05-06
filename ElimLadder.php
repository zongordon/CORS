<?php 
//Adjusted to display red (aka) and blue (ao) colours in the ladder

require_once('Connections/DBconnection.php'); 

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsClassContestants = "";
if (isset($_GET['class_id'])) {
  $colname_rsClassContestants = $_GET['class_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClassContestants = sprintf("SELECT com.comp_name, com.comp_start_date, a.club_name, re.reg_id, re.contestant_height, co.contestant_name, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM competition AS com, registration AS re  INNER JOIN classes AS cl USING (class_id)  INNER JOIN contestants AS co USING (contestant_id)  INNER JOIN account AS a USING (account_id)  INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = %s AND comp_current = 1 ORDER BY club_startorder, reg_id", GetSQLValueString($colname_rsClassContestants, "int"));
$rsClassContestants = mysql_query($query_rsClassContestants, $DBconnection) or die(mysql_error());
$row_rsClassContestants = mysql_fetch_assoc($rsClassContestants);
$totalRows_rsClassContestants = mysql_num_rows($rsClassContestants);

$colname_rsResult = "";
if (isset($_GET['class_id'])) {
  $colname_rsResult = $_GET['class_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsResult = sprintf("SELECT a.club_name, co.contestant_name, clu.club_startorder, re.contestant_result FROM registration AS re INNER JOIN classes AS cl USING (class_id)  INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = %s AND contestant_result <> 0 ORDER BY contestant_result", GetSQLValueString($colname_rsResult, "int"));
$rsResult = mysql_query($query_rsResult, $DBconnection) or die(mysql_error());
$row_rsResult = mysql_fetch_assoc($rsResult);
$totalRows_rsResult = mysql_num_rows($rsResult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="T&auml;vlingsstege"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, tävlingsstege per klass, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="print.css" type="text/css" media="print" /> 
<link rel="stylesheet" href="3_elimladder.css" type="text/css" media="screen"/>
</head>
<body>
<?php 
$comp_name = $row_rsClassContestants['comp_name'];
$class_discipline = $row_rsClassContestants['class_discipline']; 
$class_gender_category = $row_rsClassContestants['class_gender_category']; 
$class_category = $row_rsClassContestants['class_category']; 
$class_age = $row_rsClassContestants['class_age']; 
$class_weight_length = $row_rsClassContestants['class_weight_length']; 
$comp_start_date = $row_rsClassContestants['comp_start_date']; 
?>
<div id="masthead">
<h1>
<?php
echo $comp_name.' - T&Auml;VLINGSPROTOKOLL - '.$class_discipline.' | '.$class_gender_category.' | '.$class_category;
if ($class_age == "") { echo ""; } 
if ($class_age <> "") { 
    echo ' | '.$class_age.' &aring;r'.'  ';     
}
if ($class_weight_length == "") { 
    echo "";     
} 
if ($class_weight_length <> "") { 
echo ' | '.$class_weight_length;
}
?>
</h1>
    <table width="90%">
    <tr>
      <td>&nbsp;</td>    
      <td>Pool A</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php echo '<NOBR>Datum: '.$comp_start_date.'</NOBR>';?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Pool B</td>
      <td>&nbsp;</td>      
    </tr>
    <tr>
      <td>&nbsp;</td>  
    </tr>
    <tr>
      <td>&nbsp;</td>    
      <td width="255"><NOBR>Omg&aring;ng 1</NOBR></td>
      <td width="80"><NOBR>Omg&aring;ng 2</NOBR></td>
      <td width="80"><NOBR>Omg&aring;ng 3</NOBR></td>
      <td width="150"><NOBR>Omg&aring;ng 4</NOBR></td>
      <td width="150" align="right"><NOBR>Omg&aring;ng 4</NOBR></td>
      <td width="80" align="right"><NOBR>Omg&aring;ng 3</NOBR></td>
      <td width="80" align="right"><NOBR>Omg&aring;ng 2</NOBR></td>
      <td width="255" align="right"><NOBR>Omg&aring;ng 1</NOBR></td>
      <td>&nbsp;</td>          
    </tr>
  </table> 
</div>
  <div id="content">
      <div class="story">
<?php 
$name = $row_rsClassContestants['contestant_name']; 
$club = $row_rsClassContestants['club_name']; 
$str = $name.', '.$club;
	if( strlen( $str ) > 34 ) $str = substr( $str, 0, 34 ) . "...";
?>
<div id="apDiv1">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">1</td><td nowrap="nowrap" class="result_tbl"><?php echo $str;?></td>
    </tr>
    <tr>
<?php    
$contestantsArray = array(); 
while ($row_rsClassContestants = mysql_fetch_assoc($rsClassContestants)) { 
$name = $row_rsClassContestants['contestant_name']; 
$club = $row_rsClassContestants['club_name']; 
$str = $name.', '.$club;
	if( strlen( $str ) > 33 ) $str = substr( $str, 0, 33 ) . "...";
$contestantsArray[] = $str; 
} 
?>    
      <td width = "5" bgcolor="#0000FF">17</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[15];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_1"></div>
<div id="apDiv2">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">9</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[7];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">25</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[23];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_1"></div>
<div id="apDiv3">
  <table width="100%" border="0">
    <tr>
      <td width = "5"  bgcolor="#FF0000">5</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[3];?></td>
    </tr>
    <tr>
      <td width = "5"  bgcolor="#0000FF">21</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[19];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_2"></div>
<div id="apDiv4">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">13</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[11];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">29</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[27];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_1"></div>
<div id="apDivFinal"></div>
<div id="apDiv5">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">3</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[1];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">19</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[17];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_2"></div>
<div id="apDiv6">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">11</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[9];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">27</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[25];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_3"></div>
<div id="apDiv7">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">7</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[5];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">23</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[21];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_4"></div>
<div id="apDiv8">
  <table width="100%" border="0">
    <tr>
      <td width = "5" bgcolor="#FF0000">15</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[13];?></td>
    </tr>
    <tr>
      <td width = "5" bgcolor="#0000FF">31</td><td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[29];?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_5"></div>
<div id="apDiv9">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[0];?></td><td width = "5" bgcolor="#FF0000">2</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[16];?></td><td width = "5" bgcolor="#0000FF">18</td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_3"></div>
<div id="apDiv10">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[8];?></td><td width = "5" bgcolor="#FF0000">10</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[24];?></td><td width = "5" bgcolor="#0000FF">26</td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_6"></div>
<div id="apDiv11">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[4];?></td><td width = "5" bgcolor="#FF0000">6</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[20];?></td><td width = "5" bgcolor="#0000FF">22</td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_4"></div>
<div id="apDiv12">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[12];?></td><td width = "5" bgcolor="#FF0000">14</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[28];?></td><td width = "5" bgcolor="#0000FF">30</td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_2"></div>
<div id="apDiv13">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[2];?></td><td width = "5" bgcolor="#FF0000">4</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[18];?></td><td width = "5" bgcolor="#0000FF">20</td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_7"></div>
<div id="apDiv14">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[10];?></td><td width = "5" bgcolor="#FF0000">12</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[26];?></td><td width = "5" bgcolor="#0000FF">28</td>
    </tr>
  </table>
</div>
<div id="apDiv15">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[6];?></td><td width = "5" bgcolor="#FF0000">8</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[22];?></td><td width = "5" bgcolor="#0000FF">24</td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_8"></div>
<div id="apDiv16">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[14];?></td><td width = "5" bgcolor="#FF0000">16</td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php echo $contestantsArray[30];?></td><td width = "5" bgcolor="#0000FF">32</td>
    </tr>
  </table>
</div>
<div id="apDivResultat">
  <table width="100%">
	<tr><td><h2 align="center">Resultat</h2></td></tr>
<?php
		do { 

if ($totalRows_rsResult == 0) { ?>
      <tr>
        <td>
<?php
		echo '1. _____________________</br>2. _____________________</br>3. _____________________</br>3. _____________________</br>'; ?>	 	
		</td>
      </tr>
<?php		
}		

	if (($row_rsResult['contestant_result'] > 0) && ($row_rsResult['contestant_result'] < 4)) { ?>
      <tr>
          <td><h3>
              <?php echo $row_rsResult['contestant_result'].'. '.$row_rsResult['contestant_name'].', '.$row_rsResult['club_name']; ?>
              </h3>
          </td>
      </tr>
<?php
	}	
		} while ($row_rsResult = mysql_fetch_assoc($rsResult)); 
			 
?>
<?php
mysql_free_result($rsClassContestants);
mysql_free_result($rsResult);
?>
<!--        
      <tr>
          <td><form method="post" target="_blank" action="http://pdfservices.net/gateway.php">
<input type="hidden" name="apikey" value="8gnd6o478cfvk0bo1s0euv3e46"/>	 <!-- your apikey of pdfservices.net-->
<!-- <input type="hidden" name="textinputs" value="true"/> <!-- allow paint forms object , you can create a pdf with fiedls and write inside as user -->
<!-- <input type="hidden" name="usemedia" value="false"/> <!-- use the media type css: @media display {, @media print { -->
<!-- <input type="hidden" name="margin" value="2"/>	 <!-- margins page -->
<!-- <input type="hidden" name="utf8" value="UTF-8" />
<input type="hidden" name="forcedownload" value="true" /> <!-- Use for download directly (no open in browser) -->
<!-- <input type="hidden" name="autoprint" value="true" /> <!-- Use for autoprint on show -->
<!-- <input type="hidden" name="w" value="297" /> <!-- width of document -->
<!-- <input type="hidden" name="h" value="210" /> <!-- height of document -->
<!-- <textarea name="html" rows="5" cols="25"/> <!-- html to submit -->
<!-- <h3>My title</h3><hr/><p>your text</p>
</textarea> 
<input type="submit" />
</form></td>
      </tr>                                                      
      <tr>
          <td></td>
      </tr>                                              
      <tr>
          <td><a target="_blank" href="http://pdfservices.net/freegateway.php?apikey=free&url=urlbutton&autoprint=true"><img class="pdfprintbutton" alt="Print / PDF" src="http://www.pdfservices.net/images/pdfservices-printer.gif"></a>
          </td>
      </tr>
-->                                      
  </table>      
</div>
    </div> 
</div>
</body>
</html>