<?php
	namespace app\libary\db;
	/**
	 *数据库驱动类，通过使用PHP的pdo扩展连接处理数据库
	 */
	class db{
		static $mysqli_link = null;
		/*
		 *用于获取数据库连接mysqli对象,如果已经存在mysqli对象就不在调用connect()去连接
		 */
		static function connect(){
			if (is_null(self::$mysqli_link)) {
				$link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				if (mysqli_connect_errno()) {
					printf("连接失败: %s<br>", mysqli_connect_error());	//DEBUG类完成后需修改
					return false;
				}
				else{
					self::$mysqli_link = $link;
					return $link;
				}
			}
			else{
				return self::$mysqli_link;
			}
		}

		/**
		 * 直接实用sql语句查询，并返回结果数组
		 * @param  string $sql  sql语句
		 * @param  array  $data 既定数组结构
		 * @return array       结果数组
		 */
		function query_sql($sql,$data=array()){

		}
		/**
		 * 获取数据库版本
		 * @return string 返回数据库版本信息
		 */
		function dbversion() {
			$mysqli=self::connect();
			return $mysqli->server_info;
		}
	}