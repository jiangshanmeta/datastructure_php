线性结构的链式存储结构的特点是用一组任意的存储单元存储线性表的数据元素。

在这里，我们用一个特殊的节点类表示存储元素。

```php
class Node_linked{
	public $data;
	public $next = NULL;
	public function __construct($value=NULL){
		$this->data = $value;
	}

	public function update($newValue){
		$this->data = $newValue;
	} 
}
```

对于每一个节点，有一个特殊的数据域```$data```表示存储的数据，还有一个指针域```$next```指向下一节点。这个类仅仅用来表示最简单的单向链表的节点，只能依次向后寻找节点。双向链表的节点其实差不多，只是多了一个前一个节点的指针域。

为了操作方便，我们为链式存储添加一个头结点。

```php
class Linear_linked{
	protected $_head;
	function __construct(){
		$this->_head = new Node_linked();
	}

	function head(){
		return $this->_head;
	}

}
```

链式存储的所需要实现的操作和顺序存储是一致的，我们首先看长度相关的几个方法。

```php
function head(){
	return $this->_head;
}

function length(){
	$length = 0;
	$prev = $this->head();
	while($prev->next){
		$prev = $prev->next;
		$length++;
	}
	return $length;
}

function isEmpty(){
	return $this->head()->next === NULL;
}
```

在这里不能直接调用count来获得线性表的长度了(其实对于PHP也是可以的，但是要实现某个特殊的接口，最终还是要自己实现)，需要我们手动遍历来计数。遍历的终点就是指向下一个节点的指针域为空。

然后就是插入操作了

```
function insert($value,$index){
	$length = $this->length();
	if($index<0 || $index>$length){
		return false;
	}
	$node = new Node_linked($value);
	$prev = $this->head();
	for($i=0;$i<$index;$i++){
		$prev = $prev->next;
	}
	$node->next = $prev->next;
	$prev->next = $node;

	return true;
}
```

与顺序存储相比，链式存储需要的操作少多了，他不需要把```$index```及以后的每一个元素都一位，在链式存储中只需要修改有限的几个指针域就可以了。相关的```push```操作和```unshift```操作的实现和顺序存储的是一致的，都是间接调用```insert```操作。

删除操作

```php
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
	$prev->next = $next->next;
	$next->next = NULL;
	unset($next);
	return $value;
}
```

删除操作也是需要找到前一个节点，然后修改几个指针域即可，不需要顺序存储那样大量的挪动位置。

根据序号寻找元素就比较折腾了。

```php
function findEleByIndex($index){
	$length = $this->length();
	if($index<0 || $index>$length-1){
		return NULL;
	}
	$prev = $this->head();
	for($i=0;$i<$index;$i++){
		$prev = $prev->next;
	}

	return $prev->next->data;
}
```

需要我们手动计数寻找对应的节点，而根据元素寻找序号和顺序结构类似，都需要一个一个按顺序比对。

```php
function findIndexByEle($value){
	$length = $this->length();
	$prev = $this->head();
	for($i=0;$i<$length;$i++){
		$prev = $prev->next;
		if($prev->data === $value){
			return $i;
		}
	}
	return -1;
}
```

到这里我们会发现有相当一部分代码是重复的，我们需要一个根据索引找节点(上面的是找元素)的功能，请大家自行实现```findNodeByIndex```这个方法(为了使用方便，我们需要返回序号对应节点的前一个节点)。


至于merge功能，对于链式存储结构来说就更简单了，因为只需要首尾相连即可。

```php
function tail(){
	$prev = $this->head();
	while($prev->next){
		$prev = $prev->next;
	}
	return $prev;
}
function merge($linked){
	if(!($linked instanceof Linear_linked)){
		return false;
	}
	$this->tail()->next = $linked->head()->next;
	return true;
}
```

这样链式存储的线性表的基本操作就完成了。当前的代码清单如下：

```php
class Node_linked{
	public $data;
	public $next = NULL;
	public function __construct($value=NULL){
		$this->data = $value;
	}

	public function update($newValue){
		$this->data = $newValue;
	} 
}

class Linear_linked{
	protected $_head;
	function __construct(){
		$this->_head = new Node_linked();
	}

	function head(){
		return $this->_head;
	}

	function tail(){
		$prev = $this->head();
		while($prev->next){
			$prev = $prev->next;
		}
		return $prev;
	}

	function insert($value,$index){
		$length = $this->length();
		if($index<0 || $index>$length){
			return false;
		}
		$node = new Node_linked($value);
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}
		$node->next = $prev->next;
		$prev->next = $node;

		return true;
	}

	function push($value){
		return $this->insert($value,$this->length());
	}

	function unshift($value){
		return $this->insert($value,0);
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
		$prev->next = $next->next;
		$next->next = NULL;
		unset($next);
		return $value;
	}

	function pop(){
		return $this->delete($this->length()-1);
	}

	function shift(){
		return $this->delete(0);
	}

	function length(){
		$length = 0;
		$prev = $this->head();
		while($prev->next){
			$prev = $prev->next;
			$length++;
		}
		return $length;
	}

	function isEmpty(){
		return $this->head()->next === NULL;
	}

	function clear(){
		$this->head()->next = NULL;
	}

	function findEleByIndex($index){
		$length = $this->length();
		if($index<0 || $index>$length-1){
			return NULL;
		}
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}

		return $prev->next->data;
	}

	function findIndexByEle($value){
		$length = $this->length();
		$prev = $this->head();
		for($i=0;$i<$length;$i++){
			$prev = $prev->next;
			if($prev->data === $value){
				return $i;
			}
		}
		return -1;
	}

	function update($newValue,$index){
		$length = $this->length();
		if($index<0 || $index>$length-1){
			return false;
		}
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}
		$prev->next->update($newValue);
		return true;
	}

	function merge($linked){
		if(!($linked instanceof Linear_linked)){
			return false;
		}
		$this->tail()->next = $linked->head()->next;
		return true;
	}

	// 返回序号所对应的节点的前一个节点
	function findNodeByIndex($index){
		$length = $this->length();
		if($index<0 || $index>$length-1){
			return NULL;
		}
		$prev = $this->head();
		for($i=0;$i<$index;$i++){
			$prev = $prev->next;
		}
		return $prev;
	}


}

```