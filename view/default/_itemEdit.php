<?php defined ('_YEXEC')  or  die();

$this->body.=
"<form name='test' method='post' action=''>
	<table>
		<input type='hidden' name='_d[id]' value='{$_->item->id}' />
		<input type='hidden' name='oid' value='{$_->objectID}' />";
		foreach ($_->fields as $key => $value)
			$this->body.=
			"<tr>
				<td>{$value->name} ($key)</td>
				<td><input type='text' name='_d[$key]' value='{$_->item->$key}'></td>
			</tr>";
	$this->body.=
	"</table>
	<input type='submit' name='updateItem' value='Сохранить' /><br />
	<div class='button'><a href='".yHtml::getURI($_->url, $_->get, array('id' => NULL))."'>Создать</a></div>
</form>";
			
?>