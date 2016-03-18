<?php
	/* 第一步：必须包含分页类所在的文件page.class.php */
	include "page.class.php";
	/* 第二步：实例化分页类对象，并通过参数传递数据表的记录总数 （总记录数需要从数据库查询获取）*/
	$page = new Page(1000);
	
	/* 第三步：通过对象中limit属性，获取LIMIT从句并组合SQL语句，从数据表aritcle中获取当页的数据记录 */
	$sql = "select * from article {$page->limit}";
	echo 'SQL = "'.$sql.'"<p>';         //输出SQL语句
	
	/* 第四步：通过分页对象中的fpage()方法，输出所有分页的结构信息 */
	echo $page->fpage();      
	