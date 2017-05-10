归并排序核心思想是“分而治之”，它的思路是这样的：要实现一个序列的排序，先实现左半个序列的排序，再实现右半个序列的排序，最后把这两个排好序的半个序列合并在一起。

两个排好序的序列合并在一起这个问题，我们早在线性结构那里就遇到了，这里再回顾一下：

```php
static private function _merge($arr1,$arr2){
	$len1 = count($arr1);
	$len2 = count($arr2);
	$rst = [];
	$index1 = 0;
	$index2 = 0;
	while($index1<$len1 && $index2<$len2){
		$item1 = $arr1[$index1];
		$item2 = $arr2[$index2];
		if($item1<$item2){
			$rst[] = $item1;
			$index1++;
		}else{
			$rst[] = $item2;
			$index2++;
		}
	}

	while($index1<$len1){
		$rst[] = $arr1[$index1];
		$index1++;
	}

	while($index2<$len2){
		$rst[] = $arr2[$index2];
		$index2++;
	}

	return $rst;
}
```

在实现了归并的基础上，我们来对左半个序列归并排序，对右半个序列归并排序，然后把两个排好序的半序列归并：

```php
static function mergeSort($arr){
	if(!is_array($arr) || empty($arr)){
		return $arr;
	}
	$len = count($arr);
	// 递归的退出条件
	if($len===1){
		return $arr;
	}
	// 一分为二，递归处理
	$mid = floor($len/2);
	$left = self::mergeSort(array_slice($arr,0,$mid));
	$right = self::mergeSort(array_slice($arr,$mid));

	return self::_merge($left,$right);
}
```

这种算法时间复杂度为O(nlogn)。