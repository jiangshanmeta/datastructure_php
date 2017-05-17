插入排序类似于我们插入扑克牌的过程：拿到一张新牌，与手中的牌比较(倒序)，如果被比较的牌比新牌大，被比较的牌向后移空出新牌的位置，否则终止比较，空出来的位置就是新牌应该插入的位置。

```php
static public function insertSort(&$arr){
	if(!is_array($arr)){
		return false;
	}
	$len = count($arr);
	// 对应摸牌过程
	for($i=1;$i<$len;$i++){
		$item = $arr[$i];
		// 与手中已有的牌比较的过程
		for($j=$i;$j>0;$j--){
			if($arr[$j-1]>$item){
				$arr[$j] = $arr[$j-1];
			}else{
				break;
			}
		}
		$arr[$j] = $item;
	}
	return true;
}
```

上面是对应的实现，第一层循环是拿取一张新牌的过程(默认手中有张牌)，第二层循环是新牌与手中的牌比较的过程。

这个算法的时间复杂度和冒泡排序是一样的，最好的情况下(顺序)是O(n)，最坏的情况下(逆序)是O(n^2)。