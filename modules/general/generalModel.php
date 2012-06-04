<?php defined ('_YEXEC')  or  die();

yFactory::linkModel('/extended');

class generalModelClass extends extendedModelClass {
	public function get($controller = NULL /*may be to delete*/) {
		if (isset($controller)) $this->controller = $controller; //may be to delete
		
		$this->update(); //may be to move to bean
	
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