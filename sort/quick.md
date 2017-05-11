快速排序是经常听到的一种排序算法，它和归并排序类似，都是利用了“分而治之”的思路。

它的思路是这样的：在序列中找到一个元素(称之为主元)，然后调整序列，使主元左侧的元素都比主元小、右侧的元素都比主元大，这样序列就被分成了两个小序列，然后递归地对这两个小序列进行快速排序。

```php
static private function _swap(&$arr,$index1,$index2){
	if($index1!==$index2){
		$temp = $arr[$index1];
		$arr[$index1] = $arr[$index2];
		$arr[$index2] = $temp;			
	}
}
static function quickSort(&$arr,$from=0,$to=NULL){
	if(!is_array($arr) || empty($arr)){
		return;
	}
	if($to===NULL){
		$to = count($arr) - 1;
	}
	// 递归退出条件：只有一个元素
	if($to-$from<1){
		return;
	}

	$mid = floor(($from+$to)/2);
	// 下面三个if判断保证$from、$mid、$to对应元素递增
	if($arr[$from]>$arr[$mid]){
		self::_swap($arr,$from,$mid);
	}
	if($arr[$from]>$arr[$to]){
		self::_swap($arr,$from,$to);
	}
	if($arr[$mid]>$arr[$to]){
		self::_swap($arr,$mid,$to);
	}
	// $to对应的元素比$mid大，把$mid对应元素交换到$to-1
	self::_swap($arr,$mid,$to-1);
	$pivot = $arr[$to-1];
	$low = $from;
	$high = $to -1;
	// 调整序列，使主元左侧元素都比主元小，右侧元素都比主元大
	while(true){
		while ($low<$to-1 && $arr[++$low]<$pivot) {
			
		}
		while($high>$from && $arr[--$high]>$pivot){

		}
		if($low<$high){
			self::_swap($arr,$low,$high);
		}else{
			break;
		}
	}
	self::_swap($arr,$low,$to-1);

	// 递归对两个子序列快速排序
	self::quickSort($arr,$from,$low-1);
	self::quickSort($arr,$low+1,$to);
}
```

一个要解决的问题是这个主元怎么确定。我上面给出的实现是找左中右三个位置对应元素的中位数。

还一个问题是快速排序对于数据量较大时速度比较快，数据量较小时可以结合其他排序方式，如插入排序。

快速排序的时间复杂度是O(nlogn)。