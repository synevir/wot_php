<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wold Of Tanks farm table page</title>
</head>

<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366" BACKGROUND = "IMG/background.jpg">

	<table border=0 width=100%>
	<tr>
		<td> The current date is <?php print (date ("D M d H:i:s T Y")); ?>. </td>
		<td align="right">
		<a href="/index.php?&lang=rus"><img src="IMG/rus.gif" border=0 alt="Russian"></a>
		&nbsp;
		<a href="/index.php?&lang=eng"><img src="IMG/eng.gif" border=0 alt="English"></a>
		</td>
	</tr>
	</table>

	<hr />

      Что это за сайт, что за цифры и зачем все это нужно  =>
      <a href="about.htm">  ccылка </a>
  <p>На этой странице представлена таблица доходности всей техники игры
  "World of Tanks". Любителям танковых сражений эта таблица поможет проанализировать различные показатели
  боевых машин и целесообразность преобретения той или иной машины, в зависимости от целей, которые поставил себе
  игрок</p>
  <hr />


<?php

//-------------------------- Main code -------------------------------------
  require_once('connect.php');			// data for connect to MySQL database
  require_once('create_functions.php');		// functions for create forms and tables

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

?>
<!--*********** таблица фильтров колонок//table filter's columns ********** -->

<form name="select_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <input type="submit" value="Применить" name="submit_button"/><br />
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

if (isset($_GET['order_by']) ){
    $order_by = $_GET['order_by'];
    echo '<br />переменная $_GET определена: '.$order_by;
  }

echo '<br /> режим сортировки: '.$order_by;
  generate_table($dbc, $columns, $where, $order_by, $sort, $limit);
  

  mysqli_close($dbc);
?>
      </form>

	<script type="text/javascript" src="./JS/jquery-2.1.3.js"> </script>
	<script type='text/javascript'>
//-------------------------------------------------------------------------------------------------------
//-----------------------	jquery section    ---------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
		// отображение и скрытие check_boxs таблицы выбора колонок для отображения в итоговой таблице
		$(document).ready(function(){
			var flag = true;
		
			$("#btn_spoiler").click(function(){
				// change value label for button "#btn_spoiler"
				if(flag) {
					this.value="Скрыть ↑↑↑↑↑↑↑↑";
					flag = false;
				}
				else {
					this.value="Показать ↓↓↓↓↓↓↓";
					flag = true;
				}
				// animate hide&unhide check_boxs table in element "#div_spoiler"
				return(this.tog = !this.tog) ?  $("#div_spoiler").slideDown(500) : $("#div_spoiler").slideUp(500);
			});
			
	});
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
	</script>


      
      

  </body>
</html>
