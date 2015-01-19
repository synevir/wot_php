<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wold Of Tanks farm table page</title>
</head>
<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366" BACKGROUND = "background.jpg">

<p>Hello.</p>
<p>The current date is <?php print (date ("D M d H:i:s T Y")); ?>.</p>
<p>На этой странице Вашему вниманию представлена таблица доходности всей техники игры
"World of Tanks". Любителям танковых сражений эта таблица поможет проанализировать различные показатели
боевых машин и целесообразность преобретения той или иной машины, в зависимости от целей, которые поставил себе
игрок</p>
<hr />

<?php

  function generate_table($dbc,$columns,$where='', $order_by, $sort, $limit=4){   
      
      $query = "SELECT $columns FROM t1 $where  ORDER BY $order_by $sort LIMIT $limit";      
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

// выравнивание колонок по центру кроме name & country
 	  foreach($column as $i) 
		if ( $i == 'name' OR $i == 'country' )
		   echo '<td>'. htmlspecialchars( $row[$i] ).' </td>';  
		else
		   echo '<td align="center">'. htmlspecialchars( $row[$i] ).' </td>'; 
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

 function MakePopupMenu($name,$values,$selected){
	if (!is_array($values) )
	    return ("make_popup_menu: values argument must be an array");

     	$str = "<select name=\"$name\">\n";
	foreach($values as $val){
	    $val = htmlspecialchars($val);
	    if ($val == $selected)  
		$str = $str."<option selected value=\"$val\">$val</option>\n";	      
	    else 
		$str = $str."<option value=\"$val\">$val</option>\n";
	}
/*
	for ($i = 0; $i < count($values); $i++){
  	    $values[$i] = htmlspecialchars($values[$i]);
	    if ($i == $selected)  
		$str = $str."<option selected value=\"$values[$i]\">$values[$i]</option>\n";	      
	    else 
		$str = $str."<option value=\"$values[$i]\">$values[$i]</option>\n";
	}*/
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
	  $string_result .= "<input type=\"checkbox\" name=\"$cb_name\" value=\"$cb_name\" ";
		  // отметка значений по умолчанию
	  if(in_array($cb_name,$checked_boxes) )
		$string_result .= "checked=\"checked\"";   
	  $string_result .= "/>$cb_name";
	  if($vertical) $string_result .= "<br />\n";
 	  if ( (($i % $box_per_cell) == 0) AND ($i < count($values)) ) 
 		$string_result .= "\n   </td>\n   <td nowrap >\n";
	
	}	

	unset($cb_name);
	$string_result.="   </td>\n</tr>\n</table>";
	return($string_result);
    }
  function CreateWhere($arr){
      if (! is_array($arr) ) return('function CreateWhere error: argument must be an array!');
      if ( count($arr) == 0) return('');

      $string='WHERE ';
      foreach($arr as $key => $val)
	  $string .= "$key='$val' AND ";

      $string = substr($string, 0, strlen($string)-4 );
      return($string);
  }




//-------------------------- Main code -------------------------------------
  require_once('connect.php');		// data for connect to MySQL database

  if (!$dbc=mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME))
      echo ' Ошибка соединеиния с базой';
  

// ------------------------ Default values ---------------------------------
  $columns  = 'name,country,level,xp,profit_battle,profit_battle_premium';
  $where    = '';
  $order_by = 'level';
  $sort     = 'DESC';
  $limit    = 20;
  $type	    = 'all';
  $country2 = 'all';
//--------------------------------------------------------------------------


// выбор выводимыx колонок
  $checked_boxs = explode(",",$columns);
  $ignore_boxs  = array('name2','zver','zapas_mass','to_top','country2');
  $check_boxs   = array();

// выборка из таблицы всех колонок для фильтров
  $query = 'SHOW COLUMNS FROM t1';
  if(!$data = mysqli_query($dbc, $query))
       echo 'query error<br>';

// формирование массива для check_boxs и select фильтров
  while($row = mysqli_fetch_row($data)){
				   //исключение из массива некоторых колонок $ignore_boxs
      if (! in_array($row[0],$ignore_boxs) )
	  $check_boxs[] = $row[0];	 
				   //извлечение типов техники в строку $pop_menu_type_values
      if ( $row[0] == 'type' AND substr($row[1],0,4) == 'enum' ){
 	  $pop_menu_type_values = explode(",", substr($row[1],5,strlen($row[1])-6) );
	  $pop_menu_type_values = preg_replace('/\'/','',$pop_menu_type_values);
	  array_unshift($pop_menu_type_values, "all");
      }				  //извлечение названий стран в строку $pop_menu_country_values
      if ( $row[0] == 'country2' AND substr($row[1],0,4) == 'enum' ){
 	  $pop_menu_country_values = explode(",", substr($row[1],5,strlen($row[1])-6) );
	  $pop_menu_country_values = preg_replace('/\'/','',$pop_menu_country_values);
	  array_unshift($pop_menu_country_values, 'all');
      }
  
  }
 


//================ Grab the profile data from the POST ==================
//=======================================================================
  if (isset($_POST['submit'])) { 
      $columns = '';
      $limit   = $_POST['limit'];

      if ($_POST['sort'] == 'по убыванию')
	   $sort = 'DESC';      
      else $sort = '';

      foreach($check_boxs as $val){
	  if (isset($_POST[$val]) && $_POST[$val] == $val)
	      $columns .=",$val";	
      }
      $columns = substr($columns, 1);  // delete first "," in string

// формирование предиката WHERE для запроса
      $_array = array();		// array of filters

      if (isset($_POST['type_popUp']) AND $_POST['type_popUp'] != 'all'){
 	 $_array['type'] = $_POST['type_popUp'];
		   $type = $_POST['type_popUp'];
      }
      if (isset($_POST['country2']) AND $_POST['country2'] != 'all'){
	 $_array['country'] = $_POST['country2'];
		  $country2 = $_POST['country2'];
      }
      var_dump($_array);
      $where = CreateWhere($_array);
  }

  $checked_boxs = explode(",",$columns);
         
// =======================================================================
// =======================================================================
//		 		Global filters
// limits value for output pop_menu 'filter amount rows in data table'
    if (!empty($limit)) 
	  $pop_menu_limit_values = array($limit,10,20,40);
    else  $pop_menu_limit_values = array(5,40,60);
		
// sort order for output pop_menu 'DESC INC'
  $pop_menu_sorting_value = array('по убыванию','по возрастанию');

// type values for pop_menu 'filter of types'

?>
<!--*********** таблица фильтров колонок//table filter's columns ********* -->

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="submit" value="Применить" name="submit" /><br />
   <table border=0>
      <tr>
	  <td align="center">строк на страницу:<br />
	      <?php echo MakePopupMenu('limit',$pop_menu_limit_values,$limit)?>
	  </td>
	  <td align="center">порядок сортировки:<br />
	      <?php echo MakePopupMenu('sort',$pop_menu_sorting_value,$pop_menu_sorting_value[0]) ?>
	  </td>
	  <td align="center">виды танков:<br />
	      <?php echo MakePopupMenu('type_popUp',$pop_menu_type_values,$type) ?>
	  </td>
	  <td align="center">cтрана:<br />
	      <?php echo MakePopupMenu('country2',$pop_menu_country_values,$country2) ?>
	  </td>

      </tr>
    </table>

<!-- Фильтры колонок под спойлером
// Checkboxes fiters under spoiler   -->
    <div class="spoil">
    <div class="smallfont">
    <hr />
	<input type="button" value="Выбор колонок для вывода" class="input-button" 
	      onclick=
		"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '')
		    { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; 
		    this.innerText = ''; this.value = 'Свернуть'; } 
		else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; 
		    this.innerText = ''; this.value = 'Развернуть фильтры'; }">
    </div>
    <div class="alt2">
    <div style="display: none;">
    Select columns for display/ Выберите колонки для отображения
	  <?php
	    echo MakeCheckBoxsInTable('columns',$check_boxs, $checked_boxs,5);
	  ?>     
    </div>
    </div>
    </div>
    <hr />
</form>

<!--******************************************************************* -->

<?php

  echo '<br /> *Цена на премиумную технику указана в игровом золоте';
  generate_table($dbc,$columns,$where,$order_by, $sort, $limit);


  mysqli_close($dbc);
  
?>

</body>
</html>
