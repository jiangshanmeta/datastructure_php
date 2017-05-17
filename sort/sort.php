<?php
class Sort{
	static private function _swap(&$arr,$index1,$index2){
		if($index1!==$index2){
			$temp = $arr[$index1];
			$arr[$index1] = $arr[$index2];
			$arr[$index2] = $temp;            
		}
	}

	static public function selectSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$len = count($arr);
		for($i=0;$i<$len;$i++){
			$item = $arr[$i];
			$index = $i;
			for($j=$i+1;$j<$len;$j++){
				if($arr[$j]<$item){
					$index = $j;
					$item = $arr[$j];
				}
			}
			self::_swap($arr,$i,$index);
		}
		return true;
	}

	static public function bubbleSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$len = count($arr);
		for($i=0;$i<$len;$i++){
			$end = $len - $i;
			$flag = false;
			for($j=1;$j<$end;$j++){
				if($arr[$j-1]>$arr[$j]){
					self::_swap($arr,$j-1,$j);
					$flag = true;
				}
			}
			if(!$flag){
				break;
			}
		}
		return true;
	}

	static public function insertSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$len = count($arr);
		for($i=1;$i<$len;$i++){
			$item = $arr[$i];
			for($j=$i;$j>0;$j--){
				if($arr[$j-1]>$item){
					$arr[$j] = $arr[$j-1];
				}else{
					break;
				}
			}
			$arr[$j] = $item;
		}
		return true;
	}

	static public function shellSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$len = count($arr);
		for($skip=floor($len/2);$skip>0;$skip=floor($skip/2)){
			for($i=$skip;$i<$len;$i++){
				$item = $arr[$i];
				for($j=$i;$j>=$skip;$j-=$skip){
					if($arr[$j-$skip]>$item){
						$arr[$j] = $arr[$j-$skip];
					}else{
						break;
					}
				}
				$arr[$j] = $item;
			}
		}
		return true;
	}

	static private function _parentIndex($num){
		return ceil($num/2) - 1;
	}

	// 调整成最大堆
	static private function _perceDown(&$arr,$from,$to){
		$temp = $arr[$from];
		for($parent=$from;2*$parent+1<=$to;$parent=$child){
			$child = 2*$parent + 1;
			if($child+1<=$to && $arr[$child+1]>$arr[$child]){
				$child++;
			}
			if($arr[$child]>$temp){
				$arr[$parent] = $arr[$child];
			}else{
				break;
			}
		}
		$arr[$parent] = $temp;
	}

	static public function heapSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$len = count($arr);
		// 调整原始数据成为最大堆
		for($i=self::_parentIndex($len-1);$i>=0;$i--){
			self::_perceDown($arr,$i,$len-1);
		}

		for($i=$len-1;$i>0;$i--){
			// 未排序序列中选出最大的
			self::_swap($arr,0,$i);

			// 重新调整为最大堆
			self::_perceDown($arr,0,$i-1);
		}
		return true;
	}

	static private function _merge(&$arr,$start,$mid,$end){
		if($mid<=$start || $end<=$mid){
			return;
		}
		$temp = [];
		$len1 = $mid - $start;
		$len2 = $end - $mid;
		$index1 = 0;
		$index2 = 0;
		while ($index1<$len1 && $index2<$len2) {
			if($arr[$start+$index1]<=$arr[$mid+$index2]){
				$temp[] = $arr[$start+$index1];
				$index1++;

			}else{
				$temp[] = $arr[$mid+$index2];
				$index2++;
			}
		}
		while ($index1<$len1) {
			$temp[] = $arr[$start+$index1];
			$index1++;
		}
		while ($index2<$len2) {
			$temp[] = $arr[$mid+$index2];
			$index2++;
		}
		$len = count($temp);
		for($i=0;$i<$len;$i++){
			$arr[$start+$i] = $temp[$i];
		}		

	}

	static public function mergeSort(&$arr,$from=0,$to=NULL){
		if(!is_array($arr)){
			return false;
		}
		if($to===NULL){
			$to = count($arr) - 1;
		}
		$len = $to - $from;
		if($len<1){
			return false;
		}
		$mid = floor(($to+$from)/2);
		self::mergeSort($arr,$from,$mid);
		self::mergeSort($arr,$mid+1,$to);

		self::_merge($arr,$from,$mid+1,$to+1);
		return true;
	}

	static public function quickSort(&$arr,$from=0,$to=NULL){
		if(!is_array($arr)){
			return false;
		}
		if($to===NULL){
			$to = count($arr) - 1;
		}
		if($to-$from<1){
			return false;
		}
		$mid = floor(($from+$to)/2);
		// 下面三个if判断保证$from、$mid、$to对应元素递增
		if($arr[$from]>$arr[$mid]){
			self::_swap($arr,$from,$mid);
		}
		if($arr[$from]>$arr[$to]){
			self::_swap($arr,$from,$to);
		}
		if($arr[$mid]>$arr[$to]){
			self::_swap($arr,$mid,$to);
		}
		// $to对应的元素比$mid大，把$mid对应元素交换到$to-1
		self::_swap($arr,$mid,$to-1);

		$pivot = $arr[$to-1];
		$low = $from;
		$high = $to - 1;
		while(true){
			while($low<$to-1&&$arr[++$low]<$pivot){

			}
			while($high>$from&&$arr[--$high]>$pivot){

			}
			if($low<$high){
				self::_swap($arr,$low,$high);
			}else{
				break;
			}
		}

		self::_swap($arr,$low,$to-1);
		// 到现在以$low为界已经分成了两部分，递归处理
		self::quickSort($arr,$from,$low-1);
		self::quickSort($arr,$low+1,$to);
	}

	static private function _getDigit($num,$digit=1){
		for($i=0;$i<$digit;$i++){
			$rst = $num%10;
			$num = floor($num/10);
		}
		return $rst;
	}

	static private function _getNumLen($num){
		return strlen((string)$num);
	}

	static private function _getBucket(){
		return [
			0=>[],
			1=>[],
			2=>[],
			3=>[],
			4=>[],
			5=>[],
			6=>[],
			7=>[],
			8=>[],
			9=>[],
		];
	}

	static public function LSDRadixSort(&$arr){
		if(!is_array($arr)){
			return false;
		}
		$maxLen = 1;
		$bucket = self::_getBucket();
		for($i=0;$i<$maxLen;$i++){
			if($i>0){
				$newBucket = self::_getBucket();
				$bucketLen = count($bucket);
				for($j=0;$j<$bucketLen;$j++){
					$bucketArr = $bucket[$j];
					$bucketArrLen = count($bucketArr);
					for($k=0;$k<$bucketArrLen;$k++){
						$item = $bucketArr[$k];
						$bucketId = self::_getDigit($item,$i+1);
						$newBucket[$bucketId][] = $item;
					}
				}
				$bucket = $newBucket;
			}else{
				$arrLen = count($arr);
				for($j=0;$j<$arrLen;$j++){
					$item = $arr[$j];
					$itemLength = self::_getNumLen($item);
					if($itemLength>$maxLen){
						$maxLen = $itemLength;
					}
					$bucketId = self::_getDigit($item,1);
					$bucket[$bucketId][] = $item;
				}
			}
		}
		$bucketLen = count($bucket);
		$index = 0;
		for($i=0;$i<$bucketLen;$i++){
			$bucketArr = $bucket[$i];
			$bucketArrLen = count($bucketArr);
			for($j=0;$j<$bucketArrLen;$j++){
				$arr[$index] = $bucketArr[$j];
				$index++;
			}
		}
		return true;
	}


}
?>