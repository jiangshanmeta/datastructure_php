首先要了解的概念是有向图和无向图。有向图的节点之间的边是有方向的，类似于向量，无向图节点之间的边没有方向，可以理解为有一条从A指向B的边，同时也有一条从B到A的边。

然后我们要解决的是如何表示图，这里的重点是如何表示多对多的关系。

```php
class Graph{
	protected $nodes = [];
	protected $_edges = [];
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


}
```

我们用一个```Graph```类表示图，其中```$nodes```用来存储节点，用```$_edges```表示边。```$_edges```的键为节点的索引，值是一个array的形式，这个array的键表示相邻节点的索引，array的值表示边的权重。上面的```set_edge```方法就是建立边的方法。

对于有向图来说，上面的```set_edge```方法已经足够了，对于无向图来说，当建立一条从A到B的边，我们同时需要建立从B到A的边。


```php
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

对于图的基本表示其实有很多方法，这里只是根据自己的理解对应的一种实现。