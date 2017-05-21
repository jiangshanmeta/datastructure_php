<?php
class Graph_node{
	protected $index;
	protected $data;
	function __construct($data=NULL){
		$this->set_data($data);
	}

	function get_index(){
		return $this->index;
	}
	function set_index($index){
		$this->index = $index;
	}
	function get_data(){
		return $this->data;
	}
	function set_data($data){
		$this->data = $data;
	}
}

class Graph{
	protected $_nodes = [];
	protected $_edges = [];
	function __construct(){

	}

	function insert($node){
		if(!($node instanceof Graph_node)){
			$node = new Graph_node($node);
		}
		$index = $this->length();
		$node->set_index($index);
		$this->insert_indexed_node($node);
	}

	function length(){
		return count($this->_nodes);
	}

	function insert_indexed_node($node){
		$index = $node->get_index();
		$this->_nodes[$index] = $node;
	}

	function set_edge($from,$to,$weight){
		if(!isset($this->_edges[$from])){
			$this->_edges[$from] = [];
		}
		$this->_edges[$from][$to] = $weight;
	}

	function get_node_by_index($index){
		return $this->_nodes[$index];
	}

	protected function _visit_stack($fn,&$stack,&$visitedHash){
		while(!empty($stack)){
			$node = array_pop($stack);
			$index = $node->get_index();
			if(is_callable($fn,true)){
				$fn($node->get_data());
			}
			if(isset($this->_edges[$index])){
				foreach ($this->_edges[$index] as $key => $value) {
					if(!isset($visitedHash[$key])){
						$visitedHash[$key] = $key;
						array_push($stack,$this->get_node_by_index($key));
					}
				}
			}
		}
	}

	function dfs($fn,$node_index=0){
		$stack = [];
		$visitedHash = [];
		$node = $this->get_node_by_index($node_index);
		$visitedHash[$node_index] = $node_index;
		array_push($stack,$node);
		$this->_visit_stack($fn,$stack,$visitedHash);

		// 有的节点没有和初始节点直接或间接相连
		$graph_length = $this->length();
		while(count($visitedHash)<$graph_length){
			for($i=0;$i<$graph_length;$i++){
				if(isset($visitedHash[$i])){
					continue;
				}
				$visitedHash[$i] = $i;
				array_push($stack,$this->get_node_by_index($i));
				$this->_visit_stack($fn,$stack,$visitedHash);
			}
		}

	}

	protected function _visit_queue($fn,&$queue,&$visitedHash){
		while(!empty($queue)){
			$node = array_shift($queue);
			$index = $node->get_index();
			if(is_callable($fn,true)){
				$fn($node->get_data());
			}
			if(isset($this->_edges[$index])){
				foreach ($this->_edges[$index] as $key => $value) {
					if(!isset($visitedHash[$key])){
						$visitedHash[$key] = $key;
						array_push($queue,$this->get_node_by_index($key));
					}
				}
			}
		}
	}

	function bfs($fn,$node_index=0){
		$queue = [];
		$visitedHash = [];
		$node = $this->get_node_by_index($node_index);
		$visitedHash[$node_index] = $node_index;
		array_push($queue,$node);
		$this->_visit_stack($fn,$queue,$visitedHash);

		// 有的节点没有和初始节点直接或间接相连
		$graph_length = $this->length();
		while(count($visitedHash)<$graph_length){
			for($i=0;$i<$graph_length;$i++){
				if(isset($visitedHash[$i])){
					continue;
				}
				$visitedHash[$i] = $i;
				array_push($queue,$this->get_node_by_index($i));
				$this->_visit_queue($fn,$queue,$visitedHash);
			}
		}
	}

	function shortpath_unweighted($from=0){
		$dist = [];
		$path = [];
		$length = $this->length();
		for($i=0;$i<$length;$i++){
			$dist[$i] = -1;
			$path[$i] = -1;
		}
		$dist[$from] = 0;

		$queue = [];
		$node = $this->get_node_by_index($from);
		array_push($queue,$node);
		while (!empty($queue)) {
			$node = array_shift($queue);
			$index = $node->get_index();
			if(!isset($this->_edges[$index])){
				continue;
			}
			foreach ($this->_edges[$index] as $key => $value) {
				if($dist[$key]===-1){
					$dist[$key] = $dist[$index] + 1;
					$path[$key] = $index;
					array_push($queue,$this->get_node_by_index($key));
				}
			}
		}
		return [
			'dist'=>$dist,
			'path'=>$path,
		];
	}

	function shortpath_weighted($from=0){
		$dist = [];
		$path = [];
		$collected = [];
		$length = $this->length();
		for($i=0;$i<$length;$i++){
			if(isset($this->_edges[$from][$i])){
				$dist[$i] = $this->_edges[$from][$i];	
				$path[$i] = $from;
			}else{
				$dist[$i] = INF;
				$path[$i] = -1;
			}
			$collected[$i] = false;
		}
		$dist[$from] = 0;
		$collected[$from] = true;


		while(true){
			$index = $this->_find_min_index_by_dist($dist,$collected);
			if($index===-1){
				break;
			}
			$collected[$index] = true;
			if(!isset($this->_edges[$index])){
				continue;
			}
			foreach ($this->_edges[$index] as $key => $value) {
				if($collected[$key]){
					continue;
				}
				if($dist[$index]+$this->_edges[$index][$key]<$dist[$key]){
					$dist[$key] = $dist[$index] + $this->_edges[$index][$key];
					$path[$key] = $index;
				}

			}
		}

		return [
			'dist'=>$dist,
			'path'=>$path,
		];
	}

	private function _find_min_index_by_dist(&$dist,&$collected){
		$minIndex = -1;
		$minDist = INF;
		$length = $this->length();
		for($i=0;$i<$length;$i++){
			if(!$collected[$i]&&$dist[$i]<$minDist){
				$minIndex = $i;
				$minDist = $dist[$i];
			}
		}
		return $minIndex;
	}


	function shortpath_multisource(){
		$dist = [];
		$path = [];
		$length = $this->length();
		// 初始化距离，到自己距离为0，没直接相连距离为INF
		for($i=0;$i<$length;$i++){
			$edges = isset($this->_edges[$i])?$this->_edges[$i]:[];
			for($j=0;$j<$length;$j++){
				if($i===$j){
					$dist[$i][$j] = 0;
				}else if(isset($edges[$j])){
					$dist[$i][$j] = $edges[$j];
				}else{
					$dist[$i][$j] = INF;
				}
				$path[$i][$j] = -1;
			}
		}

		for($k=0;$k<$length;$k++){
			for($i=0;$i<$length;$i++){
				for($j=0;$j<$length;$j++){
					if($dist[$i][$k]+$dist[$k][$j]<$dist[$i][$j]){
						$dist[$i][$j] = $dist[$i][$k] + $dist[$k][$j];
						$path[$i][$j] = $k;
					}
				}
			}
		}

		return [
			'dist'=>$dist,
			'path'=>$path
		];

	}




}

class Graph_undirected extends Graph{
	function __construct(){
		parent::__construct();
	}

	function set_edge($from,$to,$weight){
		parent::set_edge($from,$to,$weight);
		parent::set_edge($to,$from,$weight);
	}
}

class Graph_directed extends Graph{
	function __construct(){
		parent::__construct();
	}


}



$graph = new Graph();

$graph->insert('index-0');
$graph->insert('index-1');
$graph->insert('index-2');
$graph->insert('index-3');
$graph->insert('index-4');
$graph->insert('index-5');
$graph->insert('index-6');

$graph->set_edge(0,1,2);
$graph->set_edge(0,3,1);
$graph->set_edge(1,3,3);
$graph->set_edge(1,4,10);
$graph->set_edge(3,4,2);
$graph->set_edge(3,2,2);
$graph->set_edge(2,0,4);

$graph->set_edge(3,5,8);
$graph->set_edge(2,5,5);
$graph->set_edge(3,6,4);
$graph->set_edge(4,6,6);
$graph->set_edge(6,5,1);
// $graph->insert('index-7');





// $graph->set_edge(0,2,9);
// $graph->set_edge(2,5,9);
// $graph->set_edge(1,3,9);
// $graph->set_edge(0,5,9);
// $graph->set_edge(5,1,8);

// $graph->bfs(function($data){
// 	var_dump($data);
// });
// $short = $graph->shortpath_unweighted();
// var_dump($short);

$short = $graph->shortpath_multisource();
var_dump($short);

// var_dump(INF);
// var_dump(9999<INF);
?>