<?php
	/**
	 * file:imgcode.php 用于请求时,通过验证码的对象想客户端输出图片
	 */
	session_start();
	require_once('vcode.class.php');
	echo new Vcode();