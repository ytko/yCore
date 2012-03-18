<?php defined ('_YEXEC')  or  die();

class yTemplateClass {
	function head(&$_) {}
	
	function body(&$_) {}
	
	static function getURI($base, $query, $modify = NULL) {
		$result = $base;

		$query = (array)$query;

		if($modify)
		foreach ($modify as $key => $value)
			$query[$key] = $value;

		$first = true;
		foreach ($query as $key => $value)
			if (isset($value))
			if ($first) {
			$first = false;
			$result.= '?'.$key.'='.$value;
		} else
			$result.= '&amp;'.$key.'='.$value;

		return $result;
	}
}

?>