图的最短路径问题是一类问题，其中最短既可以指经过的路径边最少，又可以指经过的路径权重和最小。对于经过的边最少这个含义，我们可以把它看成无权图的最短路径问题，也可以看成有权图的一种特殊情况，即所有边权重一致。

## 单源无权图最短路径

图的最短路径问题，最简单的一种情况就是无权图从一个节点出发，找到其它节点的最短路径。

它的基本思路是这样的：从起点开始，按照到起点的距离从小到大向外探索节点，新探索的节点到起点的距离为上一个节点到起点的距离+1。这里的按照到起点的距离和之前提到的广度优先搜索思路是一致的。


```php
function shortpath_unweighted($from=0){
	$dist = [];
	$path = [];
	$length = $this->length();
	for($i=0;$i<$length;$i++){
		$dist[$i] = -1;
		$path[$i] = -1;
	}
	$dist[$from] = 0;

	$queue = [];
	$node = $this->get_node_by_index($from);
	array_push($queue,$node);
	//  广度优先搜索
	while (!empty($queue)) {
		$node = array_shift($queue);
		$index = $node->get_index();
		if(!isset($this->_edges[$index])){
			continue;
		}
		foreach ($this->_edges[$index] as $key => $value) {
			// 新探索的节点到起点的距离为上一个节点```$index```到起点距离+1
			if($dist[$key]===-1){
				$dist[$key] = $dist[$index] + 1;
				$path[$key] = $index;
				array_push($queue,$this->get_node_by_index($key));
			}
		}
	}
	return [
		'dist'=>$dist,
		'path'=>$path,
	];
}
```

这里有两个数组```$dist```好```$path```，前者存放到起点的距离，后者存放最短路径上前一个元素的索引。这里的```$dist```初始化为-1，也可以初始化为正无穷或者负无穷，总之能表示距离未被探索即可。


## 单源有权图最短路径

解决这个问题的算法有个名字是**Dijkstra**，它的思路是这样的：从未找到最短路径的节点中找到距离起点最近的节点，这样这个新节点的最短路径就确定了，然后通知这个节点的未找到最短路径的相邻节点更新到起点的距离。



```php
private function _find_min_index_by_dist(&$dist,&$collected){
	$minIndex = -1;
	$minDist = INF;
	$length = $this->length();
	for($i=0;$i<$length;$i++){
		if(!$collected[$i]&&$dist[$i]<$minDist){
			$minIndex = $i;
			$minDist = $dist[$i];
		}
	}
	return $minIndex;
}
function shortpath_weighted($from=0){
	$dist = [];
	$path = [];
	$collected = [];
	$length = $this->length();
	// 初始化，到起点没有直接边的距离为无穷
	for($i=0;$i<$length;$i++){
		if(isset($this->_edges[$from][$i])){
			$dist[$i] = $this->_edges[$from][$i];	
			$path[$i] = $from;
		}else{
			$dist[$i] = INF;
			$path[$i] = -1;
		}
		$collected[$i] = false;
	}
	$dist[$from] = 0;
	$collected[$from] = true;


	while(true){
		// 从未找到最短路径的节点中找出距离最小的
		$index = $this->_find_min_index_by_dist($dist,$collected);
		if($index===-1){
			break;
		}
		// 标记该节点已找到最短路径
		$collected[$index] = true;
		if(!isset($this->_edges[$index])){
			continue;
		}
		// 通知相邻节点路程更新
		foreach ($this->_edges[$index] as $key => $value) {
			if($collected[$key]){
				continue;
			}
			if($dist[$index]+$this->_edges[$index][$key]<$dist[$key]){
				$dist[$key] = $dist[$index] + $this->_edges[$index][$key];
				$path[$key] = $index;
			}

		}
	}

	return [
		'dist'=>$dist,
		'path'=>$path,
	];
}
```

最开始我就说，单源无权图的最短路径问题是单源有权图的最短路径问题的一种特殊情况，我们比较一下这两个实现：在无权图中出队操作相当于有权图中找距离最小的节点，无权图中距离+1操作(可以看做权重均为1)对应有权图中更新距离操作。

在我的这个实现中，对应算法复杂度为O(n^2)，while循环执行N次，找最小距离节点用的是直接暴力遍历对应N，所以时间复杂度为O(n^2)。一个改进是找最小距离节点那一步使用最小堆。

## 多源有权图最短路径

有了单源有权图最短路径的解决方案，多源有权图最短路径似乎没什么难度，遍历一遍节点调用单源有权图解决方案就好了，这样时间复杂度为O(n^3)。

还有个改进方案被称为**Floyd**算法：

```php
function shortpath_multisource(){
	$dist = [];
	$path = [];
	$length = $this->length();
	// 初始化距离，到自己距离为0，没直接相连距离为INF
	for($i=0;$i<$length;$i++){
		$edges = isset($this->_edges[$i])?$this->_edges[$i]:[];
		for($j=0;$j<$length;$j++){
			if($i===$j){
				$dist[$i][$j] = 0;
			}else if(isset($edges[$j])){
				$dist[$i][$j] = $edges[$j];
			}else{
				$dist[$i][$j] = INF;
			}
			$path[$i][$j] = -1;
		}
	}

	for($k=0;$k<$length;$k++){
		for($i=0;$i<$length;$i++){
			for($j=0;$j<$length;$j++){
				if($dist[$i][$k]+$dist[$k][$j]<$dist[$i][$j]){
					$dist[$i][$j] = $dist[$i][$k] + $dist[$k][$j];
					$path[$i][$j] = $k;
				}
			}
		}
	}

	return [
		'dist'=>$dist,
		'path'=>$path
	];

}
```

可以看到这个算法时间复杂度也为O(n^3)，毕竟三个for循环套在一起。虽然也为O(n^3)，但比第一个方案优化一点。