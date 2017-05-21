在线性结构中，节点之间的关系是一对一，在树中，节点之间的关系是一对多，在图中，节点之间的关系是多对多。所以，在树中包含了线性关系，在图中，既包含了树又包含了线性结构。

我们采用了一个节点类```Node_graph```表示图中的节点。

```php
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
```

可以在[github查看最终的实现代码](https://github.com/jiangshanmeta/datastructure_php/blob/master/graph/graph.php)