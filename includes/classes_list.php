<?php 
//Added classes for table layout in css file
//Replaced width="100%" with class="wide_tbl"
$sorting = "class_team, class_discipline, class_gender, class_age, class_weight_length, class_gender_category";
if (filter_input(INPUT_GET, 'sorting')) {
$sorting = filter_input(INPUT_GET, 'sorting');
}

try {
    //Select all classes for respective competition
    require('Connections/DBconnection.php');           
    $query1 = "SELECT c.class_id, c.comp_id, c.class_team, c.class_category, c.class_discipline, c.class_discipline_variant, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, c.class_fee, com.comp_name FROM classes AS c INNER JOIN competition AS com USING (comp_id) WHERE comp_current = 1 ORDER BY $sorting";
    $stmt_rsClasses = $DBconnection->query($query1);
    $totalRows_rsClasses = $stmt_rsClasses->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }               
    
if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
<p>Det finns inga t&auml;vlingsklasser att visa &auml;n!</p>
<?php } // Show if recordset empty

if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?> 
    <h3>Befintliga t&auml;vlingsklasser</h3>
    <?php if ($MM_authorizedUsers === "1"){ ?>
    <p>Se startlistan av t&auml;vlande eller hela t&auml;vlingsstegen, &auml;ndra eller ta bort t&auml;vlingsklasser genom att klicka p&aring; respektive l&auml;nk. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
    <?php } else {?>
    <p>Se startlistan &ouml;ver t&auml;vlande genom att klicka p&aring; l&auml;nken.<br> <strong>Obs! T&auml;vlingsstegarna visas p&aring; separat sida efter sista anm&auml;lningsdagen och d&aring; lottningen &auml;r gjord!</strong></p>
    <?php }?>
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="270" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="class_team, class_discipline, class_gender, class_age, class_weight_length, class_gender_category"<?php if (!(strcmp($sorting, "class_team, class_discipline, class_gender, class_age, class_weight_length, class_gender_category"))) {echo "selected=\"selected\"";} ?>>Typ av klass</option>
      <option value="class_discipline, class_team, class_gender, class_age, class_weight_length, class_gender_category"<?php if (!(strcmp($sorting, "class_discipline, class_team, class_gender, class_age, class_weight_length, class_gender_category"))) {echo "selected=\"selected\"";} ?>>Klassens disciplin</option>
      <option value="class_age, class_team, class_discipline, class_gender, class_weight_length, class_gender_category"<?php if (!(strcmp($sorting, "class_age, class_team, class_discipline, class_gender, class_weight_length, class_gender_category"))) {echo "selected=\"selected\"";} ?>>Klassens &aring;lder</option>
</select>
      </label></td>
      <td><input type="submit" name="submit" class= "button" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
    <table class="wide_tbl" border="1">
      <thead>
      <tr>
        <th>Typ av klass</th>
        <th>Disciplin</th>
        <th>Kata-system</th>
        <th>K&ouml;ns-kategori</th>
        <th>Kategori</th>
        <th>&Aring;lder</th>
        <th>Vikt- eller l&auml;ngdkategori</th>
        <th>Startlista</th>
        <?php if ($MM_authorizedUsers === "1"){ ?>
        <th>Avgift</th>        
        <th>T&auml;vlingssteg</th>        
        <th>&Auml;ndra</th>
        <th>Ta bort</th>
        <?php }?>
      </tr>
      </thead>
<?php while($row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC)) { ?>
  <tr>
    <td><?php if($row_rsClasses['class_team'] === 1) { echo 'Lag';} else{ echo 'Individuell';} ?></td>
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php if ($row_rsClasses['class_discipline'] === "Kata"){
                        if ($row_rsClasses['class_discipline_variant'] === 0){ 
                        echo 'Flaggor';
                        }
                        else {
                        echo 'Po&auml;ng';
                        }
                    }; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
          <?php if ($MM_authorizedUsers === "1"){ ?>
          <td><a href="ClassContestants.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>                    
          <td><?php echo $row_rsClasses['class_fee'].' kr'; ?></td>
          <td><a href="javascript:MM_openBrWindow('ElimLadder.php?class_id=<?php echo $row_rsClasses['class_id']; ?>','T&auml;vlingsstege','',1145,800,'true')">T&auml;vlingsstege</a></td>          
          <td><a href="ClassUpdate.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">&Auml;ndra</a></td>
          <td><a href="#" onclick="return deleteClass('<?php echo $row_rsClasses['class_id']; ?>')">Ta bort</a></td>
          <?php } else {?>
          <td><a href="ClassContestants_loggedout.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>          
          <?php }?>
  </tr>
<?php } ?>
    </table>
<?php 
} // Show if recordset not empty 
?>
  </div>
</div>
<?php
//Kill statement
$stmt_rsClasses->closeCursor();
include("includes/footer.php");
?>
</body>
</html>