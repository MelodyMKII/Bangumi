<?php
	class Calendar{
		private $year;			//当前的年
		private $month;			//当前的月
		private $start_weekday;	//当月的第一天对应的是周几,作为当月开始遍历日期的开始
		private $days;			//当前月总天数
		/*构造方法 用来初始化一些日期属性*/
		function __construct(){
			$this->year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
			$this->month = isset($_GET["month"]) ? $_GET["month"] : date("m");
			$this->days = date("t",mktime(0,0,0,$this->month,1,$this->year));
			$this->start_weekday = date("w",mktime(0,0,0,$this->month,1,$this->year));
			}
		/*魔术方式用于打印整个日历*/
		function __toString(){
			$out .= '<table align="center">';
			$out .= $this->chageDate();
			$out .= $this->weekList();
			$out .= $this->daysList();
			$out .= '</table>';
			return $out;
			}
		/*内部调用的私有方法,用于输出周列表*/
		private function weekList(){
			$week = array('日','一','二','三','四','五','六' );
			$out .='<tr>';
			for($i = 0;$i < count($week);$i++)
				$out .='<th class ="fontb">'.$week[$i].'</th>';
			$out .= '</tr>';
			return $out;
		}
		/*内部调用的私有方法,用于输出日列表*/
		private function daysList(){
			$out .='<tr>';
			for($j = 0;$j < $this->start_weekday; $j++)
				$out .='<td>&nbsp;</td>';
			/* 将当月的所有日期循环遍历出来,如果是当前日期,为其设置深色背景 */
			for($k = 1;$k <= $this->days;$k++){
				$j++;
				if($k == date('d'))
					$out .='<td class="fontb">'.$k.'</td>';
				else
					$out .='<td>'.$k.'</td>';
				if($j%7 == 0)
					$out .= '</tr><tr>';
			}
			while ($j%7 !==0) {
				$out .= '<td>&nbsp;</td>';
				$j++;
			}
			$out .='</tr>';
			return $out;
		}
		/* 下列用语处理当前年月份的上下一年或上下一月的数据 */
		private function prevYear($year,$month){
			$year = $year-1;
			if($year < 1970)
				$year = 1970;
			return "year={$year}&month={$month}";
		}
		private function prevMonth($year,$month){
			if ($month == 1) {
				$year = $year -1;
				if($year < 1970)
					$year = 1970;
				$month =12;
			}
			else{
				$month--;
			}
			return "year={$year}&month={$month}";
		}
		private function nextYear($year,$month){
			$year = $year +1;
			if($year > 2038)
				$year = 2038;
			return "year={$year}&month={$month}";
		}
		private function nextMonth($year,$month){
			if($month == 12){
				$year = $year +1;
				if($year > 2038)
					$year = 2038;
				$month =1;
			}
			else{
				$month++;
			}
			return "year={$year}&month={$month}";
		}
		/*用户操作区调整年份和月份的设置*/
		private function chageDate($url ='index.php'){
			$out .= '<tr>';
			$out .= '<td><a href="'.$url.'?'.$this->prevYear($this->year,$this->month).'">'.'<<'.'</a></td>';
			$out .= '<td><a href="'.$url.'?'.$this->prevMonth($this->year,$this->month).'">'.'<'.'</a></td>';
			$out .= '<td colspan="3">';
			$out .= '<form>';
			$out .= '<select name ="year" onchange="window.location=\''.$url.'?year=\'+this.options[selectedIndex].value+\'&month='.$this->month.'\'">';						//传递取单赋值于year变量
			for($sy=1970;$sy <= 2038;$sy++){
				$selected = ($sy==$this->year) ? "selected" : "";
				$out .='<option '.$selected.' value ="'.$sy.'">'.$sy.'</option>';							//生成年份取单
			}
			$out .='</select>';

			$out .= '<select name ="month" onchange="window.location=\''.$url.'?year='.$this->year.'&month=\'+this.options[selectedIndex].value">';							//传递取单赋值与month变量

			for($sm=1;$sm <= 12;$sm++){
				$selected1 = ($sm==$this->month) ? "selected" : "";
				$out .='<option '.$selected1.' value ="'.$sm.'">'.$sm.'</option>';			
			}																								//生成月份取单						
			$out .= '</select>';
			$out .= '</form>';
			$out .= '</td>';

			$out .= '<td><a href="'.$url.'?'.$this->nextMonth($this->year,$this->month).'">'.'>'.'</a></td>';
			$out .= '<td><a href="'.$url.'?'.$this->nextYear($this->year,$this->month).'">'.'>>'.'</a></td>';
			$out .='</tr>';
			return $out;
		}
	}
