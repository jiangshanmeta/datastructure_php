选择排序是最容易理解的一个排序了，它的思路是这样的：依次从未排序的序列中找到最小的，把它交换到有序部分的最后。

```php
static function selectSort($arr){
	if(!is_array($arr)){
		return $arr;
	}
	$len = count($arr);
	for($i=0;$i<$len;$i++){
		$item = $arr[$i];
		for($j=$i+1,$index=$i;$j<$len;$j++){
			if($arr[$j]<$item){
				$index = $j;
			}
		}
		if($i!==$index){
			$arr[$i] = $arr[$index];
			$arr[$index] = $item;
		}
	}
	return $arr;
}
```

这个算法时间复杂度为O(n^2)。