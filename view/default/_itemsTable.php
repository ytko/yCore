<?php defined ('_YEXEC')  or  die();

// pagination

$this->body.=
"<div>";
	$this->getPage('_pagination', $_);
$this->body.=
"</div>";

// Заголовок таблицы
$this->body.=
"<div>
	<form name='test' method='post' action=''>
		<input type='hidden' name='oid' value='{$_->objectID}' />
		<table>
			<tr>
				<td>id</td>";
				foreach ($_->fields as $key => $value) $this->body.=
				"<td>{$value->name} ($key)</td>";
				
				$this->body.=
				"<td></td>
			</tr>";
			
			// Содержимое таблицы
			foreach($_->items as $i => $item) {
			$this->body.=
			"<tr>
				<td><a href='".yHtml::getURI($_->url, $_->get, array('id' => $item->id))."'>{$item->id}</a></td>";
				foreach ($_->fields as $key => $value) $this->body.=
				"<td>{$item->$key}</td>";
				
				$this->body.=
				"<td><input type='submit' name='deleteItem[{$item->id}]' value='X' class='delete' /></td>
			</tr>";
			}
		
		// Конец таблицы
		$this->body.=
		"</table>
	</form>
</div>

<div>";
	$this->getPage('_pagination', $_);
$this->body.=
"</div>";

?>