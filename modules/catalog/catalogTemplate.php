<?php defined ('_YEXEC')  or  die();

yCore::includeTemplate('object');

class catalogTemplate extends objectTemplate {
	public function catItem($row) {
		$category = $this->object->fields->category->values[$row->category];
		return
<<<HEREDOC
<div class='catItem'>
	<div class='price'>Цена: <span>{$row->price}</span> руб.</div>
	<div class='name'><a href='page?id={$row->id}'>{$row->name}</a></div>
	<div class='description'>{$row->description}</div>
	<div class='category'>Категория: {$category}</div>
</div>
HEREDOC;
	}
}

?>