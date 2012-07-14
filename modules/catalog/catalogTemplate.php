<?php defined ('_YEXEC')  or  die();

yFactory::includeTemplate('object');

class catalogTemplateClass extends objectTemplateClass {
	public function catItem($row) {
		return
<<<HEREDOC
<div class='catItem'>
	<div class='price'>Цена: <span>{$row->price}</span> руб.</div>
	<div class='name'><a href='page?id={$row->id}'>{$row->name}</a></div>
	<div class='description'>{$row->description}</div>
	<div class='category'>Категория: {$row->category}</div>
</div>
HEREDOC;
		//"<div class='catItem'><a href='page?id={$row->id}'>$result</a></div>";
	}
}

?>