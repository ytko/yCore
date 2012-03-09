<?php defined ('_YEXEC')  or  die(_CEXEC);

$this->getPage('_style', $_);
$this->getPage('_menu', $_);

$this->body.= "<div style='float:left; width:100%;'>";
	$this->getPage('_objectList', $_->objectList);
	$this->getPage('_itemEdit', $_);
$this->body.= "</div>";

$this->body.= "<div style='float:left; width:100%;'>";
	$this->getPage('_itemsTable', $_);
$this->body.= "</div>";
?>