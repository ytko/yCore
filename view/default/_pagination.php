<?php defined ('_YEXEC')  or  die();

$this->body.=
 	($_->pagination->first != $_->pagination->current)
		? "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $_->pagination->first))."'><<</a> "
		: NULL;

$this->body.=
	(!empty($_->pagination->prev))
		? "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $_->pagination->prev))."'><</a> "
		: NULL;

foreach($_->pagination->before as $page)
	$this->body.= "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $page))."'>$page</a> ";	

$this->body.= "<strong>{$_->pagination->current}</strong> ";

foreach($_->pagination->after as $page)
	$this->body.= "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $page))."'>$page</a> ";	

$this->body.=
	(!empty($_->pagination->next))
		? "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $_->pagination->next))."'>></a> "
		: NULL;

$this->body.=
	($_->pagination->last != $_->pagination->current)
		? "<a href='".yHtml::getURI($_->url, $_->get, array('page' => $_->pagination->last))."'>>></a> "
		: NULL;

?>