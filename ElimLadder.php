<?php 
//Adapted code to PHP 7 (PDO) and added minor error handling. Changed from charset=ISO-8859-1.
//Changed from array_key_exists(0, $contestantsArray) to isset($startnumberArray[0]) to enable identifying NULL when no start numbers exist. Changed database default value to NULL.

//Fetch the class id from previous page
$colname_rsClassData = filter_input(INPUT_GET,'class_id');

//Catch anything wrong with query
try {
//SELECT competitor data för the competition class
require('Connections/DBconnection.php');         
$query = "SELECT com.comp_name, com.comp_start_date, a.club_name, re.reg_id, re.contestant_startnumber, re.contestant_height, co.contestant_name, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM competition AS com, registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = :class_id AND comp_current = 1 ORDER BY club_startorder, reg_id";
$stmt_rsClassContestants = $DBconnection->prepare($query);
$stmt_rsClassContestants->execute(array(':class_id'=>$colname_rsClassData));
$row_rsClassContestants = $stmt_rsClassContestants->fetch(PDO::FETCH_ASSOC);
//$totalRows_rsClassContestants = $stmt_rsClassContestants->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   
try {
//SELECT result data for the class
$query_Result = "SELECT a.club_name, co.contestant_name, clu.club_startorder, re.contestant_result FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = :class_id AND contestant_result <> 0 ORDER BY contestant_result";
$stmt_rsResult = $DBconnection->prepare($query_Result);
$stmt_rsResult->execute(array(':class_id'=>$colname_rsClassData));
$row_rsResult = $stmt_rsResult->fetch(PDO::FETCH_ASSOC);
$totalRows_rsResult = $stmt_rsResult->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   

$comp_name = $row_rsClassContestants['comp_name'];
$class_discipline = $row_rsClassContestants['class_discipline']; 
$class_gender_category = $row_rsClassContestants['class_gender_category']; 
$class_category = $row_rsClassContestants['class_category']; 
$class_age = $row_rsClassContestants['class_age']; 
$class_weight_length = $row_rsClassContestants['class_weight_length']; 
$comp_start_date = $row_rsClassContestants['comp_start_date']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="T&auml;vlingsstege"?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Munktellarenan." />
<meta name="keywords" content="tuna karate cup, tävlingsstege per klass, karate, eskilstuna, munktellarenan, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="print.css" type="text/css" media="print" /> 
<link rel="stylesheet" href="3_elimladder.css" type="text/css" media="screen"/>
</head>
<body>
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
    <table class = masthead_table>
    <tr>
      <td>&nbsp;</td>  
    </tr>        
    <tr>
      <td class = zero_left>&nbsp;</td>    
      <td class = first_left>Pool A</td>
      <td class = second_left>&nbsp;</td>
      <td class = third_left>&nbsp;</td>
      <td class = fourth_left><?php echo 'Datum: '.$comp_start_date;?></td>
      <td class = fourth_right>&nbsp;</td>
      <td class = third_right>&nbsp;</td>
      <td class = second_right>&nbsp;</td>
      <td class = first_right>Pool B</td>
      <td class = zero_right>&nbsp;</td>      
    </tr>
  </table> 
</div>
  <div id="content">
      <div class="story">
<?php 
$startnumber = $row_rsClassContestants['contestant_startnumber']; 
$name = $row_rsClassContestants['contestant_name']; 
$club = $row_rsClassContestants['club_name']; 
$str = $name.', '.$club;
        if( strlen( $str ) > 34 ){ $str = substr( $str, 0, 34 ) . "..";}
$contestantsArray[] = $str;        
$startnumberArray[] = $startnumber;        
?>
<div id="apDiv1">
  <table width="100%" border="0">
    <tr>
        <td class="AKA_red"><?php if (isset($startnumberArray[0])) { echo $startnumberArray[0]; } else { echo "1"; }?><!--1--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
<?php    
$contestantsArray = array(); 
$startnumbersArray = array();
while($row_rsClassContestants = $stmt_rsClassContestants->fetch(PDO::FETCH_ASSOC)) {  
$startnumber = $row_rsClassContestants['contestant_startnumber']; 
$name = $row_rsClassContestants['contestant_name']; 
$club = $row_rsClassContestants['club_name']; 
$str = $name.', '.$club;
        if( strlen( $str ) > 34 ){ $str = substr( $str, 0, 34 ) . "..";}
$contestantsArray[] = $str; 
$startnumbersArray[] = $startnumber;
} 
?>    
       <td class="AO_blue"><?php if (isset($startnumberArray[15])) { echo $startnumbersArray[15]; } else { echo "17"; }?><!--17--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(15, $contestantsArray)) { echo $contestantsArray[15]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_1"></div>
<div id="apDiv2">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[7])) { echo $startnumbersArray[7]; } else { echo "9"; }?><!--9--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(7, $contestantsArray)) { echo $contestantsArray[7]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[23])) { echo $startnumbersArray[23]; } else { echo "25"; }?><!--25--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(23, $contestantsArray)) { echo $contestantsArray[23]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_1"></div>
<div id="apDiv3">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[3])) { echo $startnumbersArray[3]; } else { echo "5"; }?><!--5--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (array_key_exists(19, $startnumbersArray)) { echo $startnumbersArray[19]; } else { echo "21"; }?><!--21--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(19, $contestantsArray)) { echo $contestantsArray[19]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_2"></div>
<div id="apDiv4">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[11])) { echo $startnumbersArray[11]; } else { echo "13"; }?><!--13--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(11, $contestantsArray)) { echo $contestantsArray[11]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[27])) { echo $startnumbersArray[27]; } else { echo "29"; }?><!--29--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(27, $contestantsArray)) { echo $contestantsArray[27]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_1"></div>
<div id="apDivFinal"></div>
<div id="apDiv5">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[1])) { echo $startnumbersArray[1]; } else { echo "3"; }?><!--3--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[17])) { echo $startnumbersArray[17]; } else { echo "19"; }?><!--19--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(17, $contestantsArray)) { echo $contestantsArray[17]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_2"></div>
<div id="apDiv6">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[9])) { echo $startnumbersArray[9]; } else { echo "11"; }?><!--11--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(9, $contestantsArray)) { echo $contestantsArray[9]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[25])) { echo $startnumbersArray[25]; } else { echo "27"; }?><!--27--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(25, $contestantsArray)) { echo $contestantsArray[25]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_3"></div>
<div id="apDiv7">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[5])) { echo $startnumbersArray[5]; } else { echo "7"; }?><!--7--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(5, $contestantsArray)) { echo $contestantsArray[5]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[21])) { echo $startnumbersArray[21]; } else { echo "23"; }?><!--23--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(21, $contestantsArray)) { echo $contestantsArray[21]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_4"></div>
<div id="apDiv8">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumberArray[13])) { echo $startnumbersArray[13]; } else { echo "15"; }?><!--15--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(13, $contestantsArray)) { echo $contestantsArray[13]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumberArray[29])) { echo $startnumbersArray[29]; } else { echo "31"; }?><!--31--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(29, $contestantsArray)) { echo $contestantsArray[29]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_5"></div>
<div id="apDiv9">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[0])) { echo $startnumbersArray[0]; } else { echo "2"; }?><!--2--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(16, $contestantsArray)) { echo $contestantsArray[16]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[16])) { echo $startnumbersArray[16]; } else { echo "18"; }?><!--18--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_3"></div>
<div id="apDiv10">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(8, $contestantsArray)) { echo $contestantsArray[8]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[8])) { echo $startnumbersArray[8]; } else { echo "10"; }?><!--10--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(24, $contestantsArray)) { echo $contestantsArray[24]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[24])) { echo $startnumbersArray[24]; } else { echo "26"; }?><!--26--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_6"></div>
<div id="apDiv11">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[4])) { echo $startnumbersArray[4]; } else { echo "6"; }?><!--6--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(20, $contestantsArray)) { echo $contestantsArray[20]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[20])) { echo $startnumbersArray[20]; } else { echo "22"; }?><!--22--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_4"></div>
<div id="apDiv12">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(12, $contestantsArray)) { echo $contestantsArray[12]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[12])) { echo $startnumbersArray[12]; } else { echo "14"; }?><!--14--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(28, $contestantsArray)) { echo $contestantsArray[28]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[28])) { echo $startnumbersArray[28]; } else { echo "30"; }?><!--30--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_2"></div>
<div id="apDiv13">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[2])) { echo $startnumbersArray[2]; } else { echo "4"; }?><!--4--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(18, $contestantsArray)) { echo $contestantsArray[18]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[18])) { echo $startnumbersArray[18]; } else { echo "20"; }?><!--20--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_7"></div>
<div id="apDiv14">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(10, $contestantsArray)) { echo $contestantsArray[10]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[10])) { echo $startnumbersArray[10]; } else { echo "12"; }?><!--12--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(26, $contestantsArray)) { echo $contestantsArray[26]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[26])) { echo $startnumbersArray[26]; } else { echo "28"; }?><!--28--></td>
    </tr>
  </table>
</div>
<div id="apDiv15">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(6, $contestantsArray)) { echo $contestantsArray[6]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[6])) { echo $startnumbersArray[6]; } else { echo "8"; }?><!--8--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(22, $contestantsArray)) { echo $contestantsArray[22]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[22])) { echo $startnumbersArray[22]; } else { echo "24"; }?><!--24--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_8"></div>
<div id="apDiv16">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(14, $contestantsArray)) { echo $contestantsArray[14]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumberArray[14])) { echo $startnumbersArray[14]; } else { echo "16"; }?><!--16--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(30, $contestantsArray)) { echo $contestantsArray[30]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumberArray[30])) { echo $startnumbersArray[30]; } else { echo "32"; }?><!--32--></td>
    </tr>
  </table>
</div>
<div id="apDivResultat">
  <table width="100%">
      <tr><td><h2 align="center">Resultat</h2></td></tr>
<?php
if ($totalRows_rsResult === 0) { ?>
      <tr>
        <td>
<?php 
echo '1. _____________________</br>2. _____________________</br>3. _____________________</br>3. _____________________</br>'; ?>	 	
	</td>
      </tr>
<?php		
}
else {
    if (($row_rsResult['contestant_result'] > 0) && ($row_rsResult['contestant_result'] < 4)) {      
?>
      <tr>
          <td>
              <?php echo $row_rsResult['contestant_result'].'. '.$row_rsResult['contestant_name'].', '.$row_rsResult['club_name']; ?>
          </td>
      </tr>
<?php		      
        while($row_rsResult = $stmt_rsResult->fetch(PDO::FETCH_ASSOC)) {  
?>
      <tr>
          <td>
              <?php echo $row_rsResult['contestant_result'].'. '.$row_rsResult['contestant_name'].', '.$row_rsResult['club_name']; ?>
          </td>
      </tr>
<?php
        }	
    }
}       
//Kill statements and DB connection
$stmt_rsClassContestants->closeCursor();
$stmt_rsResult->closeCursor();
$DBconnection = null;
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