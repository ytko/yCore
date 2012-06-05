<?php defined ('_YEXEC')  or  die();

yFactory::linkModel();

/*************************************************
TODO:
безопасность для функций БД:
- addFilter
- addFilterSQL
 
Оптимизировать getObject
  
saveArray
- проработать update (добавление полей)
- переделать: table[1] - может быть 0, или что угодно еще
- добавить saveObject

= NULL: empty() => isset()

objectType!!!

debug_mode, warning_report, error_report

Доступ к объектам/элементам:
- $objectKey, $itemKey - запрет на название объектов только из цифр; проверка на латиницу
- $objectKeyOrID
- getObject($objectKeyOrID)
- getItem($itemPath) / getItem($objectKeyOrID, $itemKeyOrID)) - доступна в обоих вариантах, $itemPath = "$objectKeyOrID_$itemID"
  
**********************************************/

class yObjectClass { //yModelObjectClass
	public $page, $itemsPerPage;

	function getUrl($vars) {
		
	}
	
	function getLinkedTableList() {
		$result = (object)array();
		foreach($this->fields as $fieldName => &$field)
			if(
				isset($field->ext->type) &&
				isset($field->ext->table) &&
				!strcmp($field->ext->type, 'link')
			)
				$result->$fieldName = $field->ext;

		return $result;
	}
}

// flags в отдельный класс: $this->flags->flag1 = true; $this->flags->set('flag1', 'flag2'); $this->flags->unset('flag3', 'flag4'); yDebug: показать включенные флаги в getObject
// setPage -> сюда?

class yModelSetClass {
	public
		$pagination = true,
		$itemsPerPage = 20,
		$page = 1,
		$objectList = false,
		$getItem = true, //redo - NULL
		$getItems = true, //redo - NULL
		$link = false; //to think
	
	function getPagination($count, $rad = 3) {
		$pagination = (object) array ();
	
		$last = ceil($count / $this->itemsPerPage);
	
		$pagination->first = '1';
		$pagination->prev = (($this->page - 1) >= $pagination->first) ? $this->page - 1 : NULL;
	
		$pagination->before = array();
		for ($i = ((($this->page - $rad) > $pagination->first) ? ($this->page - $rad) : $pagination->first); $i < $this->page; $i++) {
			$pagination->before[$i] = $i;
		}
	
		$pagination->current = $this->page;
	
		$pagination->after = array();
		for ($i = $this->page + 1; $i <= ((($this->page + $rad) < $last) ? ($this->page + $rad) : $last); $i++) {
			$pagination->after[$i] = $i;
		}
	
		$pagination->next = (($this->page + 1) <= $last) ? $this->page + 1 : NULL;
		$pagination->last = $last;
	
		return $pagination;
	}
}

class extendedModelClass extends yModelClass {
	public $_;
	public $db;
	public $objectExists; //???
	public $filters, $fields, $flags;
	public $controller;
	//public $override = (object)array('SQL' => NULL);
	private $isObjectLoaded = false;
	private $itemsPerPage, $pagination;
	
	function __construct() {
		$this->db = new yExtDbClass(ySettings::$db->prefix, ySettings::$db->com_prefix, true, true);
		
		$this->reset();
	}
	
	function setController(&$controller) {
		$this->controller = &$controller;
		return $this;
	}
	
	function reset($property = NULL) {
		if ( (!isset($property)) || (!strcmp($property, 'flags'))) {
			$this->flags = array();
			$this->set = new yModelSetClass;
		}
		if ( (!isset($property)) || (!strcmp($property, 'filters')))
			$this->filters = array();
		if ( (!isset($property)) || (!strcmp($property, 'pagination')))
			$this->pagination = (object)array();
		if ( (!isset($property)) || (!strcmp($property, 'page')))			
			$this->setPage(1);
		if ( (!isset($property)) || (!strcmp($property, '_'))) {
			$this->_ = new yObjectClass;
			$this->isObjectLoaded = false;
		}
	}

// object id, key, name, type, item id --------------------------------	
	
	private $objectID = NULL, $objectKey = NULL, $objectName = NULL, $objectType = NULL, $itemID = NULL;
	
	public function setObjectKey($objectKey) {
		if (strcmp ($objectKey, $this->objectKey) || (!$isObjectLoaded)) {
			$this->objectKey = $objectKey;			
			$objectRec = $this->getObjectRec();
			$this->objectID = $objectRec->id;
			$this->objectName = $objectRec->name;
			$this->objectType = $objectRec->type;
			$this->fields = $objectRec->fields;
		}
		
		return $this->objectID;
	}

	public function setObjectID($objectID) {
		if (($objectID != $this->objectID) || (!$isObjectLoaded)) {
			$this->objectID = $objectID;
			$objectRec = $this->getObjectRec();
			$this->objectKey = $objectRec->key;
			$this->objectName = $objectRec->name;
			$this->objectType = $objectRec->type;
			$this->fields = $objectRec->fields;
		}
		
		return $this->objectKey;
	}
	
	private function getObjectKey($objectID = NULL) {
		if (isset($objectID)) {
			$result = ($this->db->getItem($objectID));
		}
		else {
			$result = $objectKey;
		}
		
		return $result->key;
	}
	
	public function setItemID($itemID) {
		$this->itemID = $itemID;
	}
	
	public function itemID($itemID = NULL) {
		if (isset($itemID)) {
			$this->setItemID($itemID);
		}
				
		return $this->itemID;
	}
	
	public function objectKey($objectKey = NULL) {
		if (isset($objectKey)) {
			$this->setObjectKey($objectKey);
		}
		
		return $this->objectKey;
	}
	
	public function objectID($objectID = NULL) {
		if (isset($objectID)) {
			$this->setObjectID($objectID);
		}
		
		return $this->objectID;
	}
	
	public function objectName() {
		return $this->objectName;
	}
	
	public function objectType() {
		return $this->objectType;
	}
	
	public function getObject() {
		yDebug::method(__METHOD__);
				
		$_ = new yObjectClass;

		// --------------------------- FLAGS ---------------------------------------------------------------------------   

		if(!$this->set->items) { // не запрашивать элементы 
			$this->db->setLimit('1'); //переделать!!!
		}
		
		if($this->set->pagination) { //постраничный вывод
			$offset = ($this->set->page - 1)*$this->set->itemsPerPage;
			$offset = ($offset > 0) ? $offset : 0;
			$this->db->setLimit("{$offset}, {$this->set->itemsPerPage}");
		}
		
		if($this->set->link) { //прилинковать таблицы
			$fields = $this->getObjectRec()->fields; //оставить только здесь? в getItems тоже есть
			
			$this->db->from = ' AS a';
			
			foreach($fields as $key => $field) {
				if (isset($field->ext->type) && !strcmp($field->ext->type, 'link')) {
					$this->db->from.= " LEFT JOIN `{$field->ext->table}` AS $key ON a.$key = $key.{$field->ext->field}";
				}
			}
		}
		
		// ------------------------------------------------------------------------------------------------------------
		
		if (isset($this->objectID)) {
			$result = $this->getItems();
			$this->isObjectLoaded = true;
		} else {
			$result->items = NULL;
			$this->isObjectLoaded = false;
		}
		
		if (isset($this->itemID)) {
			$item = $this->getItem($this->itemID);
			$result->item = $item->item;		
		} else {
			$result->item = NULL;
		}

		// --------------------------- FLAGS ---------------------------------------------------------------------------		
		
		if($this->set->objectList) { // получить список объектов
			$result->objectList = $this->getTables();
			$result->objectList->item = $this->getTable();
		}
		
		if($this->set->pagination) //постраничный вывод
			$result->pagination = $this->getPagination();

		// -------------------------------------------------------------------------------------------------------------		
		// --------------------------- COPYPASTE -----------------------------------------------------------------------
		
		$result->objectID = $this->objectID();
		$result->objectKey = $this->objectKey();
		$result->objectName = $this->objectName();
		$result->objectType = $this->objectType();
		
		$result->fields = $this->fields;
		$result->page = $this->set->page;
		$result->itemsPerPage = $this->set->itemsPerPage;

		// -------------------------------------------------------------------------------------------------------------		
		
		return $result;
	}
	
// SETTINGS -----------------------------------------------------------	
	// flags в отдельный класс: $this->flags->flag1 = true; $this->flags->set('flag1', 'flag2'); $this->flags->unset('flag3', 'flag4'); yDebug: показать включенные флаги в getObject
	
	public $set;
	
	function setPage($page, $itemsPerPage = NULL) {
		if (isset($page) && $page >= 1) {
			$this->set->page = $page;
		}
				
		if (isset($itemsPerPage)) {
			$this->set->itemsPerPage = $itemsPerPage;
		}			
	}
	
// PAGINATION ---------------------------------------------------------	
	
	function getPagination($rad = 3) {
		$count = $this->countItems();
		
		return $this->set->getPagination($count, $rad);
	}
	
// FILTERS ------------------------------------------------------------

	public function addFilter($field, $type, $filter, $operator = 'AND') {
		$this->filters[] =
			(object)array(
				'field' => $field,
				'type' => $type,
				'filter' => $filter,
				'operator' => $operator
			);
	}
	
	public function addFilterSQL($sql, $operator = 'AND') {
		$this->filters[] =
			(object)array(
				'type' => 'sql',
				'filter' => $sql,
				'operator' => $operator
			);
	}

	private function applyFilters() { //internal
		foreach ($this->filters as $filter)
			if (!strcmp($filter->type, 'text'))
				$this->db->addWhere("`{$filter->field}` LIKE '%{$filter->filter}%'", $filter->operator);
			elseif (!strcmp($filter->type, 'like')) //=== text, оставить like
				$this->db->addWhere("`{$filter->field}` LIKE '%{$filter->filter}%'", $filter->operator);		
			elseif (!strcmp($filter->type, 'sql'))
				$this->db->addWhere($filter->filter, $filter->operator);
			elseif (!strcmp($filter->type, 'relevance')) {
				$this->db->addField('*'); //!!!
				$this->db->addField("MATCH (`{$filter->field}`) AGAINST ('{$filter->filter}')", "relevance");
				$this->db->setOrder("`relevance` DESC");
				//$this->db->setLimit(10); //!!!
			}
	}
	
// ITEMS --------------------------------------------------------------

	function countItems(){
		if (empty($this->objectKey)) return NULL;
		
		$this->applyFilters();
		$this->db->reset('fields');
		$this->db->addField('COUNT(*)', 'count');
		
		return $this->db->getItem($this->currentObjectTableName(), NULL)->count;
	}
	
	function getItems($fields = false){
		yDebug::method(__METHOD__);
		if (empty($this->objectKey)) return NULL;
		
		$this->applyFilters();

		if (!$fields) {
			$fields = $this->fields;
		}
		
		$items = $this->db->getItems($this->currentObjectTableName());
					
		$_ = new yObjectClass();
		
		$_->fields = $fields;
		$_->item = $items[0];
		$_->items = $items;
		$_->objectKey = $this->objectKey();
		$_->objectID = $this->objectID();
		
		return $_;
		
		return (object)array(
			'fields'		=> $fields,
			'item'			=> $items[0],			
			'items'			=> $items,
			'objectKey'		=> $this->objectKey(),
			'objectID'		=> $this->objectID()
		);
	}
	
	function getItem($id, $fields = false){
		yDebug::method(__METHOD__);
		if (empty($this->objectKey)) return NULL;
			
		$this->applyFilters();			
			
		if (!empty($id))
			$item = $this->db->getItem($this->currentObjectTableName(), $id);
		else
			$item = array();
		
		if (!$fields) {
			$fields = $this->getObjectRec();
			$fields = $fields->fields;
		}
		
		return (object)array(
			'fields'		=> $fields,
			'item'			=> $item,			
			'items'			=> array($item),
			'objectKey'		=> $this->objectKey(),
			'objectID'		=> $this->objectID()
		);
	}
	
	function getValue($field){
		//$this->db->addField($field);
		$item = $this->db->getItem($this->currentObjectTableName());
		
		return $item->$field;		
	}	

	public function updateItems($values) {
		//print_r($values);
		if (is_array($values)) {
			foreach ($values as $updateItem) {
				if (!empty($updateItem->oid))
					if ($this->setObjectID($updateItem->oid))
						$this->updateItem($updateItem->_d);
			}
		}
	}
	
	public function updateItem($values) {
		//print_r($values);
		if (empty($this->objectKey)) return NULL;
	
		return $this->db->insertItem($this->currentObjectTableName(), $values);
	}
	
	public function saveItem($values) { //synonym
		return $this->updateItem($values);
	}
	
	public function deleteItems($list) {
		if (is_array($list)) {
			foreach ($list as $deleteItem) {
				if (!empty($deleteItem->oid))
					if ($this->setObjectID($deleteItem->oid))
						$this->deleteItem($deleteItem->id);
			}
		}
	}	
	
	
	 public function deleteItems_old() {
	if (empty($this->objectKey)) return NULL;
	
	return $this->db->deleteItems($this->currentObjectTableName());
	}
	
	public function deleteItem($id) {
		if (empty($this->objectKey)) return NULL;	
	
		$this->db->addWhere("`id` = '$id'");
		$this->db->setLimit(1);
		return $this->db->deleteItems($this->currentObjectTableName());
	}

	
// OBJECTS ------------------------------------------------------------

	function getTables($fields = false){
		yDebug::method(__METHOD__);
		$this->db->setOrder('`key`');
		$items = $this->db->getItems('');
			
		if (!$fields)
			$fields = (object)array('key'=>'text', 'name'=>'text', 'fields'=>'text');
			
		foreach ($items as &$item) {
			$item->fields = unserialize($item->fields);
		}
			
		return (object)array(
			'fields'		=> $fields,
			'item'			=> $items[0],			
			'items'			=> $items,
			'objectKey'		=> $this->objectKey(),
			'objectID'		=> $this->objectID()
		);

		//$result = $this->_v->getItems($items, array('header' => $header), 'folder_loop');

		//return $result;
	}
	
	function getTable($id = NULL, $fields = false){
		yDebug::method(__METHOD__);
		if (empty($this->objectKey)) return false;
		
		if (!empty($id))
			$item = $this->getObjectRec($id);
		else
			$item = array();
		
		if (!$fields)
			$fields = (object)array('key'=>'text', 'name'=>'text', 'fields'=>'text');
		
		/*	
		__p($fields);
		$fields = unserialize($fields);
		*/

		return (object)array(
			'fields'		=> $fields,
			'item'			=> $item,			
			'items'			=> array($item),
			'objectKey'		=> $this->objectKey(),
			'objectID'		=> $this->objectID()
		);

		//$result = $this->_v->getItem($item, array('header' => $fields), 'folder_single');

		//return $result;
	}
	
	public function createObject($objectKey, $objectName = NULL, $objectType = NULL, $fields = NULL){ //check name
		if (empty($fields)) $fields = array();
		
		$fullKey = $this->currentObjectTableName($objectKey);
		
		if ($this->db->createTable($fullKey, $fields)) {
			if (!$this->setObjectRec($fields, $objectKey, $objectName, $objectType)) { 	// Make Object Record if table created
				$this->db->dropTable($fullKey); 							// Drop table if can't make record
				return false;
			}				
			else return true;
		}
		else return false;	
	}
	
	public function addObjects($list) {
		if (is_array($list))
			foreach($list as $addObject)
				if (!empty($addObject->objectKey))
					$this->createObject($addObject->objectKey, $addObject->objectName, $addObject->objectType);
	}
	
	public function dropObjects($list) { //todo:dropObjects, deleteObjects merge
		if (is_array($list))
			foreach($list as $dropObject)
				if (!empty($dropObject->oid))
					$this->dropObject($dropObject->oid);
	}
	
	public function dropObject($id = NULL) {
		$head = $this->getObjectRec($id);

		$objectKey = $head->key;
		$fullKey = $this->currentObjectTableName($objectKey);
		
		$this->db->setLimit(1);
		$this->db->addWhere("`id` = '$id'");
		$this->db->deleteItems('');

		return $this->db->dropTable($fullKey);
	}
	
	public function deleteObject($id = NULL) { //synonym
		return $this->dropObject($id);
	}

	public function clearObjects($list) {
		if (is_array($list))
			foreach($list as $clearObject) {
			if (!empty($clearObject->oid))
				if ($this->setObjectID($clearObject->oid))
				$this->deleteItems_old();
		}
	}	
	
 //setObjectRec getObjectRec дописать для возможности/невозможности изменять тип существующего поля
	private function setObjectRec($fields, $key = NULL, $name = NULL, $type = NULL) {
			$this->isObjectLoaded = false;
		
			$serialized_fields = serialize($fields);

			$values = array('key' => $this->objectKey(), 'fields' => $serialized_fields);
	
			if(!empty($key))
				$values['key'] = $key;
			else
				$values['key'] = $this->objectKey();
	
			if(!empty($name))
				$values['name'] = $name;
			
			if(!empty($type))
				$values['type'] = $type;
	
			return $this->db->insertItem('', $values);
	}
	
	private function getObjectRec($id = NULL) {
		yDebug::method(__METHOD__);
		
		if (!isset($id) && isset($this->objectID)) $id = $this->objectID;
		
		if (isset($id))
			$this->db->addWhere("`id` = '{$id}'");
		elseif (isset($this->objectKey))
			$this->db->addWhere("`key` LIKE '{$this->objectKey()}'");
	
		$result = $this->db->getItem('');
	
		$result->fields = (object)unserialize($result->fields);
		foreach ($result->fields as $key => &$field) {
			$field = (object)$field;
			if (isset($field->ext))
				$field->ext = (object)$field->ext;
		}
				
		$this->isObjectLoaded = true;	
		
		return $result;
	}
	
	function addFields($list) {
		if (is_array($list))
			foreach($list as $addField) {
			if (!empty($addField->oid))
				if ($this->setObjectID($addField->oid))
					$this->addField($addField->fieldKey, $addField->fieldName, $addField->fieldType, $addField->fieldExt);
		}
	}
	
	public function addField($key, $name, $type, $ext = NULL) {
		$field = array(
			'type' => $type,
			'name' => $name
		);
			
		if (isset($ext)) $field['ext'] = $ext;
	
		return $this->addFields_old(array($key => $field));
	}
	
	public function addFields_old($fields) { //to merge with addFields
		if ($this->db->tableAddFields(
				$this->currentObjectTableName(),
				$fields) ) {
			$objectRecord = $this->getObjectRec();
			$objFields = (array)$objectRecord->fields;
			$objFields = array_merge($objFields, (array)$fields);
			$this->setObjectRec($objFields);
			return true;		
		}
		else return false;	
	}
	
	public function dropFields($fields) {
		if (is_array($fields))
			foreach($fields as $dropField) {
			if (!empty($dropField->oid))
				if ($this->setObjectID($dropField->oid))
					$this->dropFields_old(array($dropField->fieldKey));
		}		
	}
	
	public function dropFields_old($fields) { //setObjectRec getObjectRec //to merge with addFields
	//удаление полей объекта
		//if (!($this->isAdmin&&$this->isUser)) return false;

		if ($this->db->tableDropFields(
				$this->currentObjectTableName(),
				$fields) ) {
			$objectRecord = $this->getObjectRec();
			$objectFields = (array)$objectRecord->fields;
			foreach ($fields as $key => $value) {
				if (is_int($key))
					unset($objectFields[$value]);
				else
					unset($objectFields[$key]);
			} // array_diff_assoc($objRec, (array)$fields);
			$this->setObjectRec($objectFields);
			return true;		
		}
		else return false;
	}	
	
// inner
// разобраться с названиями
	private function currentObjectTableName($objectKey = NULL) {
		return '_'.((isset($objectKey)) ? $objectKey : $this->objectKey());
	}
	
	private function getTableName($objectKey = NULL) { // (===currentObjectTableName)
		return ((isset($objectKey)) ? $objectKey : $this->objectKey());
	}
	
	private function getFullTableName($objectKey = NULL) { // (===currentObjectTableName)
		return $this->db->getTableName(((isset($objectKey)) ? $objectKey : $this->objectKey()));
	}	
	
// ARRAYS

	function saveArray($table) {
		$fields = array();

		//reset($table); ???
		$fields = current($table);

		foreach ($fields as $key => $value) //переделать: table[1] - может быть 0, или что угодно еще
			//$fields[$key] = array('type' => 'text', 'name' => $key);
			$this->addField($key, $key, 'text');
		
		foreach ($table as $key => $value)
			$this->saveItem($value);
	}
}

class yExtDbClass {
	public $sql;
	public $cms_prefix, $com_prefix;
	public $isUser = false, $isAdmin = false; // $isAdmin === can modify structure / $isUser === can modify records / evrybody can read records
	public $filters = NULL;
	
	public $fields = array(), $from = NULL, $where = NULL, $groupby = NULL, $orderby = NULL, $limit = NULL; //$from!!! - сделать

	function __construct($prefix, $com_prefix, $isUser = false, $isAdmin = false) {
		//$this->setObj($name);
		$this->isAdmin = $isAdmin;
		$this->isUser = $isUser;
		
		$this->cms_prefix = $prefix;
		$this->com_prefix = $com_prefix;
		
		//$this->sql = &$_q;

		$this->db = yFactory::getDb();
		$this->sql = $this->db->sql;
	}
	
// $from, $where, $limit

	function addWhere($where, $logic = 'AND') {
		/*$this->sql->where.= ((empty($this->sql->where))
			? NULL
			: " $logic "
		)."($where)"; //!удалить
		*/
		$this->where.= ((empty($this->where))
			? NULL
			: " $logic "
		)."($where)"; //добавть отправку в _qClass
	}
	
	function setLimit($limit) {		
		$this->limit = $limit;
	}
	
	function setGroup($groupby) {
		$this->groupby = $groupby;
	}
	
	function setOrder($orderby) {
		$this->orderby = $orderby;
	}
	
	function addField($field, $key = NULL) {
		if (isset($key))
			$this->fields[$key] = $field;
		else
			$this->fields[] = $field;
	}
	
	function reset($property = NULL) {
		if ( (!isset($property)) || (!strcmp($property, 'fields')))
			$this->fields = array();
		if ( (!isset($property)) || (!strcmp($property, 'from')))			
			$this->from = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'where')))			
			$this->where = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'orderby')))			
			$this->orderby = NULL;
		if ( (!isset($property)) || (!strcmp($property, 'limit')))			
			$this->limit = NULL;
	}
	
// TABLES ------------------------------------------------------------

	function createTable($table, $fields = NULL) {
		if (!($this->isAdmin&&$this->isUser)) return false;
		if (empty($fields)) $fields = array();
		
		$query = 
			"CREATE TABLE `{$this->getTableName($table)}` ( ".
			"`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY";

		foreach ($fields as $field_key => $field_value) {
			if (!strcmp($field_value, 'text'))
				$query.= ", `$field_key` TEXT NOT NULL";
		}

		$query.=
			") ENGINE = MYISAM ;";

		return $this->sql->query($query);
	}
	
	function dropTable($table) {
		if (!($this->isAdmin&&$this->isUser)) return false;
		
		$query = "DROP TABLE `{$this->getTableName($table)}`";

		return $this->sql->query($query);
	}
	
	function tableAddFields($table, $fields) {
	//добавление полей объекта
		if (!($this->isAdmin&&$this->isUser)) return false;

		$query = "ALTER TABLE `{$this->getTableName($table)}` ";
		
		$i = 0;
		foreach ($fields as $key => $value) {
			$query.= ($i ? ', ' : NULL); $i++;			
			if (!strcmp($value['type'], 'text'))
				$query.= "ADD `$key` TEXT NOT NULL";
			if (!strcmp($value['type'], 'integer') || !strcmp($value['type'], 'int'))
				$query.= "ADD `$key` INT NOT NULL";
			if (!strcmp($value['type'], 'float'))
				$query.= "ADD `$key` FLOAT NOT NULL";
			if (!strcmp($value['type'], 'items'))
				$query.= "ADD `$key` INT NOT NULL";
			if (!strcmp($value['type'], 'time'))
				$query.= "ADD `$key` TIMESTAMP NOT NULL";
		}
		
		return $this->sql->query($query);
	}
	
	function addIndex($table, $field, $type = '') { //сделать распознование $field - строка или массив(набор строк), сейчас только для строки
		if (!($this->isAdmin&&$this->isUser)) return false;
		
		$tableName = $this->getTableName($table);
		
		//проверка есть ли такой индекс
		//$query = "SHOW INDEX FROM `$tableName`";
		//foreach ($this->sql->get_results($query) as ... ) {	...
		//}
				
		if (!strcmp($type, 'fullText'))
			$query = "ALTER TABLE `$tableName` ADD FULLTEXT `$field` (`$field`)";
		else
			return;
			
		return $this->sql->query($query);				
	}
	
	function tableDropFields($table, $fields) { //setObjRec getObjRec
	//удаление полей объекта
		if (!($this->isAdmin&&$this->isUser)) return false;
	
		$query = "ALTER TABLE `{$this->getTableName($table)}` ";
		
		$i = 0;
		foreach ($fields as $key => $value) {
			$query.= ($i ? ', ' : NULL); $i++;
			if (is_int($key)) {
				$query.= "DROP `$value`";
				unset($fields[$key]);
				$fields[$value] = $value;
			}		
			else
				$query.= "DROP `$key`";
		}

		return $this->sql->query($query);
	}
	
// ITEMS --------------------------------------------------------------
	
	function getItemQuery($table) {
	// универсальная часть для getItems и getItem
		$query = "SELECT ";
		if (!count($this->fields))
			$query.= "*";
		else {
			$i = 0;
			foreach ($this->fields as $key => $value) {
				$query.= ($i ? ', ' : NULL); $i++;	
				if (is_int($key))
					$query.= "$value";
				else
					$query.= "$value AS `$key`";
			}
		}
		
		$query.= " FROM `{$this->getTableName($table)}`";
		
		if (!empty($this->from)) {
			$query.= " {$this->from}";
		}
		
		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}
		
		if (!empty($this->groupby)) {
			$query.= " GROUP BY {$this->groupby}";
		}
				
		if (!empty($this->orderby)) {
			$query.= " ORDER BY {$this->orderby}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}		

		return $query;
	}
	
	function getItems($table) {
	// Получить таблицу в виде массива объектов
	// список полей передается в виде массива $fields; если ключ текстовый, то он используется в запросе как для "AS $key"
		$query = $this->getItemQuery($table);
		
		$this->reset();
		
		return $this->sql->get_results($query);
	}

	function getItem($table, $id = NULL) {
	// Получить строку таблицы в виде объекта
	// список полей передается в виде массива $fields; если ключ текстовый, то он используется в запросе как для "AS $key"
		if(!empty($id)) $this->addWhere("`id` = '$id'");
		$this->setLimit(1);
		$query = $this->getItemQuery($table, $fields);

		$this->reset();

		return (object)$this->sql->get_row($query);
	}
	
	function insertItem($table, $values) {
		if (!$this->isUser) return false;	
		$queryColumns = '';
		$queryValues = '';
		$queryUpdate = '';
		
		$i = 0;
		foreach ($values as $key => $value) {
			//$value = $this->sql->quote($value);
			$queryColumns.= (($i)?', ':NULL)."`$key`";
			$queryValues.= (($i)?', ':NULL)."'$value'";
			$queryUpdate.= (($i)?', ':NULL)."`$key` = '$value'";
			$i++;
		}
				
		$query = "INSERT INTO `{$this->getTableName($table)}` ($queryColumns) VALUES ($queryValues) ".
				 "ON DUPLICATE KEY UPDATE $queryUpdate";

		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}	
		
		$this->reset();

		return $this->sql->query($query);		
	}
	
	function deleteItems($table) {
		if (!$this->isUser) return false;		
		$query = "DELETE FROM `{$this->getTableName($table)}`";
		
		if (!empty($this->from)) {
			$query.= " {$this->from}";
		}
		
		if (!empty($this->where)) {
			$query.= " WHERE {$this->where}";
		}	
		
		if (!empty($this->limit)) {
			$query.= " LIMIT {$this->limit}";
		}
		
		$this->reset();		
		
		return $this->sql->query($query);
	}
	

// inner --------------------------------------------------------------
	
	function getTableName($table) {
		return "{$this->cms_prefix}{$this->com_prefix}{$table}";
	}

}

?>