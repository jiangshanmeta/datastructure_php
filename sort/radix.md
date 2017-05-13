基数排序和之前提及的各种排序算法思路完全不同。之前排序算法的是通过关键字的比较和移动，而基数排序不需要记录关键字的比较，同时通过若干次分类和收集实现的。

我所介绍的是最低位优先基数排序。它的思路是这样的：从低位到高位，依次进行一次分配，每次分配按照元素低n位的值，每次分配的结果作为下一次分配的原始数据，最后把数据收集起来。


```php
// 获取数字低n位
static private function _getDigit($num,$digit=1){
	for($i=0;$i<$digit;$i++){
		$rst = $num%10;
		$num = floor($num/10);
	}
	return $rst;
}

static private function _getNumLen($num){
	$num = (string)$num;
	return strlen($num);
}

static private function _getBucket(){
	return [
		0=>[],
		1=>[],
		2=>[],
		3=>[],
		4=>[],
		5=>[],
		6=>[],
		7=>[],
		8=>[],
		9=>[],
	];
}

static function LSDRadixSort($arr){
	if(!is_array($arr)){
		return $arr;
	}
	$maxLen = 1;
	$bucket = self::_getBucket();
	// 从低位到高位，$maxLen(最大的位数)需要在第一次循环后才能确定
	for($i=0;$i<$maxLen;$i++){
		if($i>0){
			$newBucket = self::_getBucket();
			$bucketLen = count($bucket);
			for($j=0;$j<$bucketLen;$j++){
				$bucketItemArr = $bucket[$j];
				$bucketItemArrLen = count($bucketItemArr);
				for($k=0;$k<$bucketItemArrLen;$k++){
					$item = $bucketItemArr[$k];
					// 根据低n位的值分配
					$bucketId = self::_getDigit($item,$i+1);
					$newBucket[$bucketId][] = $item;
				}
			}
			$bucket = $newBucket;
		}else{
			// 第一次分配特殊处理，要把原始数据结构调整为_getBucket返回的数据结构
			$arrLen = count($arr);
			for($j=0;$j<$arrLen;$j++){
				$item = $arr[$j];
				$itemLength = self::_getNumLen($item);
				if($itemLength>$maxLen){
					$maxLen = $itemLength;
				}
				$bucketId = self::_getDigit($item,$i+1);
				$bucket[$bucketId][] = $item;
			}
		}
	}

	$rst = [];
	// 最后的收集工作
	$bucketLen = count($bucket);
	for($i=0;$i<$bucketLen;$i++){
		$bucketItemArr = $bucket[$i];
		$bucketItemArrLen = count($bucketItemArr);
		for($j=0;$j<$bucketItemArrLen;$j++){
			$rst[] = $bucketItemArr[$j];
		}
	}
	return $rst;
}
```