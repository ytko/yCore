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
	
	public function content() {
		// splitted url
		$urlArray = $this->urlArray();

		if (reset($urlArray) == 'admin') {
			array_shift($urlArray); // delete 'admin'

			if (array_key_exists('', $this->adminStructure))
				$current = '';
			else
				$current = array_shift($urlArray);
			
			return yCore::create($this->adminStructure[$current])
				->setUrl($urlArray)
				->setAdmin(true)
				->get();
		}
		else {
			if (array_key_exists('', $this->structure))
				$current = '';
			else
				$current = array_shift($urlArray);
			
			if(array_key_exists($current, $this->structure)) {
				return yCore::create($this->structure[$current])
					->setUrl($urlArray)
					->get();
			}
		}		
	}

	public function menu() {
		return yCore::structureTreeClass()->show();
	}
	
	public function show() {
		$content = $this->content();
		$menu =  $this->menu();
		return yCore::templateTemplate()->setMenu($menu)->get($content);
		
	}
}

?>