在选择排序中，我们为了从剩余元素中找到最小的，需要遍历剩余元素。想要改进选择排序，一个思路就是改进从剩余元素找到最小元素的方式。还记得树那一章提及的最小堆吗，最小堆为我们提供了一种快速寻找序列中最小元素的方法。

然而我们这里的实现用的是最大堆，这样就不需要额外的空间，直接在待排序列上修改就好了。

基本思路是这样的：首先把待排序列调整成为最大堆，然后从堆中移除最大元素，剩余元素调整成为最大堆，重复上述步骤。


```php
static function _parentIndex($index){
	return ceil($index/2)-1;
}

// 调整成为最大堆
static function _perceDown(&$arr,$from,$to){
	$temp = $arr[$from];
	$child;
	for($parent=$from;(2*$parent+1)<=$to;$parent=$child){
		$child = 2*$parent +1;
		if($child<$to&&$arr[$child]<$arr[$child+1]){
			$child++;
		}
		if($temp>=$arr[$child]){
			break;
		}else{
			$arr[$parent] = $arr[$child];
		}
	}
	$arr[$parent] = $temp;
}

static function heapSort($arr){
	if(!is_array($arr)){
		return $arr;
	}
	$len = count($arr);
	// 初始化成为最大堆
	for($i=self::_parentIndex($len-1);$i>=0;$i--){
		self::_perceDown($arr,$i,$len-1);
	}

	for($i=$len-1;$i>0;$i--){
		// 移除最大元素
		self::_swap($arr,0,$i);
		// 堆尺寸减一，剩余元素调整成为最大堆
		self::_perceDown($arr,0,$i-1);
	}
	return $arr;
}
```

堆排序的时间复杂度是O(nlogn)，这种排序方式非常适合在一个待排序列中找前几个最大的元素。