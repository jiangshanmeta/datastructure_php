霍夫曼树是一类特殊的二叉树，它的特征是带权路径长度最小(带权路径长度的概念请自行学习)。霍夫曼树又被称为最优二叉树。

我们这里实现霍夫曼树依赖于堆(具体来说是最小堆)。

霍夫曼树的构成按照以下方法：

* 根据给定的n个权重构成含有n棵二叉树的集合，集合中的每个二叉树均只有一个根节点。
* 从集合中选取两棵权重最低的二叉树作为左右子树构建一棵新的二叉树，新的二叉树权重为两颗子树根节点权重之和
* 从集合中删除那两棵二叉树，并把新的二叉树加入到集合中
* 重复第二步和第三步，直道集合中只含有一棵树，则该树为霍夫曼树。

其中那个二叉树的集合我们使用最小堆实现。


```php
class Hoffmann_tree{
	function __construct(){
		
	}

	function genTree($arr){
		$minHeap = new MinHeap();
		$minHeap->compareFunc = function($data){
			return $data['weight'];
		};
		foreach ($arr as $value) {
			$minHeap->insert($value);
		}
		$size = count($arr);
		for($i=1;$i<$size;$i++){
			$node = [];
			$node['left'] = $minHeap->delete();
			$node['right'] = $minHeap->delete();
			$node['weight'] = $node['left']['weight'] + $node['right']['weight'];
			$minHeap->insert($node);
		}
		return $minHeap->delete();
	}
}
$huffmann = new Hoffmann_tree();
$data = [
	['data'=>'a','weight'=>7],
	['data'=>'b','weight'=>5],
	['data'=>'c','weight'=>2],
	['data'=>'d','weight'=>4],
];
$rst = $huffmann->genTree($data);
```

以上就是按照上面所说的步骤对应的一种实现。

霍夫曼树还有一些特征：

* 霍夫曼树中没有度为1的节点
* n个叶节点的霍夫曼树总共有2n-1个节点
* 霍夫曼树左右节点交换后依然是霍夫曼树
* 同一组权重可以对应多个霍夫曼树，但是其带权路径长度一致。