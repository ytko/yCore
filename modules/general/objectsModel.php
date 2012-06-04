<?php defined ('_YEXEC')  or  die();

yFactory::linkModel('/extended');

class generalObjectsModelClass extends extendedModelClass {
	function getModel($controller = NULL) { //synonym: to delete
		return $this->get($controller);
	}
	
	function get() {
		$this->update();
		
		$this->setObjectID($this->controller->objectID());
		$this->set->items = false;
		$this->set->objectList = true;

		$_ = $this->getObject();

		$_->get = $this->controller->get; //???
		$_->url = $this->controller->url; //???		
		
		return $_;
	}
	
	function update() {
		$this->dropObjects($this->controller->dropObjects());
		$this->addObjects($this->controller->addObjects());
		$this->addFields($this->controller->addFields());
		$this->dropFields($this->controller->dropFields());
		$this->clearObjects($this->controller->clearObjects());
	}
}

?>