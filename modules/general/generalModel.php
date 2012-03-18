<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class generalModelClass extends yModelClass {
	function __construct($db) {
		parent::__construct($db);
	}
	
	function getModel($controller) {
		$this->run($controller->actions);
	
		$this->set->objectList = true;
		
		$this->setObjectID($controller->objectID);
		$this->setItemID($controller->itemID);
		$this->setPage($controller->page, 20);
	
		$_ = $this->getObject();
	
		$_->get = $controller->get;
		$_->url = $controller->url;
	
		$_->objectList->get = $controller->get;
		$_->objectList->url = $controller->url;
	
		return $_;
	}
	
	function run($actions) {
		if (!empty($actions)) foreach ($actions as $action) {
			if (!empty($action->oid)) 
				if (!$this->setObjectID($action->oid)) continue;		

			if (!strcmp($action->action, 'updateItem')) {
				$this->saveItem($action->_d);
			}

			elseif (!strcmp($action->action, 'deleteItem')) {
				$this->deleteItem($action->id);
			}
		}	
	}
}

?>