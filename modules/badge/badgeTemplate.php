<?php defined ('_YEXEC')  or  die();

yFactory::linkTemplate();

class badgeTemplateClass extends yTemplateClass {

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

	function body() {
		$result = 
<<<HEREDOC
<style>
	.badges .hidden {display:none;}
	.next {width:30px;}
</style>
<script>
	$(document).ready(function(){
		$(".badges .next").click(function(){
			$(".badges > .hidden:first").removeClass("hidden");
			if(!$(".badges > div").is(".hidden")) {
				$(".badges .next").addClass("hidden");
			}
		});
	});
</script>
Заказать бейджи:<div class="nc_item">&nbsp;</div>
HEREDOC;
		for($i = 0; $i < 5; $i++) {
			$class = ($i > 0) ? ' class="hidden"' : NULL;
			$result.=
<<<HEREDOC
	<div $class>
		<div class="nc_item">&nbsp;</div>
		<div class="nc_item">Фамилия:<input name="badges[$i][lastName]" type="text" maxlength="255" size="50" value=""></div>
		<div class="nc_item">Имя:<input name="badges[$i][firstName]" type="text" maxlength="255" size="50" value=""></div>
		<div class="nc_item">Отчество:<input name="badges[$i][midName]" type="text" maxlength="255" size="50" value=""></div>
		<div class="nc_item">Должность:<input name="badges[$i][position]" type="text" maxlength="255" size="50" value=""></div>
	</div>
HEREDOC;
		}
		
		return "<div class='badges'>$result".
<<<HEREDOC
		<div class="nc_item">&nbsp;</div>
		<div class="next button-1">
			<span class="txt">+</span><span class="bg-button"></span>
		</div></div>
HEREDOC;

	}
}

?>

