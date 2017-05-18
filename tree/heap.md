在介绍堆的相关概念之前请自行学习满二叉树和完全二叉树的有关知识。

对于完全二叉树，如果从1按照从上到下从左到右的顺序进行编号，则节点的序号之间有如下特点：

* 如果一个节点的序号为i，如果有左儿子，则左儿子序号为2i，如果有右儿子，则右儿子的序号为2i+1
* 如果一个节点序号为i，如果有父节点，则父节点序号为floor(i/2)，其中floor表示向下取整

以上两个结论非常重要，在以后的实现中会用到。

完全二叉树我们已经可以进行标号了，而且这些标号是连续的，从1开始的，在这种特殊的情况下我们就会考虑之前的链式结构是否可以换成顺序存储结构 。答案是肯定的，下面实现堆就用到了顺序存储结构(借助于一个数组)。

说了这么久，堆到底是什么。堆首先是完全二叉树，并且堆有两种，一种叫最小堆，一种叫最大堆。最小堆的特点是根元素对应数据最小，从上到下数据增大，最大堆与之相反。看到这你可能会想起优先队列，毕竟优先队列就是按升序或者降序排列的。以下的讲解均已最大堆为例。

```php
public $compareFunc;
public $elements = [];
public $size = 0;
public function __construct(){
	$this->elements[0] = NULL;
	$this->compareFunc = function($data){
		return $data;
	};
}
```

堆中的元素存放在```$elements```数组中，并且从下标1开始。```$size```属性用来表示堆中的元素个数，```$compareFunc```是比较节点大小时产生比较值的函数。

和堆相关有三个基本操作：把一个数组调整成为一个堆、向堆中插入一个元素、删除堆顶元素。这三个操作所需要的一个元操作：如果左子树是堆、右子树也是堆，如何把根节点结合进去构成一个更大的堆。


```php
static public function _perceDown(&$arr,$from,$to,$fn){
	$temp = $fn($arr[$from]);
	$rootItem = $arr[$from];
	for($parent=$from;2*$parent<=$to;$parent=$child){
		$child = 2*$parent;
		// 和左右子树较大的比较
		if($child<$to&&$fn($arr[$child])<$fn($arr[$child+1]) ){
			$child++;
		}
		if($temp<$fn($arr[$child]) ){
			$arr[$parent] = $arr[$child];
		}else{
			break;
		}
	}
	if($parent===$from){
		return false;
	}else{
		$arr[$parent] = $rootItem;
		return true;
	}
}
```

上述实现中```$arr```对应堆中的```$elements```，```$from```对应根节点在数组中的下标，```$to```是左右子树中最后一个元素的下标，```$fn```对应堆中```$compareFunc```。这个实现思路是这样的：根节点和左右子树的根节点进行比较，如果根节点比左右子树根节点都大，那么我们就已经调整成最大堆了，否则根节点和左右子树根节点中较大的互换位置，剩下要做的就是把其中被移动的子树调整成最大堆。这里的返回值是用来表示是否被调整过(在插入操作中会用到这个结果)。


然后我们看一下如何将一个数组调整成为最大堆：

```php
static public function arrayToMaxHeap($arr,$compareFunc=NULL){
	$maxHeap = new MaxHeap();
	if($compareFunc===NULL){
		$maxHeap->compareFunc = function($data){
			return $data;
		};
	}else{
		$maxHeap->compareFunc = $compareFunc;
	}
	if(!is_array($arr)){
		return $maxHeap;
	}
	$maxHeap->size = count($arr);
	array_unshift($arr,NULL);
	if(!is_array($arr)){
		return $maxHeap;
	}
	// 调整arr成为一个堆
	for($i=$maxHeap->size;$i>0;$i--){
		$parentIndex = self::_parentIndex($i);
		if($parentIndex>0){
			self::_perceDown($arr,$parentIndex,$maxHeap->size,$maxHeap->compareFunc);
		}
	}

	$maxHeap->elements = $arr;

	return $maxHeap;
}
```

这里多次调用了调整函数，从下到上逐渐调整，最大堆的规模逐渐变大。


然后要实现的是删除最大堆中的堆顶元素：

```php
public function delete(){
	if($this->isEmpty()){
		return NULL;
	}
	$data = $this->elements[1];
	// 调整剩余元素
	$this->elements[1] = $this->elements[$this->size];
	$cls = get_class($this);
	$cls::_perceDown($this->elements,1,$this->size-1,$this->compareFunc);
	unset($this->elements[$this->size]);
	$this->size--;
	return $data;
}
```

在最大堆中堆顶元素是最大元素，找到它并没有什么难度，问题是删除这个元素之后剩余的元素如何调整成为新的最大堆。我们可以把最后一个元素移到堆顶，这样左子树是一个最大堆，右子树是最大堆，要结合根节点构成新的最大堆，这个问题我在最开始已经解决了。


最后一个问题是向最大堆中插入一个元素：

```php
public function insert($data){
	$this->size++;
	$this->elements[$this->size] = $data;
	for($i=$this->size;$i>0;$i=$parentIndex){
		$parentIndex = self::_parentIndex($i);
		if($parentIndex>0){
			$cls = get_class($this);
			$rst = $cls::_perceDown($this->elements,$parentIndex,$this->size,$this->compareFunc);
			// 已经调整成堆，无需进一步调整
			if(!$rst){
				break;
			}
		}
	}
}
```

插入时先把新节点放到最后，然后从下向上调整成堆，如果在调整过程中发现某一步没有移动元素，说明已经调整成堆无需进一步向上调整。

[相关实现代码可以在github上看到](https://github.com/jiangshanmeta/datastructure_php/blob/master/tree/heap.php)