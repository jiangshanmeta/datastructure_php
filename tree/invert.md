反转二叉树是这个圈子里一个著名的梗，所以我把这个操作单独拿出来。

所谓反转二叉树，就是把左右子树对换。我将用两种方式来实现，一种是递归的，另一种是非递归的。

```php
static function invertBinaryTree1($tree){
	if(!($tree instanceof Node_tree)){
		return false;
	}

	$temp = $tree->leftNode;
	$tree->leftNode = $tree->rightNode;
	$tree->rightNode = $temp;
	self::invertBinaryTree($tree->leftNode);
	self::invertBinaryTree($tree->rightNode);
	return true;
}
```

上面是递归实现反转二叉树，我们反转的顺序是从上到下，不过通过调整代码顺序，我们可以轻松实现从下到上反转二叉树。

```php
static function invertBinaryTree2($tree){
	if(!($tree instanceof Node_tree)){
		return false;
	}
	$queue = [];
	array_push($queue,$tree);
	while(!empty($queue)){
		$node = array_shift($queue);
		if($node->leftNode){
			array_push($queue,$node->leftNode);
		}
		if($node->rightNode){
			array_push($queue,$node->rightNode);
		}

		$temp = $node->leftNode;
		$node->leftNode = $node->rightNode;
		$node->rightNode = $temp;
	}
	return true;
}
```

上面是非递归实现反转二叉树，可以看到和层序遍历类似，我使用了一个队结构。上面的实现反转是从上到下进行的。

到现在我们的代码清单如下：

```php
class Node_tree{
	public $leftNode = NULL;
	public $rightNode = NULL;
	public $parentNode = NULL;
	public $data;
	function __construct($value=NULL){
		$this->update($value);
	}

	function update($newValue){
		$this->data = $newValue;
	}

	protected function _formatNode($value){
		if(!($value instanceof Node_tree)){
			$value = new Node_tree($value);
		}
		$value->parentNode = $this;
		return $value;
	}

	function setLeft($value){
		$node = $this->_formatNode($value);
		$this->leftNode = $node;
	}

	function setRight($value){
		$value = $this->_formatNode($value);
		$this->rightNode = $value;
	}

	// 树的深度
	static function depth($tree){
		$leftDepth = 0;
		$rightDepth = 0;
		if($tree->leftNode){
			$leftDepth = self::depth($tree->leftNode);
		}
		if($tree->rightNode){
			$rightDepth = self::depth($tree->rightNode);
		}
		return max($leftDepth,$rightDepth) + 1;
	}

	// 节点的层次
	function level(){
		$count = 1;
		$parentNode = $this->parentNode;
		while($parentNode){
			$parentNode = $parentNode->parentNode;
			$count++;
		}
		return $count;
	}

	// 节点数量
	static function length($tree){
		$leftTreeLength = 0;
		$rightTreeLength = 0;
		if($tree->leftNode){
			$leftTreeLength = self::length($tree->leftNode);
		}
		if($tree->rightNode){
			$rightTreeLength = self::length($tree->rightNode);
		}
		return $leftTreeLength + $rightTreeLength + 1;
	}

	static function preOrderTraversal($tree,$fn){
		if(!($tree instanceof Node_tree)){
			return false;
		}
		if(is_callable($fn,true)){
			$fn($tree->data);
		}
		self::preOrderTraversal($tree->leftNode,$fn);
		self::preOrderTraversal($tree->rightNode,$fn);
	}

	static function inOrderTraversal($tree,$fn){
		if(!($tree instanceof Node_tree)){
			return false;
		}

		self::inOrderTraversal($tree->leftNode,$fn);
		if(is_callable($fn,true)){
			$fn($tree->data);
		}
		self::inOrderTraversal($tree->rightNode,$fn);
	}

	static function postOrderTraversal($tree,$fn){
		if(!($tree instanceof Node_tree)){
			return false;
		}

		self::postOrderTraversal($tree->leftNode,$fn);
		self::postOrderTraversal($tree->rightNode,$fn);
		if(is_callable($fn,true)){
			$fn($tree->data);
		}
	}

	static function levelOrderTraversal($tree,$fn){
		if(!($tree instanceof Node_tree)){
			return false;
		}
		$queue = [];
		array_push($queue,$tree);
		while(!empty($queue)){
			$node = array_shift($queue);
			if(is_callable($fn,true)){
				$fn($node->data);
			}
			if($node->leftNode){
				array_push($queue,$node->leftNode);
			}
			if($node->rightNode){
				array_push($queue,$node->rightNode);
			}
		}
		return true;
	}

	static function invertBinaryTree1($tree){
		if(!($tree instanceof Node_tree)){
			return false;
		}

		$temp = $tree->leftNode;
		$tree->leftNode = $tree->rightNode;
		$tree->rightNode = $temp;
		self::invertBinaryTree($tree->leftNode);
		self::invertBinaryTree($tree->rightNode);
		return true;
	}

	static function invertBinaryTree2($tree){
		if(!($tree instanceof Node_tree)){
			return false;
		}
		$queue = [];
		array_push($queue,$tree);
		while(!empty($queue)){
			$node = array_shift($queue);
			if($node->leftNode){
				array_push($queue,$node->leftNode);
			}
			if($node->rightNode){
				array_push($queue,$node->rightNode);
			}

			$temp = $node->leftNode;
			$node->leftNode = $node->rightNode;
			$node->rightNode = $temp;
		}
		return true;
	}


}
```