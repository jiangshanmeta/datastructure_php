虽然在php中有各种各样的排序函数，但是掌握一些基本的排序算法也是需要的。这一章就用php实现一些基本的排序算法。

为了管理各种排序算法，我取了一个```Sort```类，具体的排序算法都以静态方法的形式组织。先约定排序结果是从小到大。

下面是一个在多个排序算法中用到的小函数，作用是数组中两元素交换

```php
static private function _swap(&$arr,$index1,$index2){
	if($index1!==$index2){
		$temp = $arr[$index1];
		$arr[$index1] = $arr[$index2];
		$arr[$index2] = $temp;            
	}
}
```

可以在[github查看最终的实现代码](https://github.com/jiangshanmeta/datastructure_php/blob/master/sort/sort.php)