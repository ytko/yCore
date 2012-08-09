<?php

//$micro_start = microtime(true);

// First line of each php-file should be "<?php defined ('_YEXEC')  or  die();"
define ('_YEXEC', '');

// Include settings and yCore factory class
require_once('settings.php');
require_once(ySettings::$corePath.'/core.php');

header('Content-type: text/html; charset=utf-8');

// Getting content of page depending on url
$structure = yCore::structureClass();
list($moduleName, $url) = $structure->moduleName();
$content = yCore::create($moduleName)->setUrl($url);
$menu = yCore::structureTreeClass()->show();
echo yCore::templateTemplate()->setMenu($menu)->get($content->get());

//echo microtime(true) - $micro_start;

?>