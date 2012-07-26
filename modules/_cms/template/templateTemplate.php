<?php defined ('_YEXEC')  or  die();

yCore::load('yTemplate');

class templateTemplate extends yTemplate {
	protected $content;
	
	function setContent($content) {
		$this->content = $content;
		return $this;
	}

// ----- HEAD -------------------------------------------------------------------------------------	
/*	function head() { 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title></title>
<script src='http://code.jquery.com/jquery-1.7.1.min.js' type="text/javascript"></script><?php 

		echo  $this->content->head;
		
?><meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head><?php
	 }
	
// ----- BODY -------------------------------------------------------------------------------------	
	function body() {
?>
<body>
<div style='float:left; width:100%; padding:5px; margin:5px 0 0 0; background-color:#cccccc'>
<a href='general'>Таблицы</a>
<a href='objects'>Объекты</a>
<a href='users'>Login</a>
</div><?php
	
	echo $this->content;
	
?></body></html><?php
	}*/
	 
	function get() {
		return
<<<HEREDOC
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title></title>
<script src='http://code.jquery.com/jquery-1.7.1.min.js' type="text/javascript"></script>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>

	<div>
		$this->content
	</div>

</body>
</html>
HEREDOC;
	}

// ------------------------------------------------------------------------------------------------
} ?>