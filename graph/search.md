图的遍历有两种基本方式：深度优先搜索(Depth First Search)和广度优先搜索(Breadth First Search)。看着这两个词觉得挺陌生的，但是在树中我们学习了他们的特殊形式先序遍历和层序遍历。

```php
function dfs($fn,$nodeIndex=0){
	$stack = [];
	$hash = [];
	$hash[$nodeIndex] = $nodeIndex;
	$node = $this->get_node_by_index($nodeIndex);
	array_push($stack,$node);
	while(!empty($stack)){
		$node = array_pop($stack);
		$index = $node->get_index();
		if(is_callable($fn,true)){
			$fn($node->data);
		}
		if(isset($this->_edges[$index])){
			foreach ($this->_edges[$index] as $key => $value) {
				if(!isset($hash[$key])){
					$hash[$key] = $key;
					array_push($stack,$this->get_node_by_index($key));
				}
			}
		}
	}
}
```

类似于先序遍历，深度优先搜索需要借助于一个栈结构。简化起见我这里直接使用了一个array模拟了一个栈。与栈不同的是一个节点可能从不同路径上被访问到，所以才有了```$hash```这个变量存储哪些节点被访问过了或者加入了待访问序列。

```php
function bfs($fn,$nodeIndex=0){
	$queue = [];
	$hash = [];
	$hash[$nodeIndex] = $nodeIndex;
	array_push($queue,$this->get_node_by_index($nodeIndex));
	while (!empty($queue)) {
		$node = array_shift($queue);
		$index = $node->get_index();
		if(is_callable($fn,true)){
			$fn($node->data);
		}
		if(isset($this->_edges[$index])){
			foreach ($this->_edges[$index] as $key => $value) {
				if(!isset($hash[$key])){
					$hash[$key] = $key;
					array_push($queue,$this->get_node_by_index($key));
				}
			}
		}


	}
}
```

图的广度优先搜索类似于树的层序遍历，需要借助于一个队结构。


到现在我们的代码清单如下：

```php
class Node_graph{
	protected $index;
	public $data;
	function __construct($data=NULL){
		$this->update($data);
	}

	function get_index(){
		return $this->index;
	}

	function set_index($index){
		$this->index = $index;
	}

	function update($data){
		$this->data = $data;
	}

}

class Graph{
	public $nodes = [];
	public $_edges = [];
	function __construct(){

	}

	function insert($node){
		if(!($node instanceof Node_graph)){
			$node = new Node_graph($node);
		}
		$index = $this->length();
		$node->set_index($index);
		$this->nodes[$index] = $node;
	}

	function length(){
		return count($this->nodes);
	}

	function set_edge($from,$to,$weight=1){
		if(!isset($this->_edges[$from])){
			$this->_edges[$from] = [];
		}
		$this->_edges[$from][$to] = $weight;
	}

	function get_node_by_index($index){
		return $this->nodes[$index];
	}

	function dfs($fn,$nodeIndex=0){
		$stack = [];
		$hash = [];
		$hash[$nodeIndex] = $nodeIndex;
		$node = $this->get_node_by_index($nodeIndex);
		array_push($stack,$node);
		while(!empty($stack)){
			$node = array_pop($stack);
			$index = $node->get_index();
			if(is_callable($fn,true)){
				$fn($node->data);
			}
			if(isset($this->_edges[$index])){
				foreach ($this->_edges[$index] as $key => $value) {
					if(!isset($hash[$key])){
						$hash[$key] = $key;
						array_push($stack,$this->get_node_by_index($key));
					}
				}
			}
		}
	}

	function bfs($fn,$nodeIndex=0){
		$queue = [];
		$hash = [];
		$hash[$nodeIndex] = $nodeIndex;
		array_push($queue,$this->get_node_by_index($nodeIndex));
		while (!empty($queue)) {
			$node = array_shift($queue);
			$index = $node->get_index();
			if(is_callable($fn,true)){
				$fn($node->data);
			}
			if(isset($this->_edges[$index])){
				foreach ($this->_edges[$index] as $key => $value) {
					if(!isset($hash[$key])){
						$hash[$key] = $key;
						array_push($queue,$this->get_node_by_index($key));
					}
				}
			}


		}


	}


}


class Graph_undirected extends Graph{
	function __construct(){
		parent::__construct();
	}

	function set_edge($from,$to,$weight=1){
		parent::set_edge($from,$to,$weight);
		parent::set_edge($to,$from,$weight);
	}
}
```