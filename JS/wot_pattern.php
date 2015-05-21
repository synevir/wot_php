<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Внедрение DOM в HTML-документ</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="style.css">
<!--   <script type="text/javascript" src="common.js"></script> -->

  <script type="text/javascript">


	function SetOrderCookie(){
		var date = new Date( new Date().getTime() + 60*1000 );
		document.cookie="name=value; path=/; expires="+date.toUTCString();

// 		document.cookie="order_value=by_xp";
// 		document.cookie = "userName=Vasya";
		alert('document.cookie=' + document.cookie);
	}


// 	SetOrderValue()
  </script>

</head>
<body>
	<a href="wot_pattern.html" onclick="{alert('link')}">
		LINK FOR EXPERIENS </a> 
	<hr />

	<input  type="button" value="Set Cookie" onclick="SetOrderCookie()" />
	<input  type="button" value="Order by" onclick="SetOrderValue()" />

</body>
</html>
