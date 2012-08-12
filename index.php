<?php

//$micro_start = microtime(true);

// Clean code required =)
error_reporting(E_ALL | E_STRICT);

// First line of each php-file should be "<?php defined ('_YEXEC')  or  die();"
define ('_YEXEC', '');

// Include settings and yCore factory class
require_once('settings.php');
require_once(ySettings::$corePath.'/core.php');

header('Content-type: text/html; charset=utf-8');

// Getting content of page depending on url
$structure = yCore::structureClass();

$structure->structure = array(
	'' => function() use ($structure) {return yCore::catalogClass()->get($structure->tail);},
	'admin' => array(
		'' => function() use ($structure) {return yCore::catalogClass()->setAdmin(true)->get($structure->tail);},
	),
);

$contentClosure = $structure->get();
$content = $contentClosure();
$menu = yCore::structureTreeClass()->show();
echo yCore::templateTemplate()->setMenu($menu)->get($content);

//echo microtime(true) - $micro_start;

?>