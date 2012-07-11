<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class objectTemplateClass extends yTemplateClass {
	public $object, $model;
	
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

	public function form() {
		foreach ($this->object->fields as $field) {
			$value = htmlspecialchars(stripcslashes($this->object->values[0]->{$field->key}), ENT_QUOTES);
			$name = htmlspecialchars(stripcslashes($field->name), ENT_QUOTES);
			
			if		($field->type == 'int')			$result.= "{$name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'id')			$result.= "<input type='hidden' name='{$field->key}' value='$value' />";
			elseif	($field->type == 'string')		$result.= "{$name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'float')		$result.= "{$name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'currency')	$result.= "{$name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'text')		$result.= "{$name}:<br /><textarea name='{$field->key}'>$value</textarea><br />";
			elseif	($field->type == 'list') {
				$result.= "{$name}:<br /><select name='{$field->key}'>";
				if(isset($field->values)) foreach ($field->values as $iKey => $iName) {
					$iKey = htmlspecialchars($iKey, ENT_QUOTES);
					$iName = htmlspecialchars($iName, ENT_QUOTES);
					$iSelected = ($iKey == $value) ? ' selected' : NULL;
					$result.= "<option value='$iKey'{$iSelected}>$iName</option>";
				}
				$result.= "</select>";
			}
		}
		
		return "<form method='post' action=''>$result<input type='submit' value='Отправить'></form>";
	}
	
	public function cat() {
		foreach ($this->object->values as $row) {
			$result.= $this->catItem($row);
		}
		return $result;
	}
	
	public function catItem($row) {
		foreach($this->object->fields as $field) {
			$value = $row->{$field->key};
			$class = $field->key;
			$name = $field->name;
			$result.= "<a href='page?id={$row->id}' class='$class'>{$name}: $value; </a>";
		}
		return '<div>'.$result.'</div>';
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