<?php
	$DB_NAME = 'bangumi';
	$DB_USER = 'root';
	$DB_PASSWORD = 'hjhbest1990';
	$DB_HOST = 'localhost';

	$link = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD);
	if (mysqli_connect_errno()) {
			printf("连接失败: %s<br>", mysqli_connect_error());
		exit();
	}
 	$link->select_db($DB_NAME);
 	echo $link->get_server_info();
 	$link->query("set names utf8");
 	$result = $link->query("select * from cr_anime");
 	echo '<table width="90%" border="1" align="center">'; 
 	echo '<caption><h1>新番动画</h1></caption>'; 
 	echo '<th>动画名</th><th>动画中文名</th><th>集数</th><th>播放星期</th><th>类型</th>'; 
 	while($rowObj=$result->fetch_object()){ 
		echo '<tr align="center">'; 
		echo '<td>'.$rowObj->anime_name.'</td>'; 
		echo '<td>'.$rowObj->anime_name_chinese.'</td>';
		echo '<td>'.$rowObj->anime_serises.'</td>';
		echo '<td>'.$rowObj->anime_week.'</td>';
		echo '<td>'.$rowObj->anime_type.'</td>';
		echo '</tr>';
	}
	echo'</talbe>';
	$result->close();
	$link->close();
?>