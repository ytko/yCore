<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class objectTemplateClass extends yTemplateClass {
	public $object;
	
	public function setObject($object) {
		$this->object = $object;
		return $this;
	}
	
// ----- HEAD -------------------------------------------------------------------------------------	
	public function head() {
		return
<<<HEREDOC
<script type="text/javascript">
	$(document).ready(function(){
		$(".clear").click(function () { 
			return confirm('Очистить?'); 
		});

		$(".delete").click(function () { 
			return confirm('Удалить?'); 
		});
	});
</script>
<style type="text/css">
	table { margin:0 5px 5px 0; }
	table td { padding:5px; }
	input.alone { margin:5px 0; }
	#all { max-width: 100%; }
	input.delete { min-width:0; width:30px; height:30px }
</style>
HEREDOC;
	}

	// ------ FORMS -------------------------------------------------------------------------------
	
	public function form() {
		foreach ($this->object->fields as $field) {
			$value = htmlspecialchars(stripcslashes($this->object->values[0]->{$field->key}), ENT_QUOTES);
			$field->name = htmlspecialchars(stripcslashes($field->name), ENT_QUOTES);
			
			$result.= self::fieldInput($field, $value);
		}
		
		return "<form method='post' action=''>$result<input type='submit' value='Отправить'></form>";
	}
	
	static public function fieldInput($field, $value = NULL) {
			if (!isset($value)) $value = $field->value;
			
			if		($field->type == 'int')			return self::intInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'id')			return self::hiddenInput($field->key, $value);
			elseif	($field->type == 'string')		return self::stringInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'float')		return self::floatInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'currency')	return self::currencyInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'text')		return self::textInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'list')		return self::listInput($field->key, $field->values, $value, $field->name).'<br />';		
	}

	public function cat() {
		$pagination = $this->pagination();
		
		foreach ($this->object->values as $row) {
			$items.= $this->catItem($row);
		}
		return <<<HEREDOC
<div class="catalog">
	<div class="search">$search</div>
	<div class="pagination">$pagination</div>
	<div class="items">$items</div>
</div>
HEREDOC;
	}
	
	protected function catItem($row) {
		foreach($this->object->fields as $field) {
			$value = $row->{$field->key};
			$class = $field->key;
			$name = $field->name;
			$result.= "<a href='page?id={$row->id}' class='$class'>{$name}: $value; </a>";
		}
		return '<div>'.$result.'</div>';
	}
	
	protected function pagination($rad = 5) {
		return
			self::getPagination(
				$this->object->filters->page->value,
				$this->object->filters->page->rows,
				$this->object->rowsTotal,
				$rad);
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
	
	public function search() {
		if($this->object->filters) foreach($this->object->filters as $filter) {
			if($filter->scope == 'external' && $filter->type == 'field') {
				$field = $this->object->fields->{$filter->field};
				$result.= self::fieldInput($field);
			}
		}
		
		return "<form method='get' action=''>$result<input type='submit' value='Искать'></form>";
	}
}

?>