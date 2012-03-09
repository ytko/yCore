<?php defined ('_YEXEC')  or  die();

$this->body.= 
"<div style='float:right; background-color:#eeeeee'>";
foreach($_->items as $i => $item) {
	$this->body.=
	"<div><a href='".yHtml::getURI($_->url, $_->get, $item->uri)."'>{$item->name} ({$item->key})</a></div>";
}
$this->body.= 
"</div>";

?>