<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body BGCOLOR="#E6E8fA" text="#000002" link="#666699" vlink="#666699" alink = "#333366" BACKGROUND = "background.jpg">


<?php

   function generate_table($dbc,$columns,$where='', $order_by, $sort, $limit=4){

      $query = "SELECT $columns FROM t1 $where  ORDER BY $order_by $sort LIMIT $limit";
      if (!$data = mysqli_query($dbc, $query))
	    echo 'query error - '.$query;
      $column = explode(",",$columns);

      echo "<table border=1 class=\"align_table\">\n";
// first row of table
      echo "  <tr>\n";
      foreach($column as $i) { 

// variant with javascript
		echo "	<td>
		<div class=\"first_row\">
				<a id=\"$i\" href=\"javascript:void(0)\" onclick=\"document.forms.select_form.submit();
					return false;\"> $i
				</a>
		</div>
	</td>\n";
       } 
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
//		else									//
//		   if ( $i == 'profit_battle' OR $i == 'profit_battle_premium' )	//
//			echo '<td align="center" bgcolor="mediumaquamarine">'. 		//
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
		$str = $str."<option selected value=\"$val\">$val</option>\n";
	    else 
		$str = $str."<option value=\"$val\">$val</option>\n";
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

	$string_result = "<table border=1>\n<tr>\n   <td nowrap>\n";
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


?>
  </body>
</html>
