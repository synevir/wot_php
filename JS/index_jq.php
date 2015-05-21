<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wold Of Tanks farm table page</title>
    <script type="text/javascript" src="jquery-2.1.3.js"> </script>
</head>
<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366" BACKGROUND = "background.jpg">

  <table border=0 width=100%>
    <tr>
      <td> The current date is <?php print (date ("D M d H:i:s T Y")); ?>. </td>
      <td align="right">
	<a href="/index.php?&lang=rus"><img src="Gif/rus.gif" border=0 alt="Russian"></a>
	&nbsp;
	<a href="/index.php?&lang=eng"><img src="Gif/eng.gif" border=0 alt="English"></a>
      </td>
    </tr>
  </table>

  <hr />


<?php

  function print_text($dbc, $text, $lang='rus'){
      $result='';
      $query = "SELECT $text FROM txt WHERE lang='$lang'";
      if (!$data = mysqli_query($dbc, $query))
	  echo 'query error - '.$query;

      while ($row = mysqli_fetch_array($data)) 
	  $result = htmlspecialchars($row[0]);

      return($result);
  }

  function print_link($dbc, $link, $page, $lang='rus'){
      $result="<a href=\"$page\"> ";
      $query = "SELECT $link FROM txt WHERE lang='$lang'";
      if (!$data = mysqli_query($dbc, $query))
	  echo 'query error - '.$query;

      while ($row = mysqli_fetch_array($data)) 
	  $result .= htmlspecialchars($row[0]);
      
      $result .= '</a> ';
      return($result);
  }

  function generate_table($dbc,$columns,$where='', $order_by, $sort, $limit=4){
      
      $query = "SELECT $columns FROM t1 $where  ORDER BY $order_by $sort LIMIT $limit";
      if (!$data = mysqli_query($dbc, $query))
	    echo 'query error - '.$query;
//        echo "<br> $query <br>\n";
      $column = explode(",",$columns);

      echo "<table border=1>\n";
      // first row of table
      echo "  <tr>\n";
      foreach($column as $i) { 
	  echo "<td> &nbsp&nbsp&nbsp&nbsp<b>
		<a href=\"javascript:void(0)\" onclick=\"document.forms.select_form.submit();
		return false;\"> $i</a>
		</b>&nbsp&nbsp&nbsp&nbsp</td>\n";
       } 
// 	    first row values as simple text
// 	    echo "<td><b> $i </b></td>"; }
      echo "  </tr>\n";

// Loop through the array of user data, formatting it as HTML table
// $odd_value -variable for difrent row color fill   
      $odd_value = 1;
      while ($row = mysqli_fetch_array($data)) {
	  if (($odd_value % 2) == 0 )
	       echo '   <tr bgcolor="BDB76B"> ';
	  else echo '   <tr> ';
	  $odd_value = $odd_value + 1;

	  // выравнивание колонок по центру кроме name & country
 	  foreach($column as $i) 
		if ( $i == 'name' OR $i == 'country' )
		    echo '<td>'. htmlspecialchars( $row[$i] ).'</td> ';

///////////////// uncomment next rows for highlighting profit colums /////////////////////
//											//
// 		else									//
// 		   if ( $i == 'profit_battle' OR $i == 'profit_battle_premium' )	//
// 			echo '<td align="center" bgcolor="mediumaquamarine">'. 		//
//			      htmlspecialchars( $row[$i] ).' </td>'; 			//
//											//
//////////////////////////////////////////////////////////////////////////////////////////

		else
		    echo '<td align="center">'. htmlspecialchars( $row[$i] ).'</td>'; 
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
		$str = $str."	<option selected value=\"$val\">$val</option>\n";
	    else 
		$str = $str."	<option value=\"$val\">$val</option>\n";
	}
/* variant whith "for" operator:
	for ($i = 0; $i < count($values); $i++){
  	    $values[$i] = htmlspecialchars($values[$i]);
	    if ($i == $selected)
		$str = $str."<option selected value=\"$values[$i]\">$values[$i]</option>\n";
	    else 
		$str = $str."<option value=\"$values[$i]\">$values[$i]</option>\n";
	}
*/
	$str = $str."</select><br />\n";
	return($str);
     }

 function MakeCheckBoxsInTable($name, $values, $checked_boxes, $box_per_cell=2, $vertical=True){
	if ( !is_array($values) )
	    return ("make_check_boxs: values argument must be an array");
	if ( !is_array($checked_boxes) )
	    return ("make_check_boxs: checked_boxs argument must be an array");

	$string_result = "<table border=0>\n<tr>\n   <td nowrap>\n";
	$i = 0;
        foreach($values as $cb_name){
	    $i = $i+1;
	    $string_result .= "<input type=\"checkbox\" name=\"$cb_name\" value=\"$cb_name\" ";
			    // отметка значений по умолчанию
	    if(in_array($cb_name,$checked_boxes) )
		  $string_result .= "checked=\"checked\"";
	    $string_result .= "/>$cb_name";
	    if($vertical) 
		  $string_result .= "<br />\n";
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

  $lang = 'rus';
  echo '<p>'.print_text($dbc,'paragraph1',$lang).print_link($dbc,'link_1','about.htm')."</p>\n\n";
  echo '<p>'.print_text($dbc,'paragraph2',$lang)."</p>\n\n";



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


// ================== Grab data from the $_POST ============================
// =========================================================================
      if (isset($_POST['limit']) ) { 
	  $columns = '';
	  $limit   = $_POST['limit'];

	  if ($_POST['sort_popUp'] == 'по убыванию'){
		$sort = '';		
		$_POST['sort_popUp'] = 'по возрастанию';
	  }
	  else 
		$sort = 'DESC';  	

	  foreach($check_boxs as $val){
	      if (isset($_POST[$val]) && $_POST[$val] == $val)
		  $columns .=",$val";
	  }
	  $columns = substr($columns, 1);  // delete first "," in string

// Формирование предиката WHERE для запроса и запоминание выбранных 
// значений для <select checked=... > в PopUpMenu()
	  $_array = array();		   // array of WHERE filters

	  if (isset($_POST['type_popUp']) AND $_POST['type_popUp'] != 'all'){
	    $_array['type'] = $_POST['type_popUp'];
		      $type = $_POST['type_popUp'];
	  }

	  if (isset($_POST['country2']) AND $_POST['country2'] != 'all'){
	    $_array['country'] = $_POST['country2'];
		     $country2 = $_POST['country2'];
	  }
      
	  $where = CreateWhere($_array);
      }

// =======================================================================
//		 		Global filters
// limits value for output pop_menu 'filter amount rows in data table'
      $pop_menu_limit_values = array(10,20,40);
// sort order for output pop_menu 'DESC INC'
      $pop_menu_sorting_value = array('по убыванию','по возрастанию');
      if ($sort == 'DESC') 
	   $sorting_value_selected = 'по убыванию';
      else $sorting_value_selected = 'по возрастанию';

      // array of columns for output
      $checked_boxs = explode(",",$columns);

$txt_submit_button_value = print_text($dbc,'submit_button_value',$lang);
$txt_strings_per_page 	 = print_text($dbc,'strings_per_page',$lang);
$txt_sort_order		 = print_text($dbc,'sorting_by',$lang);
$txt_types_tanks	 = print_text($dbc,'types_tank',$lang);
$txt_country		 = print_text($dbc,'country',$lang);


// *********** таблица фильтров колонок//table filter's columns by "php" **********
// ********************************************************************************

  echo "<form name=\"select_form\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
  echo "  <input type=\"submit\" value=\"$txt_submit_button_value\" name=\"submit_button\" /><br />\n";
  echo "<table border=0 >\n";
  echo "	<tr>\n";
  echo "	  <td align=\"center\">$txt_strings_per_page:<br />\n";
  echo 	      	MakePopupMenu('limit', $pop_menu_limit_values, $limit);
  echo "	  </td>\n";
  echo "	  <td align=\"center\">$txt_sort_order :<br />\n";
  echo 		MakePopupMenu('sort_popUp', $pop_menu_sorting_value, $sorting_value_selected);
  echo "	  </td>\n";
  echo "	  <td align=\"center\">$txt_types_tanks :<br />\n";
  echo 		MakePopupMenu('type_popUp', $pop_menu_type_values, $type);
  echo "	  </td>\n";
  echo "	  <td align=\"center\">$txt_country :<br />\n";
  echo 		MakePopupMenu('country2', $pop_menu_country_values, $country2);
  echo "	  </td>\n";
  echo "	</tr>";
  echo "</table>";

?>
<!--*********** таблица фильтров колонок//table filter's columns ********** 

<form name="select_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="submit" value="Применить выбранные фильты" name="submit_button" /><br />
   <table border=0 >
      <tr>
	  <td align="center">строк на страницу:<br />
	      <?php echo MakePopupMenu('limit', $pop_menu_limit_values, $limit)?>
	  </td>
	  <td align="center">порядок сортировки:<br />
	      <?php echo MakePopupMenu('sort_popUp', $pop_menu_sorting_value, $sorting_value_selected) ?>
	  </td>
	  <td align="center">виды танков:<br />
	      <?php echo MakePopupMenu('type_popUp', $pop_menu_type_values, $type) ?>
	  </td>
	  <td align="center">cтрана:<br />
	      <?php echo MakePopupMenu('country2', $pop_menu_country_values, $country2) ?>
	  </td>

      </tr>
    </table>
-->
<!-- Фильтры колонок под спойлером
// Checkboxes fiters under spoiler      -->
    <div class="spoil">
	<div class="smallfont">
	<hr />
	    <input type="button" value="Выбор колонок для вывода" class="input-button" id ="btn_spoiler">
	</div>
	<div class="alt2">
		<div id="div_spoiler" style="display: none;">
		    Select columns for display/ Выберите колонки для отображения
		    <?php
			echo MakeCheckBoxsInTable('columns', $check_boxs, $checked_boxs, 5);
		    ?>     
		</div>
	</div>
    </div>
    <hr />


<!--****************** Output Main Table *********************************************** -->

<?php
  echo '<br /> *Цена на премиумную технику указана в игровом золоте';
  generate_table($dbc, $columns, $where, $order_by, $sort, $limit);



  $query = "SELECT length(name) FROM t1 ORDER BY level LIMIT $limit";
      if (!$data = mysqli_query($dbc, $query))
	    echo 'query error - '.$query;
while ($row = mysqli_fetch_array($data)) 
  echo "<br>$row[0]"."-$order_by";


  mysqli_close($dbc);
?>



<script type='text/javascript'>

	function hide_element(element,time){
		$(element).slideUp(time);
	}
	function unhide_element(element,time){
		$(element).slideDown(time);
	}

$(document).ready(function(){
	var flag = true;
	
	$("#btn_spoiler").click(function(){
		
		if(flag) {
			this.value="Скрыть ↑↑↑↑↑↑↑↑";
			flag = false;
		}
		else {
			this.value="Показать ↓↓↓↓↓↓↓";
			flag = true;
		}
		
		
		
// 		return(this.tog = !this.tog) ?  unhide_element("#div_spoiler", 500) :hide_element("#div_spoiler", 500);
		return(this.tog = !this.tog) ?  $("#div_spoiler").slideDown(500) : $("#div_spoiler").slideUp(500);
		
	});
});

</script>


      </form>
  </body>
</html>
