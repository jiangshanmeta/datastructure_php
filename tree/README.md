树是n个节点的有限集。在任意一棵非空树中:(1)有且仅有一个特定的称为根的节点;(2)当n>1时，其余节点可分为m个互不相交的有限集，其中每个有限集本身又是一颗树，称为根的子树。

从定义中我们可以看到树其实是一种递归的定义，和树有关的操作也大量和递归有关。

关于树的基本概念，比如**节点的度**、**树的度**、**节点层次**、**树的深度**，请自行查阅相关书籍。


我们接下来要讨论的主要是二叉树。二叉树是一种特定类型的树，每个节点至多有两颗子树(任意节点的度不大于2，树的度不大于2)，且这两颗子树有左右之分。

关于二叉树有一些基本的性质。

* 在二叉树的第i层至多有2^(i-1)个节点(i≥1)
* 深度为k的二叉树至多有2^k -1 个节点(k≥1)
* 对于任何一棵二叉树，如果其叶节点数为n0，度为2的节点数为n2，则 n0 = n2 + 1

为了在PHP中实现二叉树，我们先做些准备工作

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

	function setLeft($value){
		$node = $this->_formatNode($value);
		$this->leftNode = $node;
	}

	function setRight($value){
		$value = $this->_formatNode($value);
		$this->rightNode = $value;
	}

}
```

这个```Node_tree```即用来表示一个节点，又用来表示树。我们约定，和节点有关的操作为实例方法，和树有关的操作为静态方法。这个节点有一个指向左子树的指针域```leftNode```和一个指向右子树的指针域```rightNode```，为了方便起见我还加了一个指向父节点的指针域```parentNode```。到这里你应该已经看出来的，我这里采用的是类似于单链表的链式表示。