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
			for($j=1;$j<$end;$j++){
				if($arr[$j-1]>$arr[$j]){
					self::_swap($arr,$j-1,$j);
				}
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

	static public function mergeSort(&$arr){

	}

	static public function quickSort(&$arr){

	}

	static public function LSDRadixSort(&$arr){

	}




}

$test = [999,3,5,27,51,1,79,234,689,33,2];
// Sort::selectSort($test);
// Sort::bubbleSort($test);
// Sort::insertSort($test);
// Sort::shellSort($test);
Sort::heapSort($test);
var_dump($test);
?>