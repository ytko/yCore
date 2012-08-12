<?php

class ySql { 
	public
			$mode, // Can be 'select', 'insert', 'insert_or_update', etc. Changes with last ->select(), ->insert(), etc. method called.
			$fields, // Used for SELECT
			$table, // Used for SELECT FROM $table, INSERT INTO $table, etc.
			$values, // Used for INSERT and UPDATE
			$join, $where, $option, $group, $having, $order, $limit;
			
	// Query definition

	// Set table name
	public function table($table) {
		$this->table = $table;
		return $this;		
	}
	
	// Set join tables (for select)
	public function join($join) {
		$this->join = $join;
		return $this;		
	}
	
	// Set where
	public function where($key, $args = NULL) {
		// override:
		// function has two arguments: use them as `$key` field = $value
		if (isset($args)) {
			if (is_array($args) or is_object($args)) {
				$args = (object)$args;
				$value = $args->value;
				if(isset($args->collation))	$collation = $args->collation;
				else						$collation = '=';
			} else {
				$value = $args;
				$collation = '=';
			}
			
			if ($collation == 'like') {
				$collation = 'LIKE';
				$value = "%$value%";
			} else {
				$collation = '=';
			}

			if(is_string($value)) $value = $this->quote($value);

			$this->where.= ($this->where ? ' AND ' : '')."`$key` $collation '$value'";
		}
		// function has one argument: use it as simple string
		else
			$this->where = $key;

		return $this;
	}

	// Set option	
	public function option($option) {
		$this->option = $option;
		return $this;		
	}

	// Set group	
	public function group($group) {
		$this->group = $group;
		return $this;		
	}

	// Set having	
	public function having($having) {
		$this->having = $having;
		return $this;		
	}
	
	// Set order
	public function order($order, $direction = NULL) {
		if (isset($direction) && strtoupper($direction) == 'ASC')
			$direction = 'ASC';
		elseif (isset($direction) && strtoupper($direction) == 'DESC')
			$direction = 'DESC';
		else
			$direction = NULL;
		
		$this->order.= 
				($this->order ? ', ' : NULL).
				"`$order`".
				($direction ? ' '.$direction : NULL);
		
		return $this;		
	}

	// Set limit
	public function limit($limit, $offset = NULL) {
		if (isset($offset))
			//if (is_numeric($limit) && is_numeric($rows))
				$this->limit = "$offset, $limit";
		else
			//if (is_numeric($limit))
				$this->limit = $limit;
		return $this;
	}

	// set query type and fields
	// TODO: safe values arrays
	// TODO: array and object input
	
	// query generating

	public function selectQuery($fields = NULL) {
		// Generating field list for sql-query
		$fields = '';
		if (is_array($this->fields) and !empty($this->fields))
			foreach($this->fields as $field) {
				$fields.= ($fields ? ', ': NULL)."$field";
			}
		else
			$fields = '*';

		// Return query
		return
			"SELECT $fields".
			" FROM `{$this->table}`".
			($this->join ? ' '.$this->join : NULL).
			($this->where ? ' WHERE '.$this->where : NULL).
			($this->group ? ' GROUP BY '.$this->group : NULL).
			($this->having ? ' HAVING '.$this->having : NULL).
			($this->order ? ' ORDER BY '.$this->order : NULL).
			($this->limit ? ' LIMIT '.$this->limit : NULL).
			';';		
	}
	
	public function insertQuery($values = NULL) {
		// Check if $this->values array is associative
		//$keys = array_keys($this->values);
		//$isAssociative = array_keys($keys) !== $keys;
		
		// Generating lists of fields and their values
		// TODO: exception on empty $this->values
		$fields = '';
		$values = '';
		foreach($this->values as $key => $value) {
			if (isset($value)) {
				$key = $this->quote($key);
				$value = $this->quote($value);
				// Generating field list if $this->values array is associative
				//if ($isAssociative)
					$fields.= ($fields ? ', ': NULL)."`$key`";

				// Generating values list in both cases
				$values.= ($values ? ', ': NULL)."'$value'";
			}
		}
		
		
		return
			"INSERT INTO `{$this->table}`".
			($fields ? ' ('.$fields.')' : NULL).
			($values ? ' VALUES ('.$values.')' : NULL).
			';';
	}
	
	public function updateQuery() {
		$set = '';
						
		foreach($this->values as $key => $value) {
			$key = $this->quote($key);
			$value = $this->quote($value);
			$set.= ($set ? ', ': NULL)."`$key` = '$value'";
		}

		if ($set)
			return
				"UPDATE `{$this->table}`".
				' SET '.$set.
				($this->where ? ' WHERE '.$this->where : NULL).
				($this->option ? ' OPTION '.$this->option : NULL).
				';';
		else
			return NULL;
	}
	
	public function deleteQuery() {
/*
DELETE [LOW_PRIORITY] [QUICK] [IGNORE] FROM tbl_name
    [PARTITION (partition_name,...)]
    [WHERE where_condition]
    [ORDER BY ...]
    [LIMIT row_count]
*/
		return
			"DELETE FROM `{$this->table}`".
			($this->where ? ' WHERE '.$this->where : NULL).
			($this->order ? ' ORDER BY '.$this->order : NULL).
			($this->limit ? ' LIMIT '.$this->limit : NULL).
			';';
	}
	
	// Field values defenition
	
	public function values($values = NULL) {
		if(is_array($values) or is_object($values)) {
			foreach($values as $key => $value) {
				$this->value($key, $value);
			}
		}
		return $this;
	}
	
	public function value($field, $value) {
		if (is_array($this->values))
			$this->values = (object)$this->values;
		elseif (!is_object($this->values))
			$this->values = (object)array();
		
		$this->values->$field = $this->quote($value);
		
		return $this;
	}
	
	public function clearValues() {
		$this->values = (object)array();
		return $this;
	}
	
	public function fields($fields = NULL) { // TODO: merge with existed values
		if($fields) $this->fields = $fields;
		return $this;
	}
	
	public function field($field) {
		if(!is_array($this->fields))
			$this->fields = array();

		$this->fields[] = $this->quote($field);
		
		return $this;
	}

	// Safe query self methods
	
	function quote($string) {
		return
			addslashes($string);
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
