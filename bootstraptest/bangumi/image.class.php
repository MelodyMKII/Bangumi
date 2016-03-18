<?php
	/**
	 * file:image.class.php 类名为Image,图像处理类,可以完成对各种类型的图像进行缩放\加图片水印和剪裁的操作.
	 */
	class Image{
		private $path;
		/**
		 * 实例图像对象是传递图像的一个路径,默认值是当前目录
		 * @param 	string 	$path 	可以制定处理图片的路径
		 */
		function __construct($path="./"){
			$this->path = rtrim($path,"/")."/";
		} 
	
		/**
		 * 对指定的图像进行缩放
		 * @param 	string 	$name 	是需要处理的图片名称
		 * @param 	int 	$width 	缩放后的宽度
		 * @param 	int 	$height 缩放后的高度
		 * @param 	string 	$qz 	是新图片的前缀
		 * @return 	mixed 			是缩放后的图片名称,失败返回false;
		 */
		function thumb($name,$width,$height,$qz="th_"){
			$imgInfo = $this->getInfo($name);
			$srcImg = $this->getImg($name,$imgInfo);
			$size = $this->getNewSize($name,$width,$height,$imgInfo);
			$newImg = $this->kidOfImage($srcImg,$size,$imgInfo);
			return $this->createNewImage($newImg,$qz.$name,$imgInfo);
		}
		/**
		 * 为图片添加水印
		 * @param 	string 	$groundName 	背景图片,即需要添加水印的图片,暂只支持GIF,JPG,PNG格式
		 * @param 	string 	$waterName 		图片水印,即作为水印的图片,暂只支持GIF,JPG,PNG格式
		 * @param 	int 	$waterPos 		水印位置,有10种状态,0位随机位置;1顶端居左,2顶端居中,3顶端居右......,9底端居右
		 * @param 	string 	$qz 			加水印后的图片的文件名在原文件名前面加上这个前缀
		 * @return 	mixed 					是生成水印后的图片名称,失败返回false
		 */
		function waterHark($groundName,$waterName,$waterPos=0,$qz="wa_"){
			/* 获取水印图片是当前路径,还是制定了路径 */
			$curpath = rtrim($this->path,"/")."/";
			$dir = dirname($waterName);
			if ($dir == ".") {
				$wpath = $curpath;
			}
			else{
				$wpath = $dir."/";
				$waterName = basename($waterName);
			}
			/* 水印图片和北京图片必须都要存在 */
			if(file_exists($curpath.$groundName)&&file_exists($wpath.$waterName)){
				$groundInfo = $this->getInfo($groundName);		//获取背景信息
				$waterInfo =$this->getInfo($waterName,$dir);	//获得水印图片信息
				/*如果背景比水印图片还小,就会被水印全部盖住*/
				if(!$pos =$this->position($groundInfo,$waterInfo,$waterPos)){
					echo '水印不应该比背景图片大!';
					return false;
				}
				$groundImg = $this->getImg($groundName,$groundInfo);
				$waterImg = $this->getImg($waterName,$waterInfo,$dir);
				/* 调用私有方法将水印图像按指定位置复制到背景图片中 */
				$groundImg = $this->createNewImage($groundImg,$qz,$groundName,$groundInfo);
			}
			else{
				echo '图片或水印图片不存在';
				return false;
			}
		}
		/**
		 * 在一个大的背景图片中剪裁出制定区域的图片
		 * @param 	string 	$name 	需要剪切的背景图片
		 * @param  	int 	$x 		剪切起始x
		 * @param  	int 	$y 		剪切起始y
		 * @param  	int 	$width 	图片裁剪的宽度
		 * @param  	int 	$height 图片剪裁的高度
		 * @param  	string 	$qz 	新图片的名称前缀
		 * @return  mixed 			剪裁后的图片名称,失败返回false
		 */
		function cut($name,$x,$y,$width,$height,$qz="cu_"){
			$imgInfo=$this->getInfo($name);
			if((($x+$width)>$imgInfo['width'])||(($y+$height)>$imgInfo['height'])){
				echo "裁剪的位置超过了背景图片范围!";
				return false;
			}
			$back =$this->getImg($name,$imgInfo);
			$cutimg=imagecreatetruecolor($width, $height);
			imagecopyresampled($cuting, $back, 0, 0, $x, $y, $width, $height, $width, $height,$width,$height);
			imagedestroy($back);
			return $this->createNewImage($cuting,$qz.$name,$imgInfo);
		}

		private function position($groundInfo,$waterInfo,$imgInfo){
			if(($groundInfo["width"]<$waterInfo["width"])||($groundInfo["height"]<$waterInfo["height"])){
				return false;
			}
			switch ($waterPos) {
				case 1:
					$posX=0;
					$posY=0;
					break;
				case 2:
					$posX=($groundInfo["width"] - $$waterInfo["width"])/2;
					$posY=0;
					break;
				case 3:
					$posX=($groundInfo["width"]-$waterInfo["width"]);
					$posY=0;
					break;
				case 4:
					$posX=0;
					$posY=($groundInfo["height"] - $$waterInfo["height"])/2;
					break;
				case 5:
					$posX=($groundInfo["width"] - $$waterInfo["width"])/2;
					$posY=($groundInfo["height"] - $$waterInfo["height"])/2;
					break;
				case 6:
					$posX=($groundInfo["width"]-$waterInfo["width"]);
					$posY=($groundInfo["height"] - $$waterInfo["height"])/2;
					break;
				case 7:
					$posX=0;
					$posY=($groundInfo["height"]-$waterInfo["height"]);
					break;
				case 8:
					$posX=($groundInfo["width"] - $$waterInfo["width"])/2;
					$posY=($groundInfo["height"]-$waterInfo["height"]);
					break;
				case 9:
					$posX=($groundInfo["width"]-$waterInfo["width"]);
					$posY=($groundInfo["height"]-$waterInfo["height"]);
					break;				
				default:
					$posX =rand(0,($groundInfo["width"]-$waterInfo["width"]));
					$posY =rand(0,($groundInfo["height"]-$waterInfo["height"]));
					break;
			}
			return  array("posX" => $posX , "posY"=>$posY);
		}
		/*内部使用的私有方法,用于获取图片的属性信息(宽度\高度\类型)*/
		private function imgInfo($name,$path='.'){
			$spath = $path=="."?rtrim($this->path,"/")."/":$path."/";
			$data = getimagesize($spath.$name);
			$imgInfo["width"] = $data[0];
			$imgInfo["height"] = $data[1];
			$imgInfo["type"] = $date[2];
			return $imgInfo;
		}
		
		/*内部使用的私有方法,用于穿件支持各种图片格式(jpg,gif,png)资源*/
		private function getImg($name,$imgInfo,$path='.'){
			$spath = $path=="."?rtrim($this->path,"/")."/":$path."/";
			$srcPic =$spath.$name;
			switch ($imgInfo["type"]) {
				case 1:
					$img = imagecreatefromgif($srcPic);
					break;
				case 3:
					$img = imagecreatefromjpeg($srcPic);
					break;
				case 3:
					$img = imagecreatefrompng($srcPic);
					break;			
				
				default:
					return false;
					break;
			}
		}
		/*内部使用的私有方法,返回等比例缩放的图片宽度和高度,如果原图比缩放后的海啸保持不变*/
		private function getNewSize($name,$width,$height,$imgInfo){
			$size["width"] = $imgInfo["width"];
			$size["height"] = $imgInfo["height"];
			if($width<$imgInfo["width"]){
				$size["width"]=$width;
			}
			if($width<$imgInfo["height"]){
				$size["height"]=$height;
			}
			if($imgInfo["width"]*$size["width"]>$imgInfo["height"]*$size["height"]){
				$size["height"] = round($imgInfo["height"]*$["width"]/$imgInfo["width"]);
			}
			else{
				$size["width"] = round($imgInfo["width"]*$["height"]/$imgInfo["height"]);
			}
			return $size;
		}
		/*内部使用的私有办法,用于保存图像,冰保留原有图片格式*/
		private function createNewImage($newImg,$$newName,$imgInfo){
			$this->path = rtrim($this->path,"/")."/";
			switch ($imgInfo["type"]) {
				case '1':
					$result = imagegif($newImg,$this->path.$newName);
					break;
				case '2':
					$result = imagejpeg($newImg,$this->path.$newName);
					break;
				case '3':	
					$result = imagepng($newImg,$this->path.$newName);
					break;
			}
			imagedestroy($newImg);
			return $newName;
		}
		/*内部使用的私有方法,用于加水印时复制图像*/
		private function copyImage($groundImg,$waterImg,$pos,$waterInfo){
			imagecopy($groundImg, $waterImg, $pos["posX"], $pos["posY"], 0, 0, $waterInfo["width"], $waterInfo["height"]);
			imagedestroy($waterImg);
			return $groundImg;
		}
		/*内部使用的私有办法,处理带有透明度的图片保持原样*/     
		private function kidOfImage($srcImg, $size,	$imgInfo){
			$newImg = imagecreatetruecolor($size["width"], $size["height"]);
			$otsc = imagecolortransparent($srcImg);   //透明色设置为color
			if ($otsc>=0&&$otsc<imagecolorstotal($srcImg)) {
				$transparentcolor = imagecolorsforindex($srcImg, $otsc);   //取透明色色值
				$newtransparentcolor = imagecolorallocate($newImg, $transparentcolor['red'], $transparentcolor['green'], $transparentcolor['blue']);	
				imagefill($newImg,0,0,$newtransparentcolor);
				imagecolortransparent($newImg,$newtransparentcolor);
			}
			imagecopyresized($newim, $srcImg, 0, 0, 0, 0, $size["width"], $size["height"], $imgInfo["width"], $imgInfo["height"]);
			imagedestroy($srcImg);
			return $newImg;
		}
		
	}