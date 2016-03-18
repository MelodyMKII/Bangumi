<?php
	/*file:image.php 用于输出用户操作表单和验证用户的输入 */
	session_start();
	if(isset($_POST['submit'])){
		/* 判断用户在表单中输入的字符串和验证码图片中的字符串是否相同 */
		if(strtoupper(trim($_POST["code"])) == $_SESSION['code']){
			echo '验证码输入成功<br>';
		}
		else{
			echo '<font color="red">验证码输入错误!!</font><br>';
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv='content-type' charset="text/html;UTF-8" />
	<title>Image</title>
	<script>
		/* 定义一个javasript函数,当单击验证码时被调用,将重新请求并获取一个新的图片 */
		function newgdcode(obj,url){
			/*后面传递一个随机参数,否则在IE7和火狐下,不刷新图片*/
			obj.src = url+'?nowtime'+new Data().getTime();
		}
	</script>
</head>
<body>
	<img src='imgcode.php' alt='看不清楚,换一张' style="cursor:pointer;" onclick="javascript:newgdcode(this,this.src);" />
	<form action="image.php" method="post">
		<input type="text" name="code" size="4" />
		<input type="submit" value="提交" name="submit">
	</form>
</body>
</html>