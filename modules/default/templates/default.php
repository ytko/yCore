<?php defined ('_YEXEC')  or  die();

class defaultTemplate {

// ----- HEAD -------------------------------------------------------------------------------------	
	function head(&$_) { ?>
<script src='/components/com_ncat/view/_js/jquery-1.7.1.min.js'></script>
<script>
	$(document).ready(function(){
		$(".clear").click(function () { 
			return confirm('Очистить?'); 
		});

		$(".delete").click(function () { 
			return confirm('Удалить?'); 
		});
	});
</script>
<style>
	table { margin:0 5px 5px 0; }
	table td { padding:5px; }
	input.alone { margin:5px 0; }
	#all { max-width: 100%; }
	input.delete { min-width:0; width:30px; height:30px }
</style>		
	<?php }	
	
// ----- BODY -------------------------------------------------------------------------------------	
	function body(&$_) {
		self::header($_);

		if (is_array($_->items))
		foreach ($_->items as &$item)
			self::loop($_, $item);
			
		self::footer($_);
	}

// ----- HEADER -----------------------------------------------------------------------------------
	function header(&$_) { ?>

<div style='float:right; background-color:#eeeeee'>
<?php foreach($_->objectList->items as $i => $item): ?>
	<div>
		<a href='<?php echo yHtml::getURI($_->url, array('oid'=>$item->id)); ?>'><?php echo $item->name ?> (<?php echo $item->key ?>)</a>
	</div>
<?php endforeach; ?>
</div>
	
<form name='test' method='post' action=''>
	<input type='hidden' name='_d[id]' value='<?php echo $_->item->id; ?>' />
	<input type='hidden' name='oid' value='<?php echo $_->objectID; ?>' />
	<table>
	<?php foreach ($_->fields as $key => $value): ?>
		<tr>
			<td><?php echo $value->name ?> (<?php echo $key; ?>)</td>
			<td><input type='text' name='_d[<?php echo $key; ?>]' value='<?php echo $_->item->$key; ?>'></td>
		</tr>
	<?php endforeach; ?>
	</table>
	<input type='submit' name='updateItem' value='Сохранить' /><br />
	<div class='button'><a href='<?php echo yHtml::getURI($_->url, $_->get, array('id' => NULL)); ?>'>Создать</a></div>
</form>
<div>
	<form name='test' method='post' action=''>
		<input type='hidden' name='oid' value='<?php echo $_->objectID; ?>' />
		<table>
			<tr>
				<td>id</td>
				
			<?php foreach ($_->fields as $key => $value): ?>
				<td><?php echo $value->name; ?></td>
			<?php endforeach; ?>
				<td></td>
								
			</tr>
	<?php }	
	
// ----- LOOP -------------------------------------------------------------------------------------	
	function loop(&$_, &$item) { ?>
			<tr>
				<td><a href='<?php echo yHtml::getURI($_->url, $_->get, array('id' => $item->id)); ?>'><?php echo $item->id; ?></a>
				</td>
				
			<?php foreach ($_->fields as $key => $value): ?>
				<td><?php echo $item->$key; ?></td>
			<?php endforeach; ?>
				<td><input type='submit' name='deleteItem[<?php echo $item->id; ?>]' value='X' class='delete' /></td>
			
			</tr>
	<?php }

// ----- FOOTER -----------------------------------------------------------------------------------
	function footer(&$_) { ?>
		</table>
	</form>
</div>
	<?php }

// ------------------------------------------------------------------------------------------------
} ?>