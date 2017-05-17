归并排序核心思想是“分而治之”，它的思路是这样的：要实现一个序列的排序，先实现左半个序列的排序，再实现右半个序列的排序，最后把这两个排好序的半个序列合并在一起。

```php

// 实现序列合并
static private function _merge(&$arr,$start,$mid,$end){
	if($mid<=$start || $end<=$mid){
		return;
	}
	$temp = [];
	$len1 = $mid - $start;
	$len2 = $end - $mid;
	$index1 = 0;
	$index2 = 0;
	while ($index1<$len1 && $index2<$len2) {
		if($arr[$start+$index1]<=$arr[$mid+$index2]){
			$temp[] = $arr[$start+$index1];
			$index1++;

		}else{
			$temp[] = $arr[$mid+$index2];
			$index2++;
		}
	}
	while ($index1<$len1) {
		$temp[] = $arr[$start+$index1];
		$index1++;
	}
	while ($index2<$len2) {
		$temp[] = $arr[$mid+$index2];
		$index2++;
	}
	$len = count($temp);
	for($i=0;$i<$len;$i++){
		$arr[$start+$i] = $temp[$i];
	}		

}

static public function mergeSort(&$arr,$from=0,$to=NULL){
	if(!is_array($arr)){
		return false;
	}
	if($to===NULL){
		$to = count($arr) - 1;
	}
	$len = $to - $from;
	// 递归退出条件
	if($len<1){
		return false;
	}
	$mid = floor(($to+$from)/2);
	self::mergeSort($arr,$from,$mid);
	self::mergeSort($arr,$mid+1,$to);

	self::_merge($arr,$from,$mid+1,$to+1);
	return true;
}
```

这种算法时间复杂度为O(nlogn)。