<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

class generalObjectsModelClass extends yModelClass {
	function getModel($controller) {
		$this->run($controller->actions);
				
		$this->setObjectID($controller->objectID);
		$this->set->items = false;
		$this->set->objectList = true;
		
		$_ = $this->getObject();
				
		$_->get = $controller->get;
		$_->url = $controller->url;

		return $_;
	}
	
	function run($actions) {
		if (!empty($actions)) foreach ($actions as $action) {
			if (!empty($action->oid))
				if (!$this->setObjectID($action->oid)) continue;

			if (!strcmp($action->action, 'updateItem')) {
				$this->saveItem($action->_d);
			}
			
			elseif (!strcmp($action->action, 'addField')) {
				$this->addField($action->fieldKey, $action->fieldName, $action->fieldType, $action->fieldExt);
			}
	
			elseif (!strcmp($action->action, 'deleteField')) {
				$this->dropFields(array($action->fieldKey));
			}
	
			elseif (!strcmp($action->action, 'deleteObject')) {
				$this->deleteObject($action->oid);
			}
	
			elseif (!strcmp($action->action, 'clearObject')) {
				$this->deleteItems();
			}
	
			elseif (!strcmp($action->action, 'addObject')) {
				$this->createObject($action->objectKey, $action->objectName, $action->objectType);
			}
	
		}
	}
}

?>