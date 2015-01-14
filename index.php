<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple PHP Page</title>
</head>
<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366">

<p>Hello.</p>
<p>The current date is <?php print (date ("D M d H:i:s T Y")); ?>.</p>
<hr />

<?php

  function generate_table($dbc,$columns,$order_by, $desk='DESC', $limit=4,$ignore_id=null){   
      $query = "SELECT $columns FROM t1 ORDER BY $order_by $desk LIMIT $limit";
      if (!$data = mysqli_query($dbc, $query))
	  echo 'query error - '.$query;
      echo $query;
      $column = explode(",",$columns);

      echo "<table border=1>\n";
// first row of table
      echo '<tr>';
      foreach($column as $i) { echo "<td><b> $i </b></td>"; }
      echo "</tr>\n";

// Loop through the array of user data, formatting it as HTML table
      while ($row = mysqli_fetch_array($data)) {
	  echo '<tr>';
 	  foreach($column as $i){ echo "<td> $row[$i] </td>"; } 	
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

 function MakePopupMenu($name,$values){
	if (!is_array($values) )
	    return ("make_popup_menu: values argument must be an array");
    	$str = "<select name=\"$name\">\n";
	for ($i = 0; $i < count($values); $i++){
  	    $values[$i] = htmlspecialchars($values[$i]);
	    $str = $str."<option value=\"$values[$i]\">$values[$i]</option>\n";
	}
	$str = $str."</select><br />\n";	
	return($str);
     }

//-------------------------- Main code -------------------------------------
  session_start();
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

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="submit" value="Применить" name="submit" /><br />
</form>

<?php


//*********************  таблица фильтров table of filter's ******************
  echo "<table border=0>\n";
  echo '<tr>';
      echo "<td>строк на страницу:<br />\n";
  $pop_menu_limit_values = array(20,40,60);
      echo MakePopupMenu('limit',$pop_menu_limit_values).'</td>';

      echo "<td>порядок сортировки:<br />\n";
  $order_by = array('по убыванию','по возрастанию');
      echo MakePopupMenu('order',$order_by).'</td>';
  echo '</tr>';
  echo '</table>';
//****************************************************************************
  
  $columns  = 'name,country,level,xp';
  $order_by = 'level';
  $desk     = 'DESC';
  $limit    = 20;
  generate_table($dbc,$columns,$order_by, $desk, $limit);


  mysqli_close($dbc);
  
?>


</body>
</html>
