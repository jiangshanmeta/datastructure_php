冒泡排序的思路是这样的：从头到尾扫描待排序列，如果当前元素大于下一个元素，则两元素交换位置，每经过一次扫描最大元素移到待排序列最后。

```php
static public function bubbleSort(&$arr){
	if(!is_array($arr)){
		return false;
	}
	$len = count($arr);
	// 每扫描一次，筛出最大的元素放到最后
	for($i=0;$i<$len;$i++){
		$end = $len - $i;
		$flag = false;
		for($j=1;$j<$end;$j++){
			if($arr[$j-1]>$arr[$j]){
				self::_swap($arr,$j-1,$j);
				$flag = true;
			}
		}
		if(!$flag){
			break;
		}
	}
	return true;
}
```

上面的实现中有个```$flag```，它的作用是表示是否已经排好序了，当还需要交换的时候说明没有排好序，一次扫描没有发生交换说明已经排好序了，这时候需要终止循环避免无用的扫描。

这个实现对应最好时间复杂度为O(n)，对应最坏时间复杂度为O(n^2)。