<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate('object');

class catalogTemplateClass extends objectTemplateClass {
	public function cat() {
		$pagination = $this->pagination();
		
		foreach ($this->object->values as $row) {
			$items.= $this->catItem($row);
		}
		
		$search = $this->search();
	
		return <<<HEREDOC

<div class="catalog">
	<div class="search">$search</div>
	<div class="pagination">$pagination</div>
	<div class="items">$items</div>
	<div class="pagination">$pagination</div>
</div>
HEREDOC;
	}
	
	public function catItem($row) {
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