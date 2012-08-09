<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureClass extends yClass {
	public
		$structure = array(),
		$tail;

	public function get($url = NULL, $structure = NULL) {
		// define variables for non-recursive call
		if(!isSet($url))		$url = $this->getUrl();
		if(!isSet($structure))	$structure = $this->structure;
		// define current directory in url
		$dir = reset($url) ?: '';

		// define current node
		if(array_key_exists($dir, $structure) && $dir != '') {
			$node = $structure[array_shift($url)];
		} elseif(array_key_exists('', $structure)) {
			$node = $structure[''];
		} else {
			return function(){echo 404;}; //404 error
		}

		// if node is array make recursive call of this method
		if(is_array($node))
			$node = $this->get($url, $node);

		$this->tail = $url;
		
		// return value of node
		return $node;
	}
}

?>