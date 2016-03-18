<?php
	namespace app\controllers;
	use app\models\bangumilist;
	class bangumilist{
		private $year;			//当前年份
		private $quarter;		//当前季度
		//private $bgm_total; 	//新番总数
		//private $movie_total;	//剧场版总数
		//private $ovaovdsp_totl;	//ova&ovd&sp总数
		
		/**
		 * 构造函数，创建番组动画列表时，如有则获取具体时间,否则取当前时间
		 * @param string $timestring 时间序列,格式为'Ym'
		 */
		function __construct($timestring){
			if ($timestring) {
				$date = DateTime::createFromFormat ('Ym',$timestring);
			}
			else{
				$date = new DateTime();
			}
			$year = $date->format('Y');
			$month = $date->format('m');
			$quarter = ceil($month/3);
			$this->year = intval($year);
			$this->quarter = intval($quarter);
		}

		/**
		 * 筛选器，判断时间是否属于本季度
		 * @param  string $date    时间
		 * @return mixed          判断在季度内返回$date,否则false
		 */
		function quarter_filter($date){
			$year = $this->year;
			$quarter = $this->quarter;
			$quarter_begin = date('Y-m-d',mktime(0,0,0,$quarter*3-2,1,$year));
			$quarter_end = date('Y-m-d',mktime(0,0,0,$quarter*3+1,0,$year));
			return $date<= $quarter_end && $date >= $quarter_begin ? $date : false;
		}


		function bangumi_query(){
			db::connect();
			$db->commend();

		}
	}