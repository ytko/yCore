<?php defined ('_YEXEC')  or  die();

class yControllerClass {
	public $post, $get;
	public $url;
	public $actions, $view, $itemID, $objectID;

	//abstract function getController();
	
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
		
		// ACTIONS
		// with current object (objectID)
					
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
		}
	}
	
	public function getRequest($key) {
		if     (isset($this->post->$key))
			return $this->post->$key;
		elseif (isset($this->get->$key))
			return $this->get->$key;
		else
			return NULL;
	}
	
	public function isRequestSet($key) {
		return (isset($this->post->$key) || isset($this->get->$key));
	}
}

?>