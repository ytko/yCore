<?php defined ('_YEXEC')  or  die();
	$result = "<ul class='level{$level}'>";

	foreach($trunk as $node) {
		$result.= "<li id='{$node->{$this->key}}'><a href='{$path}{$node->{$this->key}}'>{$node->{$this->value}}</a>";

		if(!empty($node->children))
			$result.= $this->render($node->children, $level+1, $path.'/'.$node->{$this->key});

		$result.= "</li>";
	}

	$result.= '</ul>';
?>