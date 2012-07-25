<?php defined ('_YEXEC')  or  die();

yCore::includeTemplate();

class objectTemplate extends yTemplate {
	public $object, $mode;
	
	public function setObject($object) {
		$this->object = $object;
		return $this;
	}
	
	public function setMode($mode) {
		$this->mode = $mode;
		return $this;
	}
	
// ----- HEAD ------------------------------------------------------------------
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

// ------ FORMS ----------------------------------------------------------------

	static public function fieldInput($field, $value = NULL) {
			if (!isset($value)) $value = $field->value;
			
			if		($field->type == 'int')			return self::intInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'id')			return self::hiddenInput($field->key, $value);
			elseif	($field->type == 'string')		return self::stringInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'float')		return self::floatInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'currency')	return self::currencyInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'text')		return self::textInput($field->key, $value, $field->name).'<br />';
			elseif	($field->type == 'list')		return self::listInput($field->key, $field->values, $field->name, $value).'<br />';		
	}

	public function form() {
		foreach ($this->object->fields as $field) {
			$value = htmlspecialchars(stripcslashes($this->object->values[0]->{$field->key}), ENT_QUOTES);
			$field->name = htmlspecialchars(stripcslashes($field->name), ENT_QUOTES);
			
			$result.= self::fieldInput($field, $value);
		}
		
		return "<form method='post' action=''>$result<input type='submit' value='Отправить'></form>";
	}

	public function search($filters = NULL) {
		$filters = $filters ? $filters : $this->object->filters;
		if($filters) foreach($filters as $filter) {
			if($filter->show && $filter->type == 'field') { //maybe: $filter->scope == 'external'
				$value = htmlspecialchars(stripcslashes($filter->value), ENT_QUOTES);
				$field = $this->object->fields->{$filter->field};
				$result.= self::fieldInput($field, $value);
			}
		}
		
		return "<form method='get' action=''>$result<input type='submit' value='Искать'></form>";
	}

// ---- Catalog View -----------------------------------------------------------

	public function cat() {
		$pagination = $this->pagination();

		// rows
		foreach ($this->object->values as $row)
			$items.= $this->catItem($row);

		//search
		$search = $this->search();

		// add button
		$add = ($this->mode == 'admin') ? "<a href='add'>Добавить</a>" : NULL;
		
		// filters
		$url = '';
		$query = $_GET; //TODO: connect with controller!!!
		
		foreach ($this->object->filters as $filter)
			if($filter->type == 'order' || $filter->type == 'sort')
				$order =
					$this->object->fields->{$filter->field}->name.': '.
					"<a href='".self::getURI($url, $query, array($filter->field => 'asc'))."' title='по возрастанию'>&#9650;</a> ".
					"<a href='".self::getURI($url, $query, array($filter->field => 'desc'))."' title='по убыванию'>&#9660;</a>";

		return
"<div class='catalog'>".
	($search ? "<div class='search'>$search</div>" : NULL).
	($pagination ? "<div class='pagination'>$pagination</div>" : NULL).
	($order ? "<div class='order'>$order</div>" : NULL).
	"<div class='items'>$items</div>".
	($pagination ? "<div class='pagination'>$pagination</div>" : NULL).
	($add ? "<div class='add'>$add</div>" : NULL).
"</div>";
	}

	protected function catItem($row) {
		foreach($this->object->fields as $field) {
			$value = $row->{$field->key};
			$class = $field->key;
			$name = $field->name;
			
			if($field->type == 'list')
				$value = $field->values[$value];
			
			if($value)
				$result.= "<a href='page?id={$row->id}' class='$class'>{$name}: $value; </a>";
		}
		return '<div>'.$result.'</div>';
	}

	protected function pagination($rad = 5) { //TODO: find by key
		if ($this->object->filters->page->show)
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
}

?>