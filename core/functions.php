<?php defined ('_YEXEC')  or  die();

class yHtml {
	static function getURI($base, $query, $modify = NULL) {
		$result = $base;

		$query = (array)$query;

		foreach ($modify as $key => $value)
			$query[$key] = $value;

		$first = true;
		foreach ($query as $key => $value)
			if (isset($value))
			if ($first) {
			$first = false;
			$result.= '?'.$key.'='.$value;
		} else
			$result.= '&'.$key.'='.$value;

		return $result;
	}
}

// ----------------------------------------------------



function _cTryInclude($path, $defaultPath) {
	$errorReporting = error_reporting();
	error_reporting(E_ERROR | E_PARSE);
	if (!include($path)) {
		error_reporting($errorReporting);
		include($defaultPath);
		return false;
	}
	else {
		error_reporting($errorReporting);
		return true;
	}
}

function _cTryIncludePath($path, $extension, $name, $defaultName, $thirdName = NULL) {
	return (_cTryInclude($path.$name.$extension, $path.$defaultName.$extension)) ? $name : $defaultName;
}

function _cTryIncludeMultiple() {
	$pathArray = func_get_args();
	
	$errorReporting = error_reporting();
	error_reporting(E_ERROR | E_PARSE);
	
	foreach ($pathArray as &$path) {
		if (include($path)) {
			error_reporting($errorReporting);
			return $path;
		}
	}

	error_reporting($errorReporting);	
	return false;
}

function _cTryIncludeMultiplePath() {
	$nameArray = func_get_args();
	$path = array_shift($nameArray);
	$extension = array_shift($nameArray);

	$errorReporting = error_reporting();
	error_reporting(E_ERROR | E_PARSE);

	foreach ($nameArray as &$name) {
		if (include($path.$name.$extension)) {
			error_reporting($errorReporting);
			return $name;
		}
	}

	error_reporting($errorReporting);
	return false;
}

function _cIsFileNameSafe($fileName) { //не должно быть слэшей
	return !(!(strpos($fileName, '/') === false) ||
			!(strpos($fileName, '\\') === false));
}

function _cIsPathNameSafe() { //не должно быть возвратов

}

// yFactory::safeName()
function _cGetSafeName($name, $defaultName = NULL, $errorName = NULL) {
	return isset($name)
	? (
			_cIsFileNameSafe($name)
			? $name
			: $errorName
	)
	: $defaultName;
}

// VIEW -------------------------------------------------------------------------------------------

function _cQuoteRecursive(&$data) {
	if (is_array($data)||is_object($data)) {
		foreach ($data as $key => &$value)
			if (is_string($value))
			$value = htmlspecialchars($value, ENT_QUOTES);
		else
			_cQuoteRecursive($value);
	}
	elseif (is_string($data)) {
		$data = htmlspecialchars($data, ENT_QUOTES);
	}
}

/*
function _cGetURI($base, $query, $modify) {
	getURI($base, $query, $modify);
}
*/

?>