<?php defined ('_YEXEC')  or  die();

//TODO: rename _d

class yControllerClass {
	public $post, $get;
	public $url;
	public $actions, $view, $itemID, $objectID;

	//v2.0
	
	public function getRequest($key) {
		if (!empty($_POST[$key]))
			return $_POST[$key];
		elseif (!empty($_GET[$key]))
			return $_GET[$key];
		elseif (isset($_POST[$key]))
			return $_POST[$key];
		elseif (isset($_GET[$key]))
			return $_GET[$key];
		else
			return NULL;
	}
	
	function objectID() {
		return $this->getRequest('oid');
	}
	
	function updateItems() {
		if ($this->getRequest('updateItem')) {
			$result = array();
			
			$result[] =
				(object)array (
					'oid'	 => $this->objectID(),
					'_d'     => (object)$_POST['_d']
				);
			
			return $result;			
		}
		else
			return NULL;
	}
	
	function deleteItems() {
		$deleteItem = $this->getRequest('deleteItem');
		
		if (!is_array($deleteItem)) return NULL;
		
		$result = array();
		
		foreach ($deleteItem as $key => $value)		
			$result[] =
				(object)array (
						'oid'		=> $this->objectID(),
						'id' 		=> $key
				);

		return $result;
	}
	
	function addFields() {
		if ($this->getRequest('addField')) {
			$result = array();
			
			$result[] =
				(object)array (
						'oid'		=> $this->objectID(),
						'fieldName' => $this->post->field['name'],
						'fieldKey' => $this->post->field['key'],
						'fieldType' => $this->post->field['type'],
				);

			if ( !empty($this->post->fieldExt['type']) )
				$result->fieldExt = $this->post->fieldExt;

			return $result;
		}
		else
			return NULL;
	}
	

	function dropFields() {	
		//if ($this->getRequest('deleteField')||$this->getRequest('dropField')) {
			
		$dropField = $this->getRequest('deleteField'); //add dropField
		
		if (!is_array($dropField)) return NULL;
		
		$result = array();
	
		foreach ($dropField as $key => $value)
			$result[] =
				(object)array (
						'oid'		=> $this->objectID(),
						'fieldKey' 	=> $key
				);

		return $result;
	}
	
	function dropObjects() {
		$dropObjects = $this->getRequest('deleteObject'); //add dropObject

		if (!is_array($dropObjects)) return NULL;
	
		$result = array();
	
		foreach ($dropObjects as $key => $value)
			$result[] =
				(object)array (
						'oid' => $key
				);
	
		return $result;
	}
	
	function addObjects() {
		if ($this->getRequest('addObject')) {
			$result = array();
		
			$result[] =
				(object)array (
					'objectKey' => $this->getRequest('objectKey'),
					'objectName' => $this->getRequest('objectName'),
					'objectType' => $this->getRequest('objectType')
				);

			return $result;
		}
		else
			return NULL;
	}	

	function clearObjects() {	
		$clearObject = $this->getRequest('clearObject');
		
		if (!is_array($clearObject)) return NULL;
		
		$result = array();
		
		foreach ($clearObject as $key => $value)		
			$result[] =
				(object)array (
						'oid'		=> $key
				);

		return $result;
	}
	
	/*	
	
	if (!empty($this->post->c)) {
		foreach ($this->post->clearObject as $key => $value)
			$this->actions[] =
			(object)array (
					'action'	=> 'clearObject',
					'oid'		=> $key
			);
	}
*/
	
	// synonyms
	function oid() {
		return $this->objectID();
	}
			
	function updateRow() { //may be
		return $this->updateItem();
	}
	
	//abstract function getController();\
	
	//v1.0
	
	function __construct($controllerName) {
		
		$this->post = (object) $_POST;
		$this->get = (object) $_GET;
		$this->files = $_FILES;
		$this->url = $_SERVER['PHP_SELF'];
		$this->controllerName = $controllerName;
		
		// GET only (GET > NULL)
		
		$this->view =
			(!empty($this->get->view))
				? $this->get->view
				: 'default';

		$this->page = 
			(!empty($this->get->page))
				? $this->get->page
				: 1;

		// POST or GET (POST > GET > NULL)

		$this->itemID = (!empty($this->post->id)
				? $this->post->id
				: (!empty($this->get->id)
					? $this->get->id
					: NULL
				  )
			);
			
		$this->objectID = 
			(!empty($this->post->oid)
				? $this->post->oid
				: (!empty($this->get->oid)
					? $this->get->oid
					: NULL
				  )
			);
		
		// to delete
		// ACTIONS
		// with current object (objectID)
					/*
		if (!empty($this->objectID)) {
			if (!empty($this->post->updateItem)) {		
				$this->actions[] =
					(object)array (
						'action' => 'updateItem',
						'oid'	 => $this->objectID,
						'_d'     => (object)$this->post->_d
					);
			}
			
			if (!empty($this->post->deleteItem)) {
				 foreach ($this->post->deleteItem as $key => $value)
					 $this->actions[] =
						(object)array (
							'action'	=> 'deleteItem',
							'oid'		=> $this->objectID,
							'id' 		=> $key
						);
			}

			if (!empty($this->post->addField)) {
				$addField = 
					(object)array (
						'action'	=> 'addField',
						'oid'		=> $this->objectID,
						'fieldName' => $this->post->field['name'],
						'fieldKey' => $this->post->field['key'],
						'fieldType' => $this->post->field['type'],
					);
				
				if ( !empty($this->post->fieldExt['type']) )
					$addField->fieldExt = $this->post->fieldExt;
										
				$this->actions[] = $addField;				
			}
			
			if (!empty($this->post->deleteField)) {
				 foreach ($this->post->deleteField as $key => $value)
					 $this->actions[] =
						(object)array (
							'action'	=> 'deleteField',
							'oid'		=> $this->objectID,
							'fieldKey' => $key
						);
			}
		}

		// ACTIONS
		// with other or multiple object(s)

		if (!empty($this->post->deleteObject)) {
			 foreach ($this->post->deleteObject as $key => $value)
				 $this->actions[] =
					(object)array (
						'action'	=> 'deleteObject',
						'oid'		=> $key
					);
		}
		
		if (!empty($this->post->clearObject)) {
			foreach ($this->post->clearObject as $key => $value)
				$this->actions[] =
				(object)array (
						'action'	=> 'clearObject',
						'oid'		=> $key
				);
		}
		
		if (!empty($this->post->addObject)) {
			 $this->actions[] =
				(object)array (
					'action'	=> 'addObject',
					'objectKey' => $this->post->objectKey,					
					'objectName' => $this->post->objectName,
					'objectType' => $this->post->objectType
				);
		}*/
	}
	
	public function isRequestSet($key) {
		return (isset($this->post->$key) || isset($this->get->$key));
	}
}

?>