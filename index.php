<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple PHP Page</title>
</head>
<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366" BACKGROUND = "background.jpg">

<p>Hello.</p>
<p>The current date is <?php print (date ("D M d H:i:s T Y")); ?>.</p>
<hr />

<?php

  function generate_table($dbc,$columns,$order_by, $sort, $limit=4){   
      $query = "SELECT $columns FROM t1 ORDER BY $order_by $sort LIMIT $limit";
      if (!$data = mysqli_query($dbc, $query))
	  echo 'query error - '.$query;
      echo "<br>$query";
      $column = explode(",",$columns);

      echo "<table border=1>\n";
// first row of table
      echo '<tr>';
      foreach($column as $i) { echo "<td><b> $i </b></td>"; }
      echo "</tr>\n";

// Loop through the array of user data, formatting it as HTML table
      while ($row = mysqli_fetch_array($data)) {
	  echo '<tr>';
 	  foreach($column as $i) 
		echo '<td>'. htmlspecialchars( $row[$i] ).' </td>'; 
	  echo "</tr>\n";
      }
      echo "</table><br />\n";
      return (0);
  }
 function MakeRadioButtons($name,$array_string,$vertical = False){
	if (!is_array ($array_string))
	    return ("make_radio_buttons: values argument must be an array");
    	$str='';
	foreach($array_string as $rb_name){
 	  $rb_name = htmlspecialchars( substr($rb_name,1,(strlen($rb_name)-2)) );
	  $str = $str."<input type=\"radio\" name=\"$name\" value=\"$rb_name\" />$rb_name";
	  if($vertical) 
	      $str = $str.'<br />';
	}
	unset($rb_name);
	return($str);
     }

 function MakePopupMenu($name,$values,$selected=0){
	if (!is_array($values) )
	    return ("make_popup_menu: values argument must be an array");
    	$str = "<select name=\"$name\">\n";
// 	$str = $str."<option value=\"$default_value\">$default_value</option>\n";
	for ($i = 0; $i < count($values); $i++){
  	    $values[$i] = htmlspecialchars($values[$i]);
	    if ($i == $selected)  
		$str = $str."<option selected value=\"$values[$i]\">$values[$i]</option>\n";	      
	    else 
		$str = $str."<option value=\"$values[$i]\">$values[$i]</option>\n";
	}
	$str = $str."</select><br />\n";	
	return($str);
     }

 function MakeCheckBoxsInTable($name, $values, $checked_boxes, $box_per_cell=2, $vertical = True){
	if (!is_array($values) )
	    return ("make_check_boxs: values argument must be an array");
	if (!is_array($checked_boxes) )
	    return ("make_check_boxs: checked_boxs argument must be an array");
	$string_result = "<table border=0>\n<tr>\n   <td nowrap>\n";
	$i=0;
        foreach($values as $cb_name){
	  $i = $i+1;
	  $string_result .= "<input type=\"checkbox\" name=\"$name\"value=\"$cb_name\" ";
		  // отметка значений по умолчанию
	  if(in_array($cb_name,$checked_boxes) )
		$string_result .= "checked=\"checked\"";   
	  $string_result .= "/>$cb_name";
	  if($vertical) $string_result .= "<br />";
 	  if ( (($i % $box_per_cell) == 0) AND ($i < count($values)) ) 
 		$string_result .= "\n   </td>\n   <td nowrap >\n";
	  
	  
	}	
	unset($cb_name);
	$string_result.="   </td>\n</tr>\n</table>";
	return($string_result);
    }


//-------------------------- Main code -------------------------------------
 // session_start();
  require_once('connect.php');		// data for connect to MySQL database

  if (!$dbc=mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME))
      echo ' Ошибка соединеиния с базой';
  $query = 'SHOW TABLES';
  $data  = mysqli_query($dbc, $query);

  echo 'Current database <b>'.DB_NAME.'</b> include table: <br>';

	      // вывод маркерованного списка/out put marked list names of table 
  echo "<ul>\n";    
  while($row = mysqli_fetch_row($data)) {
      echo "<li>$row[0] </li>\n";
  }
  echo "</ul>\n";
  echo "<hr />";


// ---------------------- Default values -----------------------------------
  $columns  = 'name,country,level,xp,profit_battle,profit_battle_premium';
  $order_by = 'level';
  $sort     = 'DESC';
  $limit    = 20;
//--------------------------------------------------------------------------

// выбор выводимыx колонок
  $defaults_checked_boxs = explode(",",$columns);
  $ignore_boxs = array('name2','zver','zapas_mass','to_top');
  $check_boxs  = array();
  
  $query = 'SHOW COLUMNS FROM t1';
  if(!$data = mysqli_query($dbc, $query))
       echo 'query error<br>';
  while($row = mysqli_fetch_row($data)){
	if (! in_array($row[0],$ignore_boxs) )
	    $check_boxs[] = $row[0];	 
	}
  

  // Grab the profile data from the POST
  if (isset($_POST['submit'])) { 
      $limit = $_POST['limit'];

      if ($_POST['sort'] == 'по убыванию')
	 $sort = 'DESC';      
  }


?>
<!--*************** таблица фильтров table of filter's **************** -->

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="submit" value="Применить" name="submit" /><br />
   <table border=0>
      <tr>
	  <td>строк на страницу:<br />
<?php 		if (!empty($limit)) 
		      $pop_menu_limit_values = array($limit,10,20,40);
		else  $pop_menu_limit_values = array(5,40,60);
		echo MakePopupMenu('limit',$pop_menu_limit_values)
?>
	  </td>
	  <td>порядок сортировки:<br />
	  <?php $sorting_value = array('по убыванию','по возрастанию');
		echo MakePopupMenu('sort',$sorting_value) ?>
	  </td>
      </tr>
    </table>

<!-- Фильтры колонок под спойлером
// Checkboxes fiters under spoiler -->
    <div class="spoil">
    <div class="smallfont">
    <hr />
	<input type="button" value="Нажмите что бы открыть или закрыть спойлер" class="input-button" 
	      onclick=
		"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '')
		    { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; 
		    this.innerText = ''; this.value = 'Свернуть'; } 
		else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; 
		    this.innerText = ''; this.value = 'Развернуть фильтры'; }">
    </div>
    <div class="alt2">
    <div style="display: none;">
    Текст внутри спойлера
	  <?php
	    echo MakeCheckBoxsInTable('columns',$check_boxs, $defaults_checked_boxs,5);
	  ?>     
    </div>
    </div>
    </div>
    <hr />
</form>

<!--******************************************************************* -->

<?php

  generate_table($dbc,$columns,$order_by, $sort, $limit);


  mysqli_close($dbc);
  
?>

</body>
</html>
