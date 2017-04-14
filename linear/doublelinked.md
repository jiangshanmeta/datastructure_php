之前所讨论的链表是单向链表，只能从前向后寻找，无法从后向前查找节点，在这一节我们来解决这个问题。

首先要对节点进行扩展：

```php
class Node_doublelinked extends Node_linked{
	public $prev = NULL;
	function __construct($value=NULL){
		parent::__construct($value);
	}
}
```

我们是基于之前的节点类```Node_linked```进行了改造，添加了一个```$prev```属性，这个属性指向了前一个节点。

随后我们实现双向链表类：

```php
class Linear_doublelinked extends Linear_linked{
	function __construct(){
		$this->_head = new Node_doublelinked();		
	}

	function insert($value,$index){
		$length = $this->length();
		if($index<0 || $index>$length){
			return false;
		}
		$node = new Node_doublelinked($value);
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}
		$node->next = $prev->next;
		$node->prev = $prev;
		if($prev->next){
			$prev->next->prev = $node;
		}
		$prev->next = $node;

		return true;
	}

	function delete($index){
		$length = $this->length();
		if($index<0 || $index>$length-1){
			return NULL;
		}
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}
		$next = $prev->next;
		$value = $next->data;
		if($next->next){
			$next->next->prev = $prev;
		}
		$prev->next = $next->next;
		$next->next = NULL;
		$next->prev = NULL;
		unset($next);
		return $value;
	}
}
```

我这里只实现了双向链表的插入和删除操作，其他需要修改的操作，如```merge```请自行完成。在插入时，双向链表所需要改变的指针域更多，要考虑对```$prev```属性进行设置，注意这里的顺序。在删除时，也需要改变后继节点的```$prev```指向。