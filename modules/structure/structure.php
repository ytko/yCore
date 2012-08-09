<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureClass extends yClass {
	public
		$structure = array(
			'' => 'catalogClass',
			'catalog' => 'catalogClass',
			/*array(
				'class' => 'catalogClass',
				'methods' => array('' => 'catalog', 'page' => 'page')
			),*/
		),
		$adminStructure = array(
			'' => 'catalogClass',
			'catalog' => 'catalogClass'
		);
	
	public function moduleName($url = NULL) {
		// splitted url
		if(!isSet($url)) $url = $this->getUrl();

		if (reset($url) == 'admin') {
			array_shift($url); // delete 'admin'

			if (array_key_exists('', $this->adminStructure))
				$current = '';
			else
				$current = array_shift($url);
			
			return array($this->adminStructure[$current], $url);
		}
		else {
			if (array_key_exists('', $this->structure))
				$current = '';
			else
				$current = array_shift($url);
			
			if(array_key_exists($current, $this->structure)) {
				return array($this->structure[$current], $url);
			}
		}		
	}
}

?>