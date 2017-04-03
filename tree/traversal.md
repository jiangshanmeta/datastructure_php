树的遍历包含先序遍历、中序遍历、后序遍历、层序遍历。

先解释一下前三种的先中后是什么意思，这个先中后指的是访问根节点的顺序。先序就是首先根节点，然后左子树，最后右子树；中序就是先左子树、然后根节点，最后右子树；后序遍历就是先左子树，然后右子树，最后根节点。注意这里默认左子树的顺序永远在右子树之前。

他们对应的实现代码如下：

```php
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
```

可以很容易看出来，这里利用的递归的思想。

层序遍历和前三种遍历不太一致，前三种遍历都可以通过栈来进行模拟，而层序遍历需要依赖队。层序遍历是按照一层一层的顺序从上到下从左到右访问每个节点。

```php
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
```

这里没有使用之前实现的队，而是利用PHP的array模拟了一个队，本身思想是一致的。

遍历二叉树有哪些应用呢，我给出如下几个例子：

```php
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
```

我们可以求得树的深度(不知道这个概念的自己看书)，我们也可以实现线性结构中的求节点数量操作。