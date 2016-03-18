<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Calender Test</title>
	<style>
	table {
		border:1px solid #050;
	}

	.fontb {
		color:white;
		background:blue;
	}
	

	th {
		width:30px;
	}

	td,th {
		height:30px;
		text-align:center;
		
	}
	form {
		margin:0px;
		padding:0px;
	}
</style>

</head>
<body>
	<?php
	require "calendar.class.php";
	echo new Calendar;
?>
</body>
</html>