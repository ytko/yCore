<?php defined ('_YEXEC')  or  die();

yCore::load('yDb');

class objectDb extends yDb {
	public function filters($filters) { //TODO: make filter yFilterClass, and then rename methon to "where"
		foreach($filters as $filter) // define WHERE from filters
			if($filter->type == 'field' && $filter->value)
				$this->where($filter->field,
						array(
							'value' => "$filter->value",
							'collation' => $filter->collation,
						));
			elseif($filter->type == 'page') {
				if(!$filter->value) $filter->value = 1;
				$this->limit($filter->rows, $filter->rows*($filter->value-1));
			}
			elseif($filter->type == 'order' || $filter->type == 'sort') {
				if($filter->direction)
					$direction = $filter->direction;
				else
					$direction = $filter->value; // safe, because order() checks $direction argument
				
				if ($direction)
					$this->order($filter->field, $direction);
			}

		return $this;
	}
	
	protected function valuesFromRow($values, $fields) {
		// add row values to query
		foreach($fields as $field) { // go through object fields
			$value = $values->{$field->key}; // value of field in current row
			if(isset($value)) // if value of field is defined add it to INSERT (or UPDATE) request
				$this->value($field->key, $value);
		}
		return $this;
	}

	public function query_addField($field) {
		$null = ' NOT NULL';
		
		if		($field->type == 'int')			$type = "INT";
		elseif	($field->type == 'id')			$type = "INT AUTO_INCREMENT";
		elseif	($field->type == 'float')		$type = "FLOAT";
		elseif	($field->type == 'currency')	$type = "DECIMAL(18,2)";
		elseif	($field->type == 'string')		$type = "VARCHAR(255)";
		elseif	($field->type == 'text')		$type = "TEXT";
		elseif	($field->type == 'list')		$type = "INT";
		
		if($field->key && $type)
			$query = "`{$field->key}` {$type}{$null}";

		return $query;
	}
	
	/*public function alterAdd($object) { // create table
		$query = NULL;
		foreach($object->fields as $field) {
			$query.=
				"ALTER TABLE `{$object->table}` ADD IF NOT EXISTS ".
				$this->query_addField($field).
				';';
		}
				
		$this->sql->query($query);
		
		return $this;
	}*/
	
	public function create($object) { // create table
		$unique = array();
		$primary = array();
		
		$query = NULL;
		foreach($object->fields as $field) {
			$query.=
				($query ? ', ' : NULL).
				$this->query_addField($field);
			
			if	($field->type == 'id') {
				//$unique[] = $field->key;
				$primary[] = $field->key;
			}
		}

		if($primary) {
			$primaryQuery = NULL;
			foreach($primary as $key) {
				if ($primaryQuery) $primaryQuery.= ', ';
				$primaryQuery.= "`$key`";
			}
			$primaryQuery = ", PRIMARY KEY ($primaryQuery)";
		}
		
		if($unique) {
			$uniqueQuery = NULL;
			foreach($unique as $key) {
				if ($uniqueQuery) $uniqueQuery.= ', ';
				$uniqueQuery.= "`$key`";
			}
			$uniqueQuery = ", UNIQUE ($uniqueQuery)";
		}
		//, PRIMARY KEY ( `{$field->key}` ) ,
		
		$query = "CREATE TABLE `{$object->table}` ({$query}{$primaryQuery}{$uniqueQuery}) ENGINE = MYISAM;";
				
		$this->sql->query($query);
		
		return $this;
	}

// ---- replace methods (like insert or update) ---------------------------------------------------

	public function replace($object = NULL, $mode = NULL) { // overrrides replace($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->replaceObject($object, $mode);
		else { // in other case do not override
			$method = 'replace'.$mode; //TODO: yDb::replace()
			return parent::$method($object);
		}
	}

	public function replaceObject($object = NULL, $mode = '') { //insert or update
		$this
			->table($object->table) // define table
			->filters($object->filters); // set where

		foreach($object->values as $row) { // go through array of rows to insert
			$this
				->clearValues() // reset array of values
				->values($row, $object->fields); // set values

			// UPDATE if has where clause and INSERT if not
			if ($this->where)
				$method = 'update'.$mode;
			else
				$method = 'insert'.$mode;
			$iResult = parent::$method();
			if (is_string($iResult)) $result.= $iResult;
		}

		return $result;
	}

	public function replaceQuery($object) { //alias
		return $this->replace($object, 'Query');
	}	
	
// ---- replace methods (like insert or update) ---------------------------------------------------

	public function insert($object = NULL, $mode = NULL) { // overrrides replace($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->replaceObject($object, $mode);
		else { // in other case do not override
			$method = 'insert'.$mode; //TODO: yDb::replace()
			return parent::$method($object);
		}
	}

	public function insertObject($object = NULL, $mode = '') { //insert or update
		$this
			->table($object->table); // define table

		foreach($object->values as $row) { // go through array of rows to insert
			$this
				->clearValues() // reset array of values
				->valuesFromRow($row, $object->fields); // set values

			// UPDATE if has where clause and INSERT if not
			$method = 'insert'.$mode;
			$iResult = parent::$method();
			if (is_string($iResult)) $result.= $iResult;
		}

		return $result;
	}

	public function insertQuery($object) { //alias
		return $this->insert($object, 'Query');
	}	

// ---- select methods ----------------------------------------------------------------------------

	public function select($object = NULL, $mode = 'Results') { // overrrides select($object)
		if (is_a($object, 'yObject')) // if $object is child of yObject
			return $this->selectObject($object, $mode);
		else { // in other case do not override
			$method = 'select'.$mode;
			return parent::$method($object);
		}
	}

	public function selectObject($object = NULL, $mode = 'Results') {
		$this
			->table($object->table)
			->filters($object->filters); // define table
			
			if(!$this->fields)
				foreach ($object->fields as $field) // go through object fields
					$this->field($field->key); // add field to selection
			
			// get total values count
			if ($mode == 'Results' || $mode == 'Cols') {
				$db = clone($this);
				$db->limit('');
				$db->fields = NULL;
				$db->field('COUNT(*) as `count`');
				$object->rowsTotal = $db->selectCell();
			}
			
			$method = 'select'.$mode; // method to use: selectResults, selectRow, etc.
			return parent::$method();
	}

	// Aliases

	public function selectResults($object = NULL) { //alias
		return $this->select($object, 'Results');
	}

	public function selectRow($object = NULL) { //alias
		return $this->select($object, 'Row');
	}

	public function selectCol($object = NULL) { //alias
		return $this->select($object, 'Col');
	}

	public function selectCell($object = NULL) { //alias
		return $this->select($object, 'Cell');
	}

	public function selectQuery($object = NULL) { //alias
		return $this->select($object, 'Query');
	}
}

/*
 * Copyright 2012 Roman Exempliarov. 
 *
 * This file is part of yCore framework.
 *
 * yCore is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 2.1 of the License, or (at your option)
 * any later version.
 * 
 * yCore is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with yCore. If not, see http://www.gnu.org/licenses/.
 */

?>
