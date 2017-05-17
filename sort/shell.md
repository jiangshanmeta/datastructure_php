希尔排序是对插入排序的优化。它的基本思路是先做一些```$skip```间隔的插入排序，将带排序序列的有序性提高一些，最后再通过一个一间隔排序(即标准的插入排序)完成最终排序工作。


```php
static public function shellSort(&$arr){
	if(!is_array($arr)){
		return false;
	}
	$len = count($arr);
	for($skip=floor($len/2);$skip>0;$skip=floor($skip/2)){
		for($i=$skip;$i<$len;$i++){
			$item = $arr[$i];
			for($j=$i;$j>=$skip;$j-=$skip){
				if($arr[$j-$skip]>$item){
					$arr[$j] = $arr[$j-$skip];
				}else{
					break;
				}
			}
			$arr[$j] = $item;
		}
	}
	return true;
}
```

这类算法要求```$skip```递减，上面给出的实现```$skip```是通过折半向下取整得到。内部的两个循环对应的是```$skip```间隔的插入排序，最外层的循环控制```$skip```的生成及多次```$skip```间隔插入循环调用。