<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class generalModelClass extends yModelClass {
	function __construct($db) {
		parent::__construct($db);
	}

	function getModel($controller = NULL) { //synonym: to delete
		return $this->get($controller);
	}	
	
	public function get($controller = NULL /*may be to delete*/) {
		if (isset($controller)) $this->controller = $controller; //may be to delete
		
		$this->update(); //may be to move to glue
	
		$this->set->objectList = true;
		
		$this->setObjectID($this->controller->objectID);
		$this->setItemID($this->controller->itemID);
		$this->setPage($this->controller->page, 20);
	
		$_ = $this->getObject();

		$_->get = $this->controller->get; //???
		$_->url = $this->controller->url; //???

		$_->objectList->get = $this->controller->get; //???
		$_->objectList->url = $this->controller->url; //???

		return $_;
	}
	
	public function update() {
		$this->deleteItems($this->controller->deleteItems());			
		$this->updateItems($this->controller->updateItems());
	}
}

?>