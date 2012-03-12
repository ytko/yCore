<?php defined ('_YEXEC')  or  die();

class mainTemplate {

// ----- HEAD -------------------------------------------------------------------------------------	
	function head(&$_) { ?>

	<?php }	
	
// ----- BODY -------------------------------------------------------------------------------------	
	function body(&$_) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<div style='float:left; width:100%; padding:5px; margin:5px 0 0 0; background-color:#cccccc'>
<a href='index.php'>Таблицы</a>
<a href='index.php?mod=objects'>Объекты</a>
<a href='index.php?mod=users'>Login</a>
</div>
	<?php echo $_; ?>
</body></html>
	<?php }	

// ------------------------------------------------------------------------------------------------
} ?>