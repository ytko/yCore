<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate('object');

class catalogTemplateClass extends objectTemplateClass {
	public function cat() {
		$result =
<<<'NEWDOC'
<style>

body {
	background-color:#eee;
	margin:0px;
}

.header {
	position:relative;
	margin:0 0 0 50%;
	left:-480px;
	width:960px;
	
	border-radius:0 0 6px 6px;
	
	display:inline-block;
	margin-bottom:30px;
	margin-top:0px;
	padding:10px;
	background-color:#fff;
}

.catalog {
	position:relative;
	margin:0 0 0 50%;
	left:-250px;
	width:500px;
}

.catItem {
	border-radius:4px;
	padding:10px;
	margin:10px 0;
	
	background-color:#fff;
	font-family: "arial", "tahoma", "verdana";
	font-size: 12px;
}

.catItem .price {
	float:right;
	color: #999;
}

.catItem .price span {
	color: #F7941C;
	font-size: 16px;
	font-weight: 700;
}

.catItem .description {
	color: #666;
	background-color:#eee;
	margin:10px 0;
	padding:10px;
	border-radius:4px;
}

.catItem .category {
	width:100%;
	text-align:right;
	font-size: 10px;
}

</style>


<div class='header'>
<div class="logo">
    <a href="/">
        <img alt="Интернет магазин ёGenius (yourgenius.ru)" align="left" border="0" width="150" height="80" title="Интернет магазин ёGenius (yourgenius.ru)" src="/files/images/logo.png">
    </a>
</div>
<div style="font-family:actor; display:inline-block; float:right; margin:10px 0 5px 0;">(812) <span style="font-size:1.8em; color:#f60;">987 1626</span></div>
</div>
<div class='catalog'>
NEWDOC;
		foreach ($this->object->values as $row) {
			$result.= $this->catItem($row);
		}
		return "$result</div>";
	}
	
	public function catItem($row) {
		$row->description = iconv('CP1251', 'UTF-8', $row->description);
		$row->category = iconv('CP1251', 'UTF-8', $row->category);
		$row->name = iconv('CP1251', 'UTF-8', $row->name);
		
		return
<<<HEREDOC
<div class='catItem'>
	<div class='price'>Цена: <span>{$row->price}</span> руб.</div>
	<div class='name'>{$row->name}</div>
	<div class='description'>{$row->description}</div>
	<div class='category'>Категория: {$row->category}</div>
</div>
HEREDOC;
		//"<div class='catItem'><a href='page?id={$row->id}'>$result</a></div>";
	}
	
	public function page() {
		foreach($this->object->fields as $field) {
			$value = $this->object->values[0]->{$field->key};
			$class = $field->key;
			$name = $field->name;
			$result.= "<span class='$class'>{$name}: $value; </span>";
		}
		return $result;
	}
}

?>