基数排序和之前提及的各种排序算法思路完全不同。之前排序算法的是通过关键字的比较和移动，而基数排序不需要记录关键字的比较，同时通过若干次分类和收集实现的。

我所介绍的是最低位优先基数排序。它的思路是这样的：从低位到高位，依次进行一次分配，每次分配按照元素低n位的值，每次分配的结果作为下一次分配的原始数据，最后把数据收集起来。


```php
static private function _getDigit($num,$digit=1){
	for($i=0;$i<$digit;$i++){
		$rst = $num%10;
		$num = floor($num/10);
	}
	return $rst;
}

static private function _getNumLen($num){
	return strlen((string)$num);
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

static public function LSDRadixSort(&$arr){
	if(!is_array($arr)){
		return false;
	}
	$maxLen = 1;
	$bucket = self::_getBucket();
	for($i=0;$i<$maxLen;$i++){
		if($i>0){
			// 上一次的结果作为这次处理的依据
			$newBucket = self::_getBucket();
			$bucketLen = count($bucket);
			for($j=0;$j<$bucketLen;$j++){
				$bucketArr = $bucket[$j];
				$bucketArrLen = count($bucketArr);
				for($k=0;$k<$bucketArrLen;$k++){
					$item = $bucketArr[$k];
					$bucketId = self::_getDigit($item,$i+1);
					$newBucket[$bucketId][] = $item;
				}
			}
			$bucket = $newBucket;
		}else{
			// 第一次处理需要调整数据结构
			$arrLen = count($arr);
			for($j=0;$j<$arrLen;$j++){
				$item = $arr[$j];
				$itemLength = self::_getNumLen($item);
				if($itemLength>$maxLen){
					$maxLen = $itemLength;
				}
				$bucketId = self::_getDigit($item,1);
				$bucket[$bucketId][] = $item;
			}
		}
	}

	// 最后的合并工作
	$bucketLen = count($bucket);
	$index = 0;
	for($i=0;$i<$bucketLen;$i++){
		$bucketArr = $bucket[$i];
		$bucketArrLen = count($bucketArr);
		for($j=0;$j<$bucketArrLen;$j++){
			$arr[$index] = $bucketArr[$j];
			$index++;
		}
	}
	return true;
}
```