选择排序是最容易理解的一个排序算法了，它的思路是这样的：从待排序列找到最小的，将最小值从待排序列移除，重复上述两步直到排好序。

```php
static public function selectSort(&$arr){
	if(!is_array($arr)){
		return false;
	}
	$len = count($arr);
	for($i=0;$i<$len;$i++){
		$item = $arr[$i];
		$index = $i;
		for($j=$i+1;$j<$len;$j++){
			if($arr[$j]<$item){
				$index = $j;
				$item = $arr[$j];
			}
		}
		self::_swap($arr,$i,$index);
	}
	return true;
}
```

这个算法时间复杂度为O(n^2)。