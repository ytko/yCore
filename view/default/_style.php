<?php defined ('_YEXEC')  or  die();

$this->body.=
"<script src='/components/com_ncat/view/_js/jquery-1.7.1.min.js'></script>
<script>
	$(document).ready(function(){
	    $(\".clear\").click(function () { 
			return confirm('Очистить?'); 
	    });
	    
	    $(\".delete\").click(function () { 
			return confirm('Удалить?'); 
	    });
	});
</script>
<style>
	table {
		margin:0 5px 5px 0;
	}
	table td {
		padding:5px;
	}
	input.alone {
		margin:5px 0;
	}
	
	#all { 
		max-width: 100%;
	}
</style>

<style>
input[type='submit'], input[type='password']
	{min-width:200px; border:solid 1px #848388; padding:5px;}
.button
	{width:200px; border:solid 1px #848388; text-align:center; padding:5px 0;}
select, input[type='text']
	{width:200px; border:solid 1px #848388; padding:5px;}
input.delete, input.clear
	{min-width:0; width:30px; height:30px}
</style>";