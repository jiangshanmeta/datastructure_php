冒泡排序的思路是这样的：序列从头向后扫描，如果当前元素大于下一个元素，则两者交换，第一次扫描完成后最大的元素一定在序列最后，然后重复这个步骤直到全部排好。

```php
class Sort{
	static function bubbleSort($arr){
		if(!is_array($arr)){
			return $arr;
		}
		$len = count($arr);
		for($end = $len-1;$end>=0;$end--){
			for($i=0;$i<$end;$i++){
				if($arr[$i]>$arr[$i+1]){
					$temp = $arr[$i];
					$arr[$i] = $arr[$i+1];
					$arr[$i+1] = $temp;
					$flag = true;
				}
			}
		}
		return $arr;
	}
}
```

上面就是基本实现了，这样算法时间复杂度为O(n^2)。

这个算法还有可以改进的地方：如果执行到一定程度已经有序了，我们应该终止循环。那如何判断已经有序了呢？如果一趟排序从头到尾没有发生交换，则已经排好序了。在下面的实现中，我们通过一个变量```$flag```来表明一趟循环是否发生了交换。

```php
static function bubbleSort($arr){
	if(!is_array($arr)){
		return $arr;
	}
	$len = count($arr);
	for($end = $len-1;$end>=0;$end--){
		$flag = false;
		for($i=0;$i<$end;$i++){
			if($arr[$i]>$arr[$i+1]){
				$temp = $arr[$i];
				$arr[$i] = $arr[$i+1];
				$arr[$i+1] = $temp;
				$flag = true;
			}
		}
		if(!$flag){
			break;
		}
	}
	return $arr;
}
```

这样最好情况下(顺序)时间复杂度为O(n)，最坏情况下时间复杂度度为O(n^2)。