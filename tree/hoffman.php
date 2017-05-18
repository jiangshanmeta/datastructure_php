<?php
class Hoffmann_tree{
	function __construct(){
		
	}

	function genTree($arr){
		$minHeap = new MinHeap();
		$minHeap->compareFunc = function($data){
			return $data['weight'];
		};
		foreach ($arr as $value) {
			$minHeap->insert($value);
		}
		$size = count($arr);
		for($i=1;$i<$size;$i++){
			$node = [];
			$node['left'] = $minHeap->delete();
			$node['right'] = $minHeap->delete();
			$node['weight'] = $node['left']['weight'] + $node['right']['weight'];
			$minHeap->insert($node);
		}
		return $minHeap->delete();
	}
}
?>