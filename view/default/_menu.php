<?php defined ('_YEXEC')  or  die();

$this->body.=
"<div style='float:left; width:100%; padding:5px; margin:5px 0 0 0; background-color:#cccccc'>
<a href='".yHtml::getURI($_->url, ySettings::$get)."'>Таблицы</a>
<a href='".yHtml::getURI($_->url, ySettings::$get, array('mod' => 'objects'))."'>Объекты</a>
<a href='".yHtml::getURI($_->url, ySettings::$get, array('mod' => 'users'))."'>Login</a>
</div>
";
/*
<a href='".getURI($_->url, $_->get, array('class' => 'obj'))."'>obj</a>
<a href='".getURI($_->url, $_->get, array('class' => 'list'))."'>list</a>
*/
?>