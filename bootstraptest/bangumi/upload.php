<?php
	/**
	 * file:upload.php
	 * 使用文件上传FileUpload类,处理单个和多个文件
	 */
	require "filesupload.class.php";
	$up = new FileUpload;
	/**
	 * 可以通过set方法设置上传的属性,设置多个属性set方法可以单独调用,也可以连贯操作一起调用多个
	 * $up 	->set('path','./newpath/')
	 * 		->set('size',1000000)
	 * 		->set('allowtype',array('gif','jpg','png'))
	 * 		->set('israndom',false);
	 */
	
	//调用$up对象的upload()方法上传文件,myfile是表单的名称.上传成功返回true,否则为false
	if($up->upload('myfile')){
		print_r($up->getFileName());
	}
	else{
		print_r($up->getErrorMsg());
	}