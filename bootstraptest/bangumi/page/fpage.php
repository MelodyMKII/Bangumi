<title>分页类Page使用演示 构造方法的其他参数应用</title>
<?php
	include "page.class.php";
	/* 
		通过第二个参数设置每页显示10条数据
		通过第三个参数设置跳转页面传递两个参数过去，也可以使用数组array("cid"=>5,"search"=>"php")
		通过第四个参数设置默认显示最后一页
	*/
	$page = new Page(1000, 10, 'cid=5&search=php', false);

	$sql = "select * from article {$page->limit}";
	echo 'SQL = "'.$sql.'"<p>';        
	
	echo $page->fpage();      
	
	
	
	
