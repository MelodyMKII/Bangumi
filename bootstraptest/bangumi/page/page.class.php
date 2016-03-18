<?php
	/**
	 * file:page.class.php
	 * 分页类
	 */
	class page{
		private $total;
		private $listRows;
		private $limit;
		private $uri;
		private $pageNum;
		private $page;
		private $config = array(
			'head' => "条记录",
			'prev' => "上一页",
			'next' => "下一页",
			'first' => "首页",
			'last' => "末页"
			);
		private $listNum = 10;

		/**
		 * 构造方法
		 * @param int 	$total 		计算分页总记录数
		 * @param int 	$listRows 	可选,每页记录数,default = 25
		 * @param mixed $query 		可选,向目标页面传递参数,可以是数组,也可以是查询字符串格式
		 * @param bool 	$ord 		可选,默认为true,页面从第一页开始显示,falss为最后一页
		 */
		public function __construct($total,$listRows=25,$query="",$ord=true){
			$this->total = $total;
			$this->listRows = $listRows;
			$this->uri = $this->getUri($query);
			$this->pageNum = ceil($this->total/$this->listRows);
			/*以下判断用来设置当前面*/
			if(!empty($_GET["page"])){
				$page = $_GET["page"];
			}
			else{
				if($ord)
					$page = 1;
				else
					$page = $this->pageNum;
			}
			if($total>0){
				if (preg_match('/\D/', $page)) {
					$this->page = 1;
				}
				else{
					$this->page = $page;
				}
			}
			$this->limit = 'LIMIT'.$this->setLimit();
		}

		/**
		 * 用于设置显示分页的信息,可以进行连贯操作
		 * @param 	string 	$param 	是成员属性数组config的下标
		 * @param 	string 	$value 	用于设置config下标对应的元素值
		 * @return 	object 			返回本对象自己$this,用于连贯操作
		 */
		function set($param,$value){
			if(array_key_exists($param, $this->config)){
				$this->config[$param] = $value;
			}
			return $this;
		}
		/*外部调用私有属性的方法*/
		function __get($args){
			if($args == "limit"||$args =="page")
				return $this->$args;
			else
				return null;
		}
		/**
		 * 按指定格式输出分页
		 * @param int 0-7的数字分别作为参数,用于自定义输出的分页结构和调整结构的顺序,默认输出全部结构
		 * @return string 分页信息内容
		 */
		function fpage(){
			$arr = func_get_args();
			$html[0]="&nbsp;共<b>{$this->total}</b>{$this->config["head"]}&nbsp;";
			$html[1]="&nbsp;本页<b>.$this->disnum().</b>条&nbsp;";
			$html[2]="&nbsp;本页从<b>{$this->start()}-{$this->end()}</b>条&nbsp;";
			$html[3]="&nbsp;<b>{$this->page}/{$this->pageNum}</b>页&nbsp;";
			$html[4]=$this->firstprev();
			$html[5]=$this->pageList();
			$html[6]=$this->nextlast();
			$html[7]=$this->goPage();
			$fpage='<div style="font:12px \'\5B8B\4F53\',san-serif;">';
			if(count($arr)<1)
				$arr = array(0,1,2,3,4,5,6,7);
			for($i=0;$i<count($arr);$i++)
				$fpage .= $html[$arr[$i]];
			$fpage .= '</div>';
			return $fpage;
		}
		/*在对象内部使用的私有方法*/
		private function setLimit(){
			if ($this->page>0) 
				return ($this->page-1)*$this->listRows.",($this->listRows)";
			else
				return 0;
		}
		/*内部私有方法,用于自动获取访问的当前URL*/
		private function getUri($query){
			$request_uri = $_SERVER["REQUEST_URI"];
			$url = strstr($request_uri, '?')?$request_uri:$request_uri.'?';
			if(is_array($query))											
				$url .= http_build_query($query);
			else if($query !="")
				$url .="&".trim($query,"?&");
			$arr = parse_url($url);					
			if(isset($arr["query"])){
				parse_str($arr["query"],$arrs);
				unset($arrs["page"]);
				$url = $arr["path"].'?'.http_build_query($arrs);
			}
			if(strstr($url,'?')){
				if(substr($url, -1)!='?')
					$url = $url.'&';
			}
			else{
				$url = $url.'?';
			}
			return $url;
		}
		/*内部私有方法,用于获取当前页开始的记录数*/
		private function start(){
			if ($this->total == 0) 
				return 0;
			else
				return ($this->page-1)*$this->listRows+1;
		}
		/*内部私有方法,用于获取当前页结束的记录数*/
		private function end(){
			return min($this->page*$this->listRows,$this->total);
		}
		/*内部私有办法,用于获取上一页和首页的操作信息*/
		private function firstprev(){
			if($this->page>1){
				$str ="&nbsp;<a href='{$this->uri}page=1'>{$this->config["first"]}</a>&nbsp;";
				$str .= "<a href='{$this->uri}page=".($this->page-1)."'>{$this->config["prev"]}</a>&nbsp;";
				return $str;
			}
		}
		/*内部私有方法,用于获取页数列表信息*/
		private function pageList(){
			$linkPage ="&nbsp;<b>";
			$inum = floor($this->listNum/2);
			/*当前页前面的列表*/
			for($i=$inum;$i>=1;$i--){
				$page = $this->page-$i;
				if($page>=1)
					$linkPage .="<a href='{$this->uri}page={$page}'>{$page}</a>&nbsp;";
			}
			/*当前页的信息*/
			if($this->pageNum>1)
				$linkPage .="<span style='padding:1px 2px;background:#BBB;color:white'>{$this->page}</span>&nbsp;";
			/*当前页后面的列表*/
			for($i=1;$i<=$inum;$i++){
				$page =$this->page+$i;
				if($page<=$this->pageNum)
					$linkPage .= "<a href='{$this->uri}page={$page}'>{$page}</a>&nbsp;";
				else
					break;
			}
			$linkPage .='</b>';
			return $linkPage;
		}

		/*内部私有方法,用于获取上一页和首页的操作信息*/
		private function nextlast(){
			if($this->page!=$this->pageNum){
				$str ="&nbsp;<a href='{$this->uri}page=".($this->page+1)."'>{$this->config["next"]}</a>&nbsp;";
				$str .="&nbsp;<a href='{$this->uri}page=".($this->pageNum)."'>{$this->config["last"]}</a>&nbsp;";
				return $str;
			}
		}
		/*内部私有方法,用于显示和处理表单跳转页面*/
		private function goPage(){
			if($this->pageNum>1){
				return '&nbsp;<input style="width:20px;height:17px !important;height:18px;border:1px solid #CCCCCC;" type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'page=\'+page+\'\'}" value="'.$this->page.'"><input style="cursor:pointer;width:25px;height:18px;border:1px solid #CCCCCC;" type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'page=\'+page+\'\'">&nbsp;';
			}
		}
		/* 内部私有方法，用于获取本页显示的记录条数 */
		private function disnum(){
			if($this->total > 0){
				return $this->end()-$this->start()+1;
			}else{
				return 0;
			}
		}

	}