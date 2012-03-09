<?php defined ('_YEXEC')  or  die();

function objectsShowArray($arr) {
	$result = "<table>";
	foreach ($arr as $key => $value)
		$result.= "<tr><td>$key</td><td>$value</td></tr>";

	$result.= "</table>";

	return($result);
}

// OBJECTS -------------------------------

$this->getPage('_style', $_);
$this->getPage('_menu', $_);

// HEADER

$this->body.= 
"<div style='float:left;'>
	<form name='test' method='post' action=''>
		<table>
			<tr>
				<td>keyword:</td>
				<td><input type='text' name='objectKey' value='' /></td>
			</tr>
			<tr>
				<td>name:</td>
				<td><input type='text' name='objectName' value='' /></td>
			</tr>
			<tr>
				<td>type:</td>
				<td><input type='text' name='objectType' value='' /></td>
			</tr>
		</table>
		<input type='submit' name='addObject' value='Добавить объект' class='button alone' />
		<table>
			<tr>
				<td>key</td>
				<td>name</td>
				<td>type</td>
				<td>очистить</td>
				<td>удалить</td>
			</tr>";
		
	// LOOP
		foreach($_->objectList->items as $i => $item) {
			$this->body.=
			"<tr>
				<td><a href='".yHtml::getURI($_->url, $_->get, array('oid' => $item->id))."'>{$item->key}</a></td>
				<td>{$item->name}</td>
				<td>{$item->type}</td>
				<td><input type='submit' name='clearObject[{$item->id}]' value='C' class='clear' /></td>
				<td><input type='submit' name='deleteObject[{$item->id}]' value='X' class='delete' /></td>
			</tr>";
		}
		
	// FOOTER
		$this->body.=
		"</table>
	</form>
</div>";

// FIELDS --------------------------------

$this->body.= 
"<div style='float:left;'>
	<strong>{$_->objectList->item->key}</strong><br />
	<form name='test' method='post' action=''>
	<input type='hidden' name='oid' value='{$_->item->id}' />
		<table>
			<tr>
				<td>keyword:</td>
				<td><input type='text' name='field[key]' value='' /></td>
			</tr>
			<tr>
				<td>name:</td>
				<td><input type='text' name='field[name]' value='' /></td>
			</tr>
		
			<tr style='background-color:#999;'>
				<td>type</td>
				<td>
					<select name='fieldExt[type]'>
					   <option value=''></option>
					   <option value='link'>Связь с полем</option>
					</select>
				</td>
			</tr>
			<tr style='background-color:#999;'>
				<td>table (link)</td>
				<td><input type='text' name='fieldExt[table]' value='' /></td>
			</tr>
			<tr style='background-color:#999;'>
				<td>field (link)</td>
				<td><input type='text' name='fieldExt[field]' value='' /></td>
			</tr>
			<tr>
				<td>type</td>
				<td>
					<select name='field[type]'>
					   <option value='text'>Текстовое</option>
					   <option value='integer'>Целое число</option>
					   <option value='float'>Число с плавающей точкой</option>
					</select>
				</td>
			</tr>
		</table>
		
		<input type='submit' name='addField' value='Добавить поле' class='button alone' />
		<br />

		Поля:
		<table>
			<tr>
				<td>Key</td>
				<td>Name</td>
				<td>Type</td>
				<td>Ext</td>
				<td></td>
			</tr>";

		foreach ($_->fields as $key => $value) 
			$this->body.=
			"<tr>
				<td>$key</td>
				<td>{$value->name}</td>
				<td>{$value->type}</td>
				<td>".objectsShowArray($value->ext)."</td>						
				<td><input type='submit' name='deleteField[$key]' value='X' class='delete' /></td>
			</tr>";
	
		$this->body.= 
		"</table>
	</form>
</div>";

?>