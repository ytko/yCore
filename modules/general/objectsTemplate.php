<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class generalObjectsTemplateClass extends yTemplateClass {

// ----- HEAD -------------------------------------------------------------------------------------	
	function head() { ?>
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
input[type='submit'], input[type='password']
	{min-width:200px; border:solid 1px #848388; padding:5px;}
.button
	{width:200px; border:solid 1px #848388; text-align:center; padding:5px 0;}
select, input[type='text']
	{width:200px; border:solid 1px #848388; padding:5px;}
input.delete, input.clear
	{min-width:0; width:30px; height:30px}
</style>	
	<?php }	
	
// ----- BODY -------------------------------------------------------------------------------------	
	function body() {
		$_ = $this->model->get();
		
		self::header($_);

		if (is_array($_->objectList->items))
		foreach ($_->objectList->items as $i => &$item)
			self::loop($_, $item);
			
		self::footer($_);
	}

// ----- HEADER -----------------------------------------------------------------------------------
	function header(&$_) { ?>
<div style='float:left;'>
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
			</tr>
	<?php }	
	
// ----- LOOP -------------------------------------------------------------------------------------	
	function loop(&$_, &$item) { ?>
			<tr>
				<td><a href='<?php echo self::getURI($_->url, $_->get, array('oid' => $item->id)); ?>'><?php echo $item->key; ?></a></td>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->type; ?></td>
				<td><input type='submit' name='clearObject[<?php echo $item->id; ?>]' value='C' class='clear' /></td>
				<td><input type='submit' name='deleteObject[<?php echo $item->id; ?>]' value='X' class='delete' /></td>
			</tr>
	<?php }

// ----- FOOTER -----------------------------------------------------------------------------------
	function footer(&$_) { ?>
		</table>
	</form>
</div>

<div style='float:left;'>
	<strong><?php echo $_->objectList->item->key; ?></strong><br />
	<form name='test' method='post' action=''>
	<input type='hidden' name='oid' value='<?php echo $_->item->id; ?>' />
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
					   <option value='time'>Timestamp</option>					   
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
			</tr>

		<?php foreach ($_->fields as $key => $value): ?>
			<tr>
				<td><?php echo $key; ?></td>
				<td><?php echo $value->name; ?></td>
				<td><?php echo $value->type; ?></td>
				<td><?php echo self::objectsShowArray($value->ext); ?></td>						
				<td><input type='submit' name='deleteField[<?php echo $key; ?>]' value='X' class='delete' /></td>
			</tr>
		<?php endforeach; ?>
		</table>
	</form>
</div>
	<?php }

// ------------------------------------------------------------------------------------------------

// ----- SELF FUNCTIONS ---------------------------------------------------------------------------

	protected function objectsShowArray($arr) {
	
	if (!is_array($arr)) return;
		
	$result = "<table>";

	foreach ($arr as $key => $value)
		$result.= "<tr><td>$key</td><td>$value</td></tr>";

	$result.= "</table>";

	return($result);
}
	
} ?>