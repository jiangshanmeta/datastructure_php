线性结构的顺序表示指的是用一组地址连续的存储单元依次存储线性结构的数据元素。在PHP中我们可以通过数组模拟这一特征。

```php
class Linear_seq{
	protected $_cache = [];
	function __construct(){

	}

}
```

为了聚合相关操作，我使用一个*Linear_seq*类，其中的```$_cache```是顺序存储体。

定义了最基本的结构，我们开始实现操作。

第一个操作的获取线性结构的长度。

```php
function length(){
	return count($this->_cache);
}
```

第二个操作是线性结构是否为空

```php
function isEmpty(){
	return empty($this->_cache);
}
```

上面两个操作没什么可说的，第三个操作是向顺序结构中插入元素

```php
function insert($value,$index){
	$length = $this->length();
	if($index<0 || $index>$length){
		return false;
	}
	for($i=$length;$i>$index;$i--){
		$this->_cache[$i] = $this->_cache[$i-1];
	}
	$this->_cache[$index] = $value;
	return true;
}
```

插入操作第一个参数是要插入的节点，第二个参数是要插入的位置，这个位置是从零开始的。首先校验插入位置的合法性，如果位置不合法返回false表示插入失败。然后进行插入操作，这里首先要做的是把第```$index```的位置空出来，对应for循环，注意这里挪位置的顺序，空出来了位置，我们才能把新元素插进来。你可能会说这里直接用```array_splice```不就好了吗，何必这么折腾。虽然PHP为我们提供了强大的工具，但是一些基础的操作还是要会。插入成功后返回true表示成功。

与插入相关的另外两个操作是```push```和```unshift```，有过编程经验的对这两个操作应该不陌生。

```php
function push($value){
	return $this->insert($value,$this->length());
}

function unshift($value){
	return $this->insert($value,0);
}
```

push就是插入到最后，unshift就是插入到最前面。


插入操作完成后，我们要做的是删除操作。

```php
function delete($index){
	$length = $this->length();
	if($index<0 || $index>$length-1){
		return NULL;
	}
	$val = $this->_cache[$index];
	for($i=$index;$i<$length-1;$i++){
		$this->_cache[$i] = $this->_cache[$i+1];
	}
	unset($this->_cache[$length-1]);
	return $val;
}
```

删除操作传入的是要删除的序号，在判断了序号合法性之后，我们把该序号以后的元素向前挪，最后返回被删除的元素。注意这里挪动的顺序和上面插入时挪动的顺序的差异。

与删除有关的操作是```pop```和```shift```

```php
function pop(){
	return $this->delete($this->length()-1);
}

function shift(){
	return $this->delete(0);
}
```

可以看出，pop是删除最后一个元素，shift是删除第一个元素。

增删解决以后，我们要做的是查找。有两种查找类型，一种是根据序号找到元素，另一种根据元素找序号。

```php
function findEleByIndex($index){
	$length = $this->length();
	if($index<0 || $index>$length-1){
		return NULL;
	}
	return $this->_cache[$index];
}

function findIndexByEle($value){
	$length = $this->length();
	for($i=0;$i<$length;$i++){
		if($value === $this->_cache[$i]){
			return $i;
		}
	}
	return -1;
}
```

对于顺序存储的线性结构来说，根据序号找元素很容易实现，不做过多说明，根据元素找序号需要遍历，找到第一个就返回序号，遍历完没找到元素则返回-1。

作为一名CURD工程师，还有一个改操作需要实现。

```php
function update($newValue,$index){
	$length = $this->length();
	if($index<0 || $index>$length-1){
		return false;
	}
	$this->_cache[$index] = $newValue;
	return true;
}
```

到这里一个顺序存储的线性结构的操作基本上就结束了，下面的操作是两个线性结构之间的操作。

第一个是将第二个线性结构合并到第一个线性结构中

```php
function merge($seq){
	if(!($seq instanceof Linear_seq)){
		return false;
	}
	$length = $seq->length();
	for($i=0;$i<$length;$i++){
		$this->push($seq->findEleByIndex($i));
	}
	return true;
}
```

算是上面操作的一个综合运用吧，思路就是把第二个线性结构每一个元素都加入到第一个线性结构中

第二个是求两个线性结构元素的并集。

```php
static function union($seq1,$seq2){
	$seq = new Linear_seq();
	if(!($seq1 instanceof Linear_seq) || !($seq2 instanceof Linear_seq)){
		return $seq;
	}
	$length1 = $seq1->length();
	for($i=0;$i<$length1;$i++){
		$seq->push($seq1->findEleByIndex($i));
	}

	$length2 = $seq2->length();
	for($i=0;$i<$length2;$i++){
		$value = $seq2->findEleByIndex($i);
		if($seq->findIndexByEle($value)===-1){
			$seq->push($value);
		}
	}

	return $seq;
}
```

并集操作返回的是一个新的线性结构，并集操作的思路也不难，首先是把第一个线性结构的元素复制，然后把第二个线性结构特有的元素添加进去。



```php
static function intersection($seq1,$seq2){
	$seq = new Linear_seq();
	if(!($seq1 instanceof Linear_seq) || !($seq2 instanceof Linear_seq)){
		return $seq;
	}

	$length = $seq1->length();
	for($i=0;$i<$length;$i++){
		$value = $seq1->findEleByIndex($i);
		if($seq2->findIndexByEle($value)!==-1){
			$seq->push($value);
		}
	}
	return $seq;
}
```

有了并集操作的例子，交集操作实现起来也不复杂，不多说了。