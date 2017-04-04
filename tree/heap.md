在介绍堆的相关概念之前请自行学习满二叉树和完全二叉树的有关知识。

对于完全二叉树，如果从1按照从上到下从左到右的顺序进行编号，则节点的序号之间有如下特点：

* 如果一个节点的序号为i，如果有左儿子，则左儿子序号为2i，如果有右儿子，则右儿子的序号为2i+1
* 如果一个节点序号为i，如果有父节点，则父节点序号为floor(i/2)，其中floor表示向下取整

以上两个结论非常重要，在以后的实现中会用到。

完全二叉树我们已经可以进行标号了，而且这些标号是连续的，从1开始的，在这种特殊的情况下我们就会考虑之前的链式结构是否可以换成顺序存储结构 。答案是肯定的，堆的实现我就采用了顺序存储结构。

说了这么久，堆到底是什么。堆首先是完全二叉树，并且堆有两种，一种叫最小堆，一种叫最大堆。最小堆的特点是根元素对应数据最小，从上到下数据增大，最大堆与之相反。看到这你可能会想起优先队列，毕竟优先队列就是按升序或者降序排列的。

```php
class Heap{
	protected $elements = [];
	protected $size = 0;
	function __construct(){
		$this->elements[0] = NULL;
	}

	function isEmpty(){
		return $this->size===0;
	}

}
```

上面是我们为堆建立的基类，我们将在```$elements```中保存堆中的元素，```$size```属性用来表示堆的大小。

我们将以最小堆进行讨论，主要有两个操作，一个是插入，一个是删除。

插入时的思路是这样的：最简单地插入是直接在数组后面添加一项，但是新节点的值可能会比父节点的值要小，这种情况下就不满足最小堆的要求了，需要我们重排。重排就把父节点向下移动，父节点的位置空出来给子节点。持续这一过程，直到满足最小堆。

```php
function insert($item){
	$i = ++$this->size;
	for(;$i>1&&$this->elements[floor($i/2)]>$item;$i=floor($i/2)){
		$this->elements[$i] = $this->elements[floor($i/2)];
	}
	$this->elements[$i] = $item;
}
```

for循环所做的就是重排过程，不断把父节点向下挪动。时间复杂度为O(logN)


删除时需要删除的节点是第一个节点，为了填补第一个节点的空缺我们可以用最后一个节点进行填充。但是这样直接填充不一定满足最小堆的特点，也需要我们进行重排。重排过程和插入的重排类似，但是插入的重排是从下到上的，删除的重排是从上到下进行的。在删除过程中，需要面对的一个问题是，父节点挪到哪个子节点的位置(从空位的角度),或者说哪个子节点挪上去(从真移动元素的角度)。对于最小堆，答案是较小的那个节点。


```php
function delete(){
	if($this->isEmpty()){
		return NULL;
	}
	$maxItem = $this->elements[1];
	$temp = $this->elements[$this->size--];
	$parentIndex = 1;
	$childIndex = 1;
	for(;$parentIndex*2<=$this->size;$parentIndex=$childIndex){
		$childIndex = $parentIndex*2;
		if($childIndex!==$this->size && $this->elements[$childIndex]>$this->elements[$childIndex+1]){
			$childIndex++;
		}
		if($temp<$this->elements[$childIndex]){
			break;
		}else{
			$this->elements[$parentIndex] = $this->elements[$childIndex];
		}
	}
	$this->elements[$parentIndex] = $temp;
	unset($this->elements[$this->size+1]);
	return $maxItem;
}
```

到这里最小堆的插入和删除操作就完成了，按照最小堆的代码完全可以写出最大堆的插入和删除从操作。

在往下一节走之前我们对代码做一些微小的改进。我们之前都是把节点的值当成简单值，然后直接比较，但是实际应用的时候这个节点可能存放一个数组、一个对象或者简单值，这样直接比较就不合适了。因而我们需要一个方法产生可以直接用来直接比较的值，这个方法默认是原样返回，即认为节点的值是简单值，并且允许用户自定义。


我们以最大堆为例进行修改。

首先要修改的是基类：

```php
class Heap{
	protected $elements = [];
	protected $size = 0;
	public $compareFunc;
	function __construct(){
		$this->elements[0] = NULL;
		$this->compareFunc = function($data){
			return $data;
		};
	}

	function isEmpty(){
		return $this->size===0;
	}

}
```

很容易可以看出来```$compareFunc```就是用来产生直接比较值的方法。


```php
function insert($item){
	$i = ++$this->size;
	$compareFunc = $this->compareFunc;
	$itemCompareValue = $compareFunc($item);
	for(;$i>1&&$compareFunc($this->elements[floor($i/2)])<$itemCompareValue;$i=floor($i/2)){
		$this->elements[$i] = $this->elements[floor($i/2)];
	}
	$this->elements[$i] = $item;
}
```

插入操作整体上和原来的一样，只是在比较的时候用的是```$compareFunc```的返回值而不是原始值。


```php
function delete(){
	if($this->isEmpty()){
		return NULL;
	}
	$compareFunc = $this->compareFunc;
	$maxItem = $this->elements[1];
	$temp = $this->elements[$this->size--];
	$tempComapre = $compareFunc($temp);
	$parentIndex = 1;
	$childIndex = 2;
	
	for(;$parentIndex*2<=$this->size;$parentIndex=$childIndex){
		$childIndex = $parentIndex*2;
		if($childIndex!==$this->size && $compareFunc($this->elements[$childIndex])<$compareFunc($this->elements[$childIndex+1])){
			$childIndex++;
		}
		if($tempComapre>$compareFunc($this->elements[$childIndex])){
			break;
		}else{
			$this->elements[$parentIndex] = $this->elements[$childIndex];
		}
		
	}
	$this->elements[$parentIndex] = $temp;
	unset($this->elements[$this->size+1]);
	return $maxItem;
}
```

删除操作的修改也是类似的，不做赘述。


到现在我们的代码清单如下：

```php
class MaxHeap extends Heap{
	function __construct(){
		parent::__construct();
	}

	function insert($item){
		$i = ++$this->size;
		$compareFunc = $this->compareFunc;
		$itemCompareValue = $compareFunc($item);
		for(;$i>1&&$compareFunc($this->elements[floor($i/2)])<$itemCompareValue;$i=floor($i/2)){
			$this->elements[$i] = $this->elements[floor($i/2)];
		}
		$this->elements[$i] = $item;
	}

	function delete(){
		if($this->isEmpty()){
			return NULL;
		}
		$compareFunc = $this->compareFunc;
		$maxItem = $this->elements[1];
		$temp = $this->elements[$this->size--];
		$tempComapre = $compareFunc($temp);
		$parentIndex = 1;
		$childIndex = 2;
		
		for(;$parentIndex*2<=$this->size;$parentIndex=$childIndex){
			$childIndex = $parentIndex*2;
			if($childIndex!==$this->size && $compareFunc($this->elements[$childIndex])<$compareFunc($this->elements[$childIndex+1])){
				$childIndex++;
			}
			if($tempComapre>$compareFunc($this->elements[$childIndex])){
				break;
			}else{
				$this->elements[$parentIndex] = $this->elements[$childIndex];
			}
			
		}
		$this->elements[$parentIndex] = $temp;
		unset($this->elements[$this->size+1]);
		return $maxItem;
	}
}

class MinHeap extends Heap{
	function __construct(){
		parent::__construct();
	}

	function insert($item){
		$i = ++$this->size;
		$compareFunc = $this->compareFunc;
		$itemCompareValue = $compareFunc($item);
		for(;$i>1&&$compareFunc($this->elements[floor($i/2)])>$itemCompareValue;$i=floor($i/2)){
			$this->elements[$i] = $this->elements[floor($i/2)];
		}
		$this->elements[$i] = $item;
	}

	function delete(){
		if($this->isEmpty()){
			return NULL;
		}
		$compareFunc = $this->compareFunc;
		$maxItem = $this->elements[1];
		$temp = $this->elements[$this->size--];
		$tempComapre = $compareFunc($temp);
		$parentIndex = 1;
		$childIndex = 1;
		for(;$parentIndex*2<=$this->size;$parentIndex=$childIndex){
			$childIndex = $parentIndex*2;
			if($childIndex!==$this->size && $compareFunc($this->elements[$childIndex])>$compareFunc($this->elements[$childIndex+1]) ){
				$childIndex++;
			}
			if($tempComapre<$compareFunc($this->elements[$childIndex]) ){
				break;
			}else{
				$this->elements[$parentIndex] = $this->elements[$childIndex];
			}
		}
		$this->elements[$parentIndex] = $temp;
		unset($this->elements[$this->size+1]);
		return $maxItem;
	}
}
```