二叉搜索树是一种特殊的二叉树，它满足以下条件：

* 非空左子树的所有值小于其根节点的值。
* 非空右子树的所有值大于其根节点的值。
* 左右子树均为二叉搜索树。

因为二叉搜索树是特殊的二叉树，代码实现上表现为继承，并且为了以后方便，我们在一般二叉树节点上添加三个方法：

```php
function isLeaf(){
	return (!$this->leftNode) && (!$this->rightNode);
}

function isHalf(){
	return (!$this->isLeaf()) && (!$this->isFull());
}

function isFull(){
	return $this->leftNode && $this->rightNode;
}
```

这三个方法是用来反应节点子树数量的。

在正式书写代码之前我们先观察一下二叉树，我们可以发现，二叉搜索树中值最小的在二叉树的左下角，二叉搜索树值最大的在二叉树的右下角，据此我们可以写出二叉搜索树的查找最值节点的两个方法。

```php
class Node_bst extends Node_tree{
	function __construct($value=NULL){
		parent::__construct($value);
	}

	static function findMax($bst){
		if(!($bst instanceof Node_bst)){
			return NULL;
		}
		while($bst->rightNode){
			$bst = $bst->rightNode;
		}
		return $bst;
	}

	static function findMin($bst){
		if(!($bst instanceof Node_bst)){
			return NULL;
		}
		while($bst->leftNode){
			$bst = $bst->leftNode;
		}
		return $bst;
	}

}
```

在二叉搜索树中找到值为X的节点，如果X小于根节点的值，那么如果二叉搜索树存在该值，那么一定在左子树中；如果X大于根节点的值，如果二叉搜索树存在该值，那么一定在右子树中。如果X等于根节点的值，还用说嘛。这就是在二叉搜索树中查找值为X的节点的思路。

```php
static function find($bst,$data){
	if(!($bst instanceof Node_bst)){
		return NULL;
	}
	while($bst){
		if($bst->data>$data){
			$bst = $bst->leftNode;
		}else if($bst->data<$data){
			$bst = $bst->rightNode;
		}else{
			return $bst;
		}
	}
	return NULL;
}
```

到现在我们所讨论的操作都是对于一棵现成的二叉搜索树，那么二叉搜索树该如何构建呢？

在二叉搜索树中插入节点不能随便插入，毕竟根据定义节点所在的位置是有限制的。

```php
static function insert($bst,$data){
	if(!($data instanceof $bst)){
		$data = new Node_bst($data);
	}

	if($data->data<$bst->data){
		if(!$bst->leftNode){
			$bst->setLeft($data);
		}else{
			self::insert($bst->leftNode,$data);
		}
	}else if($data->data>$bst->data){
		if(!$bst->rightNode){
			$bst->setRight($data);			
		}else{
			self::insert($bst->rightNode,$data);		
		}
	}

}
```

根据定义，如果新节点的值小于根节点，那么一定在左侧，如果没有左子树，直接作为左子树就好了，否则就需要把这个新节点插入到左子树这个二叉搜索树中。大于的情况类似，不做赘述。


关于二叉搜索树还有一个操作就是删除节点，我将从要被删除的节点的角度看这个问题。如果是叶节点，直接删掉该节点，删除父节点的引用就好了。如果只有一颗子树，父节点的相关引用指向这棵子树就可以了。最复杂的是有两棵子树的情况，我们可以找左子树的最大值节点，或者右子树的最小值节点代替该节点，这样在删除的同时能保证依然是二叉搜索树，并且改动最小。

```php
// 删除节点
static function delete($node){
	$parentNode = $node->parentNode;
	if($node->isLeaf()){
		if($parentNode){
			if($parentNode->leftNode===$node){
				$parentNode->leftNode = NULL;
			}else{
				$parentNode->rightNode = NULL;
			}
		}
		$node->parentNode = NULL;
	}else if($node->isHalf()){
		if($node->leftNode){
			$node->leftNode->parentNode = $parentNode;
			if($parentNode){
				if($parentNode->leftNode===$node){
					$parentNode->leftNode = $node->leftNode;
					// $parentNode->setLeft($node->leftNode);
				}else{
					$parentNode->rightNode = $node->leftNode;
					// $parentNode->setRight($node->rightNode);
				}
			}
		}else{
			$node->rightNode->parentNode = $parentNode;
			if($parentNode){
				if($parentNode->leftNode===$node){
					$parentNode->leftNode = $node->rightNode;
				}else{
					$parentNode->rightNode = $node->rightNode;
				}
			}
		}

		$node->parentNode = NULL;
	}else{
		$rightMinNode = self::findMin($node->rightNode);
		$node->update($rightMinNode->data);
		self::delete($rightMinNode);
	}
}
```

关于二叉搜索树的代码清单如下：

```php
class Node_bst extends Node_tree{
	function __construct($value=NULL){
		parent::__construct($value);
	}

	static function find($bst,$data){
		if(!($bst instanceof Node_bst)){
			return NULL;
		}
		while($bst){
			if($bst->data>$data){
				$bst = $bst->leftNode;
			}else if($bst->data<$data){
				$bst = $bst->rightNode;
			}else{
				return $bst;
			}
		}
		return NULL;
	}

	static function findMax($bst){
		if(!($bst instanceof Node_bst)){
			return NULL;
		}
		while($bst->rightNode){
			$bst = $bst->rightNode;
		}
		return $bst;
	}

	static function findMin($bst){
		if(!($bst instanceof Node_bst)){
			return NULL;
		}
		while($bst->leftNode){
			$bst = $bst->leftNode;
		}
		return $bst;
	}

	static function insert($bst,$data){
		if(!($data instanceof $bst)){
			$data = new Node_bst($data);
		}

		if($data->data<$bst->data){
			if(!$bst->leftNode){
				$bst->setLeft($data);
			}else{
				self::insert($bst->leftNode,$data);
			}
		}else if($data->data>$bst->data){
			if(!$bst->rightNode){
				$bst->setRight($data);			
			}else{
				self::insert($bst->rightNode,$data);		
			}
		}

	}

	// 删除节点
	static function delete($node){
		$parentNode = $node->parentNode;
		if($node->isLeaf()){
			if($parentNode){
				if($parentNode->leftNode===$node){
					$parentNode->leftNode = NULL;
				}else{
					$parentNode->rightNode = NULL;
				}
			}
			$node->parentNode = NULL;
		}else if($node->isHalf()){
			if($node->leftNode){
				$node->leftNode->parentNode = $parentNode;
				if($parentNode){
					if($parentNode->leftNode===$node){
						$parentNode->leftNode = $node->leftNode;
						// $parentNode->setLeft($node->leftNode);
					}else{
						$parentNode->rightNode = $node->leftNode;
						// $parentNode->setRight($node->rightNode);
					}
				}
			}else{
				$node->rightNode->parentNode = $parentNode;
				if($parentNode){
					if($parentNode->leftNode===$node){
						$parentNode->leftNode = $node->rightNode;
					}else{
						$parentNode->rightNode = $node->rightNode;
					}
				}
			}

			$node->parentNode = NULL;
		}else{
			$rightMinNode = self::findMin($node->rightNode);
			$node->update($rightMinNode->data);
			self::delete($rightMinNode);
		}
	}

}
```