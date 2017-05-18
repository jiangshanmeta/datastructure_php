<?php
class Heap{
	static protected function _parentIndex($num){
		return floor($num/2);
	}


	public $compareFunc;
	public $elements = [];
	public $size = 0; 
	public function __construct(){
		$this->elements[0] = NULL;
		$this->compareFunc = function($data){
			return $data;
		};
	}
	public function isEmpty(){
		return $this->size === 0;
	}

	public function insert($data){
		$this->size++;
		$this->elements[$this->size] = $data;
		for($i=$this->size;$i>0;$i=$parentIndex){
			$parentIndex = self::_parentIndex($i);
			if($parentIndex>0){
				$cls = get_class($this);
				$rst = $cls::_perceDown($this->elements,$parentIndex,$this->size,$this->compareFunc);
				if(!$rst){
					break;
				}
			}
		}
	}

	public function delete(){
		if($this->isEmpty()){
			return NULL;
		}
		$data = $this->elements[1];
		// 调整剩余元素
		$this->elements[1] = $this->elements[$this->size];
		$cls = get_class($this);
		$cls::_perceDown($this->elements,1,$this->size-1,$this->compareFunc);
		unset($this->elements[$this->size]);
		$this->size--;
		return $data;
	}


}
class MaxHeap extends Heap{
	static public function _perceDown(&$arr,$from,$to,$fn){
		$temp = $fn($arr[$from]);
		$rootItem = $arr[$from];
		for($parent=$from;2*$parent<=$to;$parent=$child){
			$child = 2*$parent;
			if($child<$to&&$fn($arr[$child])<$fn($arr[$child+1]) ){
				$child++;
			}
			if($temp<$fn($arr[$child]) ){
				$arr[$parent] = $arr[$child];
			}else{
				break;
			}
		}
		if($parent===$from){
			return false;
		}else{
			$arr[$parent] = $rootItem;
			return true;
		}
		
	}


	static public function arrayToMaxHeap($arr,$compareFunc=NULL){
		$maxHeap = new MaxHeap();
		if($compareFunc===NULL){
			$maxHeap->compareFunc = function($data){
				return $data;
			};
		}else{
			$maxHeap->compareFunc = $compareFunc;
		}
		if(!is_array($arr)){
			return $maxHeap;
		}
		$maxHeap->size = count($arr);
		array_unshift($arr,NULL);
		if(!is_array($arr)){
			return $maxHeap;
		}
		// 调整arr成为一个堆
		for($i=$maxHeap->size;$i>0;$i--){
			$parentIndex = self::_parentIndex($i);
			if($parentIndex>0){
				self::_perceDown($arr,$parentIndex,$maxHeap->size,$maxHeap->compareFunc);
			}
		}

		$maxHeap->elements = $arr;

		return $maxHeap;
	}
	function __construct(){
		parent::__construct();
	}

}
class MinHeap extends Heap{
	// $from $to 均指在数组中的索引
	static public function _perceDown(&$arr,$from,$to,$fn){
		$temp = $fn($arr[$from]);
		$rootItem = $arr[$from];
		// var_dump($temp,$from,$to);
		for($parent=$from;2*$parent<=$to;$parent=$child){
			$child = 2*$parent;
			if($child<$to&&$fn($arr[$child])>$fn($arr[$child+1]) ){
				$child++;
			}
			if($temp>$fn($arr[$child]) ){
				$arr[$parent] = $arr[$child];
			}else{
				break;
			}
		}
		if($parent===$from){
			return false;
		}else{
			$arr[$parent] = $rootItem;
			return true;
		}
		
	}

	static public function arrayToMinHeap($arr,$compareFunc=NULL){
		$minHeap = new MinHeap();
		if($compareFunc===NULL){
			$minHeap->compareFunc = function($data){
				return $data;
			};
		}else{
			$minHeap->compareFunc = $compareFunc;
		}
		if(!is_array($arr)){
			return $minHeap;
		}
		$minHeap->size = count($arr);
		array_unshift($arr,NULL);
		

		// 调整arr成为一个堆
		for($i=$minHeap->size;$i>0;$i--){
			$parentIndex = self::_parentIndex($i);
			if($parentIndex>0){
				self::_perceDown($arr,$parentIndex,$minHeap->size,$minHeap->compareFunc);
			}
		}

		$minHeap->elements = $arr;

		return $minHeap;
	}

	function __construct(){
		parent::__construct();
	}


}
?>