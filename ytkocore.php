<?php defined('_JEXEC') or die('Restricted access');

/*
echo '<pre>';
print_r (get_defined_constants());
JPATH_COMPONENT
JPATH_COMPONENT_SITE
JPATH_COMPONENT_ADMINISTRATOR
*/

define ('_YEXEC', '');
//define ('DS', '/');

class ySettings {
	static public $get, $mvc, $path, $db;
}

ySettings::$path = JPATH_COMPONENT_SITE;
ySettings::$get = (object)array('option' => $_GET['option']);
ySettings::$mvc = (object)array(
		'module' => 'mod',
		'controller' => 'controller',
		'model' => 'model',
		'view' => 'view'
);
ySettings::$db = (object)array(
		'resource' => JFactory::getDBO(),
		'prefix' => '#__',
		'com_prefix' => 'ytko',
		'type' => ''
);

include_once(ySettings::$path.DS.'include'.DS.'debug.php');
/* Debugger settings
yDebug::$on = true;
yDebug::$all = true;
//*/

require_once(ySettings::$path.DS.'core'.DS.'factory.php');
include_once(ySettings::$path.DS.'core'.DS.'functions.php');
require_once(ySettings::$path.DS.'core'.DS.'dbquery.php');
require_once(ySettings::$path.DS.'core'.DS.'db.php');

yFactory::$basePath = JPATH_COMPONENT_SITE;

$module = yFactory::getModule($_GET[ySettings::$mvc->module]);
$result = $module->getModule($cms);

__p($_POST);

echo '<style>'.$result->style.'</style>';
echo '<script>'.$result->script.'</script>';
echo $result->title;
echo $result->body;

?>