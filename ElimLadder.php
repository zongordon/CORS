<?php 
// Changed code to support individual as well as class individual clubb draws and still display old setup of draws (

//Fetch the class id from previous page
$colname_rsClassData = filter_input(INPUT_GET,'class_id');

//Catch anything wrong with query
try {
//SELECT data för the competition class
require('Connections/DBconnection.php');         
$queryClass = "SELECT com.comp_name, com.comp_arranger, com.comp_start_date, com.comp_limit_roundrobin, cl.class_team, cl.class_category, "
        . "cl.class_discipline, cl.class_discipline_variant, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age "
        . "FROM competition AS com, registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) "
        . "INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) "
        . "WHERE cl.class_id = :class_id AND comp_current = 1";
$stmt_rsClass = $DBconnection->prepare($queryClass);
$stmt_rsClass->execute(array(':class_id'=>$colname_rsClassData));
$row_rsClass = $stmt_rsClass->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   
//Catch anything wrong with query
try {
//SELECT contestant data för the competition class
require('Connections/DBconnection.php');         
$query = "SELECT a.club_name, re.reg_id, re.contestant_startnumber, re.contestant_height, co.contestant_name, cl.class_category, cl.class_discipline,"
        . "cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age, clu.club_startorder "
        . "FROM competition AS com, registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) "
        . "INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) "
        . "WHERE cl.class_id = :class_id AND comp_current = 1 ORDER BY clu.club_startorder, re.club_start_order, re.start_order;";
$stmt_rsClassContestants = $DBconnection->prepare($query);
$stmt_rsClassContestants->execute(array(':class_id'=>$colname_rsClassData));
$totalRows_rsClassContestants = $stmt_rsClassContestants->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   
//Catch anything wrong with query
try {
//SELECT result data for the class
$query_Result = "SELECT a.club_name, co.contestant_name, clu.club_startorder, cl.class_id, re.contestant_result "
        . "FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) "
        . "INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) "
        . "WHERE cl.class_id = :class_id AND contestant_result <> 0 ORDER BY contestant_result";
$stmt_rsResult = $DBconnection->prepare($query_Result);
$stmt_rsResult->execute(array(':class_id'=>$colname_rsClassData));
$row_rsResult = $stmt_rsResult->fetch(PDO::FETCH_ASSOC);
$totalRows_rsResult = $stmt_rsResult->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   
//Creating arrays for contestants and start numbers
$contestantsArray = array(); 
$startnumbersArray = array();
while($row_rsClassContestants = $stmt_rsClassContestants->fetch(PDO::FETCH_ASSOC)) {  
$startnumber = $row_rsClassContestants['contestant_startnumber']; 
$name = $row_rsClassContestants['contestant_name']; 
$club = $row_rsClassContestants['club_name']; 
$str = $name.', '.$club;
        //Limit the string to 37 characters
        if( strlen( $str ) > 37 ){ $str = substr( $str, 0, 37 ) . "..";}
$contestantsArray[] = $str; 
$startnumbersArray[] = $startnumber;
}
$pagetitle="T&auml;vlingsstege";
//If there are any class contestants
if ($totalRows_rsClassContestants >0){
$comp_name = $row_rsClass['comp_name'];
$comp_arranger = $row_rsClass['comp_arranger'];
$comp_start_date = $row_rsClass['comp_start_date']; 
$comp_limit_roundrobin = $row_rsClass['comp_limit_roundrobin'];
$class_team = $row_rsClass['class_team']; 
$team = '';
if($class_team === 1){$team = 'Lag-';}
$class_discipline = $row_rsClass['class_discipline']; 
$class_discipline_variant = $row_rsClass['class_discipline_variant']; 
$class_gender_category = $row_rsClass['class_gender_category']; 
$class_category = $row_rsClass['class_category']; 
$class_age = $row_rsClass['class_age']; 
$class_weight_length = $row_rsClass['class_weight_length'];  
$pagedescription="$comp_name som arrangeras av $comp_arranger.";
$pagekeywords="$pagetitle, $comp_arranger, $comp_name, karate, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
} else { //If there are no class contestants
$comp_name = '';
$comp_arranger = '';
$comp_start_date = ''; 
$comp_limit_roundrobin = '';
$class_team =  ''; 
$team = '';
$class_discipline = ''; 
$class_discipline_variant = ''; 
$class_gender_category = ''; 
$class_category = ''; 
$class_age = ''; 
$class_weight_length = '';  
$pagedescription = '';
$pagekeywords="karate, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";    
} //If there are no class contestants
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?php echo $pagedescription ?>" />
<meta name="keywords" content="<?php echo $pagekeywords ?>" />

<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="print.css" type="text/css" media="print" /> 
<link rel="stylesheet" href="3_elimladder.css" type="text/css" media="screen"/>
</head>
<body>
<div id="masthead">
<h1>
<?php
echo $comp_name.' - T&Auml;VLINGSPROTOKOLL - '.$team.$class_discipline.' | '.$class_gender_category.' | '.$class_category;
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
      <td class = first_left>
      <?php if ($class_discipline === 'Kata' && $class_discipline_variant === 1){
                echo 'Kata Point System';
            }
            else {
                if ($totalRows_rsClassContestants > $comp_limit_roundrobin || $totalRows_rsClassContestants < 3 || $class_team === 1) { 
                    echo 'Pool A'; 
                } 
                else { 
                    echo 'Round Robin'; 
                }
            };?></td>
      <td class = second_left>&nbsp;</td>
      <td class = third_left>&nbsp;</td>
      <td class = fourth_left><?php echo 'Datum: '.$comp_start_date;?></td>
      <td class = fourth_right>&nbsp;</td>
      <td class = third_right>&nbsp;</td>
      <td class = second_right>&nbsp;</td>
      <td class = first_right>
      <?php if ($class_discipline === 'Kata' && $class_discipline_variant === 1){
                echo 'Kata Point System';
            }
            else {
                if ($totalRows_rsClassContestants > $comp_limit_roundrobin || $totalRows_rsClassContestants < 3 || $class_team === 1) { 
                    echo 'Pool B'; 
                } 
                else { 
                    echo 'Round Robin'; 
                }
            };?></td>
      <td class = zero_right>&nbsp;</td>      
    </tr>
  </table> 
</div>
  <div id="content">
      <div class="story">
<div class ="result_tbl">
<?php 
//When a kata class is using the point system, show the class accordingly else show elimination ladder or round robin table 
if ($class_discipline === 'Kata' && $class_discipline_variant === 1){ ?>
<div id="apMatchTbl">    
<table width="88%" border="1">
    <tr>
        <td><h3>Name</h3></td>      
        <td><h3>No.</h3></td>             
        <td><h3>1T</h3></td>             
        <td><h3>1A</h3></td>             
        <td><h3>2T</h3></td>             
        <td><h3>2A</h3></td>             
        <td><h3>3T</h3></td>             
        <td><h3>3A</h3></td>             
        <td><h3>4T</h3></td>             
        <td><h3>4A</h3></td>             
        <td><h3>5T</h3></td>             
        <td><h3>5A</h3></td>             
        <td><h3>6T</h3></td>             
        <td><h3>6A</h3></td>             
        <td><h3>7T</h3></td>             
        <td><h3>7A</h3></td>             
        <td><h3>TEC</h3></td>             
        <td><h3>ATH</h3></td>             
        <td><h3>Extra</h3></td>                     
        <td><h3>Total</h3></td>                     
        <td><h3>Rank</h3></td>                     
    </tr>        
<?php if ($totalRows_rsClassContestants < 4){ //With less than 4 contestans show only AKA table;?>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
<?php } //With less than 4 contestans show only AKA table;    
if ($totalRows_rsClassContestants === 4){ //Show 4 contestans;?>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>        
<?php    
}//Show 4 contestans;    
if ($totalRows_rsClassContestants > 4 && $totalRows_rsClassContestants < 11){ //Show 5-10 contestans;?>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(6, $contestantsArray)) { echo $contestantsArray[6]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[6])) { echo $startnumbersArray[6]; }?><!--7--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(8, $contestantsArray)) { echo $contestantsArray[8]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[8])) { echo $startnumbersArray[8]; }?><!--9--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <?php    
} //Show 5-10 contestans;
if ($totalRows_rsClassContestants > 11) { //Show 11-24 contestans;?>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(6, $contestantsArray)) { echo $contestantsArray[6]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[6])) { echo $startnumbersArray[6]; }?><!--7--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(8, $contestantsArray)) { echo $contestantsArray[8]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[8])) { echo $startnumbersArray[8]; }?><!--9--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(10, $contestantsArray)) { echo $contestantsArray[10]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[10])) { echo $startnumbersArray[10]; }?><!--11--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(12, $contestantsArray)) { echo $contestantsArray[12]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[12])) { echo $startnumbersArray[12]; }?><!--13--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(14, $contestantsArray)) { echo $contestantsArray[14]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[14])) { echo $startnumbersArray[14]; }?><!--15--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(16, $contestantsArray)) { echo $contestantsArray[16]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[16])) { echo $startnumbersArray[16]; }?><!--17--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(18, $contestantsArray)) { echo $contestantsArray[18]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[18])) { echo $startnumbersArray[18]; }?><!--19--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(20, $contestantsArray)) { echo $contestantsArray[20]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[20])) { echo $startnumbersArray[20]; }?><!--21--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(22, $contestantsArray)) { echo $contestantsArray[22]; }?></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[22])) { echo $startnumbersArray[22]; }?><!--23--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
<?php 
} //Show 11-24 contestans;
if ($totalRows_rsClassContestants > 3){ //With more than 3 contestans show AKA and AO table;?>
    <tr>
        <td colspan="21"><h3>&nbsp;</h3></td>      
    </tr>    
    <tr>
        <td><h3>Name</h3></td>      
        <td><h3>No.</h3></td>             
        <td><h3>1T</h3></td>             
        <td><h3>1A</h3></td>             
        <td><h3>2T</h3></td>             
        <td><h3>2A</h3></td>             
        <td><h3>3T</h3></td>             
        <td><h3>3A</h3></td>             
        <td><h3>4T</h3></td>             
        <td><h3>4A</h3></td>             
        <td><h3>5T</h3></td>             
        <td><h3>5A</h3></td>             
        <td><h3>6T</h3></td>             
        <td><h3>6A</h3></td>             
        <td><h3>7T</h3></td>             
        <td><h3>7A</h3></td>             
        <td><h3>TEC</h3></td>             
        <td><h3>ATH</h3></td>             
        <td><h3>Extra</h3></td>                     
        <td><h3>Total</h3></td>                     
        <td><h3>Rank</h3></td>                     
    </tr>        
<?php } //With more than 3 contestans show AKA and AO table;       
if ($totalRows_rsClassContestants === 4){ //Show 4 contestans;?>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
<?php
} //Show 4 contestans;    
if ($totalRows_rsClassContestants > 4 && $totalRows_rsClassContestants < 11){ //Show 5-10 contestans;?>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(5, $contestantsArray)) { echo $contestantsArray[5]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[5])) { echo $startnumbersArray[5]; }?><!--6--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>        
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(7, $contestantsArray)) { echo $contestantsArray[7]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[7])) { echo $startnumbersArray[7]; }?><!--8--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(9, $contestantsArray)) { echo $contestantsArray[9]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[9])) { echo $startnumbersArray[9]; }?><!--10--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>    
<?php 
} //Show 5-10 contestans;
if ($totalRows_rsClassContestants > 11) { //Show 11-24 contestans;?>
    <tr>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>     
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(5, $contestantsArray)) { echo $contestantsArray[5]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[5])) { echo $startnumbersArray[5]; }?><!--6--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(7, $contestantsArray)) { echo $contestantsArray[7]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[7])) { echo $startnumbersArray[7]; }?><!--8--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(9, $contestantsArray)) { echo $contestantsArray[9]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[9])) { echo $startnumbersArray[9]; }?><!--10--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(11, $contestantsArray)) { echo $contestantsArray[11]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[11])) { echo $startnumbersArray[11]; }?><!--12--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(13, $contestantsArray)) { echo $contestantsArray[13]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[13])) { echo $startnumbersArray[13]; }?><!--14--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(15, $contestantsArray)) { echo $contestantsArray[15]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[15])) { echo $startnumbersArray[15]; }?><!--16--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(17, $contestantsArray)) { echo $contestantsArray[17]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[17])) { echo $startnumbersArray[17]; }?><!--18--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(19, $contestantsArray)) { echo $contestantsArray[19]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[19])) { echo $startnumbersArray[19]; }?><!--20--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(21, $contestantsArray)) { echo $contestantsArray[21]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[21])) { echo $startnumbersArray[21]; }?><!--22--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
    <tr>    
       <td nowrap="nowrap"><?php if (array_key_exists(23, $contestantsArray)) { echo $contestantsArray[23]; }?></td>
       <td class="AO_blue"><?php if (isset($startnumbersArray[23])) { echo $startnumbersArray[23]; }?><!--24--></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>
       <td>&nbsp;</td>        
       <td>&nbsp;</td>                
       <td>&nbsp;</td>                
    </tr>
  </table>    
<?php
} //Show 11-24 contestans;
} //When not using Kata Point System

//When limit for Round Robin is not met or it's a team class - show elimination ladder
//If startnumber and contestant exist - show them with the proper colours
elseif ($totalRows_rsClassContestants > $comp_limit_roundrobin || $totalRows_rsClassContestants < 3 || $class_team === 1) { ?>
<div id="apDiv1">
  <table width="100%" border="0">
    <tr>
        <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; } else { echo "1"; }?><!--1--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[16])) { echo $startnumbersArray[16]; } else { echo "17"; }?><!--17--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(16, $contestantsArray)) { echo $contestantsArray[16]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>    
<div id="apDivOmg2_1"></div>
<div id="apDiv2">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[8])) { echo $startnumbersArray[8]; } else { echo "9"; }?><!--9--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(8, $contestantsArray)) { echo $contestantsArray[8]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[24])) { echo $startnumbersArray[24]; } else { echo "25"; }?><!--25--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(24, $contestantsArray)) { echo $contestantsArray[24]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_1"></div>
<div id="apDiv3">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; } else { echo "5"; }?><!--5--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (array_key_exists(20, $startnumbersArray)) { echo $startnumbersArray[20]; } else { echo "21"; }?><!--21--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(20, $contestantsArray)) { echo $contestantsArray[20]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_2"></div>
<div id="apDiv4">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[12])) { echo $startnumbersArray[12]; } else { echo "13"; }?><!--13--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(12, $contestantsArray)) { echo $contestantsArray[12]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[28])) { echo $startnumbersArray[28]; } else { echo "29"; }?><!--29--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(28, $contestantsArray)) { echo $contestantsArray[28]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_1"></div>
<div id="apDivFinal"></div>
<div id="apDiv5">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; } else { echo "3"; }?><!--3--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[18])) { echo $startnumbersArray[18]; } else { echo "19"; }?><!--19--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(18, $contestantsArray)) { echo $contestantsArray[18]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_2"></div>
<div id="apDiv6">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[10])) { echo $startnumbersArray[10]; } else { echo "11"; }?><!--11--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(10, $contestantsArray)) { echo $contestantsArray[10]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[26])) { echo $startnumbersArray[26]; } else { echo "27"; }?><!--27--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(26, $contestantsArray)) { echo $contestantsArray[26]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_3"></div>
<div id="apDiv7">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[6])) { echo $startnumbersArray[6]; } else { echo "7"; }?><!--7--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(6, $contestantsArray)) { echo $contestantsArray[6]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[22])) { echo $startnumbersArray[22]; } else { echo "23"; }?><!--23--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(22, $contestantsArray)) { echo $contestantsArray[22]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_4"></div>
<div id="apDiv8">
  <table width="100%" border="0">
    <tr>
      <td class="AKA_red"><?php if (isset($startnumbersArray[14])) { echo $startnumbersArray[14]; } else { echo "15"; }?><!--15--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(14, $contestantsArray)) { echo $contestantsArray[14]; } else { /*it does not exist*/ }?></td>
    </tr>
    <tr>
      <td class="AO_blue"><?php if (isset($startnumbersArray[30])) { echo $startnumbersArray[30]; } else { echo "31"; }?><!--31--></td><td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(30, $contestantsArray)) { echo $contestantsArray[30]; } else { /*it does not exist*/ }?></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_5"></div>
<div id="apDiv9">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; } else { echo "2"; }?><!--2--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(17, $contestantsArray)) { echo $contestantsArray[17]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[17])) { echo $startnumbersArray[17]; } else { echo "18"; }?><!--18--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_3"></div>
<div id="apDiv10">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(9, $contestantsArray)) { echo $contestantsArray[9]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[9])) { echo $startnumbersArray[9]; } else { echo "10"; }?><!--10--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(25, $contestantsArray)) { echo $contestantsArray[25]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[25])) { echo $startnumbersArray[25]; } else { echo "26"; }?><!--26--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_6"></div>
<div id="apDiv11">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(5, $contestantsArray)) { echo $contestantsArray[5]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[5])) { echo $startnumbersArray[5]; } else { echo "6"; }?><!--6--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(21, $contestantsArray)) { echo $contestantsArray[21]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[21])) { echo $startnumbersArray[21]; } else { echo "22"; }?><!--22--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg3_4"></div>
<div id="apDiv12">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(13, $contestantsArray)) { echo $contestantsArray[13]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[13])) { echo $startnumbersArray[13]; } else { echo "14"; }?><!--14--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(29, $contestantsArray)) { echo $contestantsArray[29]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[29])) { echo $startnumbersArray[29]; } else { echo "30"; }?><!--30--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg4_2"></div>
<div id="apDiv13">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; } else { echo "4"; }?><!--4--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(19, $contestantsArray)) { echo $contestantsArray[19]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[19])) { echo $startnumbersArray[19]; } else { echo "20"; }?><!--20--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_7"></div>
<div id="apDiv14">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(11, $contestantsArray)) { echo $contestantsArray[11]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[11])) { echo $startnumbersArray[11]; } else { echo "12"; }?><!--12--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(27, $contestantsArray)) { echo $contestantsArray[27]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[27])) { echo $startnumbersArray[27]; } else { echo "28"; }?><!--28--></td>
    </tr>
  </table>
</div>
<div id="apDiv15">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(7, $contestantsArray)) { echo $contestantsArray[7]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[7])) { echo $startnumbersArray[7]; } else { echo "8"; }?><!--8--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(23, $contestantsArray)) { echo $contestantsArray[23]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[23])) { echo $startnumbersArray[23]; } else { echo "24"; }?><!--24--></td>
    </tr>
  </table>
</div>
<div id="apDivOmg2_8"></div>
<div id="apDiv16">
  <table width="100%" border="0">
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(15, $contestantsArray)) { echo $contestantsArray[15]; } else { /*it does not exist*/ }?></td><td class="AO_blue"><?php if (isset($startnumbersArray[15])) { echo $startnumbersArray[15]; } else { echo "16"; }?><!--16--></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="result_tbl"><?php if (array_key_exists(31, $contestantsArray)) { echo $contestantsArray[31]; } else { /*it does not exist*/ }?></td><td class="AKA_red"><?php if (isset($startnumbersArray[31])) { echo $startnumbersArray[31]; } else { echo "32"; }?><!--32--></td>
    </tr>
  </table>
</div>
</div>
<div id="apDivRepechage"></div>          
<?php 
}//When limit for Round Robin is not met or it's a team class - show elimination ladder

//When limit for Round Robin is met - show round robin protocol
//If startnumber and contestant exist - show them with the proper colours
else { 
//Set start of match numbers
$matchNo = 1; ?>    
<div id="apMatchTbl">
  <table width="450" border="1">
    <tr>
        <td><h3>Match</h3></td>      
        <td><h3>Nr.</h3></td>             
        <td><h3>Namn</h3></td>      
        <td><h3>Po&auml;ng</h3></td>                     
        <td><h3>Vinst</h3></td>                     
    </tr>        
    <tr>
        <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>        
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>        
    </tr>
<?php if (array_key_exists(3, $contestantsArray)) { 
        //Set correct order of match numbers
        $matchNo = $matchNo + 1;?>        
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>      
<?php }
      if (array_key_exists(4, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>             
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>        
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>        
    </tr>            
<?php }
        $matchNo = $matchNo + 1;?>
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>                  
<?php if (array_key_exists(3, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>                   
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>                   
<?php } 
      if (array_key_exists(4, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>      
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>                   
<?php }
      if (array_key_exists(3, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>      
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>                                    
<?php } 
        $matchNo = $matchNo + 1;?>      
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?><!--1--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>                
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?><!--3--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>
<?php if (array_key_exists(4, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>      
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?><!--2--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>                   
<?php } 
      if (array_key_exists(3, $contestantsArray) && array_key_exists(4, $contestantsArray)) { 
        $matchNo = $matchNo + 1;?>
    <tr>
       <td rowspan="2"><h3><?php echo $matchNo ?></h3></td>
       <td class="AKA_red"><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?><!--4--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>               
    </tr>
    <tr>    
       <td class="AO_blue"><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?><!--5--></td>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td width="200">&nbsp;</td>
       <td width="25">&nbsp;</td>              
    </tr>                   
<?php } ?>
  </table>
</div>
<div id="apContTbl">
  <table width="350" border="1">
    <tr>
        <td><h3>Startnr.</h3></td>      
        <td><h3>Namn</h3></td>             
        <td><h3>Vinster</h3></td>      
        <td><h3>Po&auml;ng</h3></td>             
    </tr>  
    <tr>    
       <td><?php if (isset($startnumbersArray[0])) { echo $startnumbersArray[0]; }?></td>
       <td nowrap="nowrap"><?php if (array_key_exists(0, $contestantsArray)) { echo $contestantsArray[0]; }?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>               
    </tr>                        
    <tr>    
       <td><?php if (isset($startnumbersArray[1])) { echo $startnumbersArray[1]; }?></td>
       <td nowrap="nowrap"><?php if (array_key_exists(1, $contestantsArray)) { echo $contestantsArray[1]; }?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>               
    </tr>                         
    <tr>    
       <td><?php if (isset($startnumbersArray[2])) { echo $startnumbersArray[2]; }?></td>
       <td nowrap="nowrap"><?php if (array_key_exists(2, $contestantsArray)) { echo $contestantsArray[2]; }?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>               
    </tr>                        
    <tr>    
       <td><?php if (isset($startnumbersArray[3])) { echo $startnumbersArray[3]; }?></td>
       <td nowrap="nowrap"><?php if (array_key_exists(3, $contestantsArray)) { echo $contestantsArray[3]; }?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>                      
    </tr>   
    <tr>    
       <td><?php if (isset($startnumbersArray[4])) { echo $startnumbersArray[4]; }?></td>
       <td nowrap="nowrap"><?php if (array_key_exists(4, $contestantsArray)) { echo $contestantsArray[4]; }?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>                      
    </tr>  
  </table>    
</div>
<?php 
}//When limit for Round Robin is met - show round robin protocol
?>
      <?php if ($class_discipline === 'Kata' && $class_discipline_variant === 1){
                echo '<div id="apDivPDFKataPoint">
                      <a href=DomPDF.php?class_id='.$colname_rsClassData.'>T&auml;vlingsstege som PDF</a></div>
                      <div id="apSponsorPic1KataPoint"></div>
                      <div id="apSponsorPic2KataPoint"></div>
                      <div id="apSponsorPic3KataPoint"></div>
                      <div id="apSponsorPic4KataPoint"></div>
                      <div id="apSponsorPic5KataPoint"></div>
                      <div id="apSponsorPic6KataPoint"></div>
                      <div id="apDivResultatKataPoint">';
            }
            else {
                if ($totalRows_rsClassContestants > $comp_limit_roundrobin || $totalRows_rsClassContestants < 3 || $class_team = 1){ 
                    echo '<div id="apDivPDF">
                          <a href=DomPDF.php?class_id='.$colname_rsClassData.'>T&auml;vlingsstege som PDF</a></div>
                          <div id="apSponsorPic1"></div>
                          <div id="apSponsorPic2"></div>
                          <div id="apSponsorPic3"></div>
                          <div id="apSponsorPic4"></div>
                          <div id="apSponsorPic5"></div>
                          <div id="apSponsorPic6"></div>                          
                          <div id="apDivResultat">'; 
                } 
                else { 
                    echo '<div id="apDivPDF">
                          <a href=DomPDF.php?class_id='.$colname_rsClassData.'>T&auml;vlingsstege som PDF</a></div>
                          <div id="apSponsorPic1"></div>
                          <div id="apSponsorPic1"></div>
                          <div id="apSponsorPic2"></div>
                          <div id="apSponsorPic3"></div>
                          <div id="apSponsorPic4"></div>
                          <div id="apSponsorPic5"></div>
                          <div id="apSponsorPic6"></div>
                          <div id="apDivResultatRR">'; 
                }
            }; 
            ?>
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
/*} else { 
    echo 'Inga deltagare i klassen!';    
}//If there are no class contestants*/

//Kill statements and DB connection
$stmt_rsClassContestants->closeCursor();
$stmt_rsClass->closeCursor();
$stmt_rsResult->closeCursor();
$DBconnection = null;
?>
  </table>
</div>
    </div> 
</div>
</div>
</body>
</html>