<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class customformsTemplateClass extends yTemplateClass {

// ----- HEAD -------------------------------------------------------------------------------------	
	function head() {
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

	function form($object) {
		foreach ($object->fields as $field) {
			$value = $object->values[0]->{$field->key};
			if		($field->type == 'int')		$result.= "{$field->name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'id')		$result.= "<input type='hidden' name='{$field->key}' value='$value' />";
			elseif	($field->type == 'string')	$result.= "{$field->name}:<br /><input type='text' name='{$field->key}' value='$value' /><br />";
			elseif	($field->type == 'text')	$result.= "{$field->name}:<br /><textarea name='{$field->key}'>$value</textarea><br />";
		}
		
		return "<form method='post' action=''>$result<input type='submit' value='Отправить'></form>";
	}
}

?>

