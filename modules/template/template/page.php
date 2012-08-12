<?php defined ('_YEXEC')  or  die(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title></title>
<script src='http://code.jquery.com/jquery-1.7.1.min.js' type="text/javascript"></script>
<?php
	echo $this->renderScript();
	echo $this->renderCss();
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
	<div class="menu">
		<?php echo $this->menu ?>
	</div>
	<div class="container content">
		<?php echo $this->content ?>
	</div>
	<div class="footer">

	</div>
</body>
</html>