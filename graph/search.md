图的遍历有两种基本方式：深度优先搜索(Depth First Search)和广度优先搜索(Breadth First Search)。看着这两个词觉得挺陌生的，但是在树中我们学习了他们的特殊形式先序遍历和层序遍历。

```php
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
```

类似于先序遍历，深度优先搜索需要借助于一个栈结构。简化起见我这里直接使用了一个array模拟了一个栈。与栈不同的是一个节点可能从不同路径上被访问到，所以才有了```$hash```这个变量存储哪些节点被访问过了或者加入了待访问序列。

```php
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
```

图的广度优先搜索类似于树的层序遍历，需要借助于一个队结构。