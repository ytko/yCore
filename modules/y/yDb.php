<?php defined ('_YEXEC')  or  die();

require_once 'ySql.php';

class yDb extends ySql {
	public $sql;
	public static $static_sql;

	public function __construct($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql', $forced = false) {
		// Overriding emulation:
		if (is_object($user))
			// Second (not last) argument is $forced if object is given
			$forced = $password;
				
		if ($forced or is_null(self::$static_sql)) {
			// Create new ezSQL object if it's not crated yet or in forced mode
			$this->init($user, $password, $name, $host, $driver);
		}
		else {
			// Link object if already connected
			$this->sql = &self::$static_sql;
		}
	}

	public function init($user = NULL, $password = NULL, $name = NULL, $host = NULL, $driver = 'mysql') {
		// Overriding emulation:
		if (is_object($user)) {
			// Use ezSQL object if object is given
			$this->sql = $user;
			//get_class($user); //TODO: add class check
		}
		else {
			// Create ezSQL object if no object is given
			
			// Include ezSQL core and ezSQL database specific component	
			include_once "ezsql/ez_sql_core.php";
			include_once "ezsql/ez_sql_$driver.php";	
			
			$className = "ezSQL_$driver";
			
			if (isset($user))
				$this->sql = new $className($user, $password, $name, $host);
			elseif (isset(ySettings::$db))
				$this->sql = new $className(ySettings::$db->user, ySettings::$db->password, ySettings::$db->name, ySettings::$db->host);
		}
		
		// Static copy of ezSQL object for optimization
		self::$static_sql = &$this->sql;
		return $this;
	}

	// Query processing functions

	public function select($query = NULL) { //alias for selectResults
		return $this->selectResults($query);
	}

	public function selectResults($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_results($query);
		if(!$result) $result = array();
		return $result;
	}

	public function selectCol($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_col($query);
		if(!$result) $result = array();
		return $result;
	}

	public function selectRow($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_row($query);
		if(!$result) $result = (object)array();
		return $result;
	}

	public function selectCell($query = NULL) {
		if(!is_string($query)) $query = $this->selectQuery($query);
		$result = $this->sql->get_var($query);
		return $result;
	}

	public function selectVar($query = NULL) { //alias for selectResults
		return $this->selectCell($query);
	}

	public function insert($query = NULL) {
		// case input ($query) is array of rows
		if(is_array($query)) {
			foreach ($query as $row) {
				$this->insert($row);
			}
		}
		// case input ($query) is a single row
		elseif(is_object($query)) {
			$this->values = $query;
			$this->insert();
		}
		// case input ($query) is not set or is a string
		else
			return
				$this->sql->query(
					($query and is_string($query))
						? $query
						: $this->insertQuery($query)
				);
	}

	public function update($query = NULL) {
		$query = ($query and is_string($query))
					? $query
					: $this->updateQuery($query);
		
		return $query ? $this->sql->query($query) : NULL;
	}

	public function delete($query = NULL) {
		return
			$this->sql->query(
				($query and is_string($query))
					? $query
					: $this->deleteQuery($query)
			);
	}

/*
	function query($query = NULL) {
		return $this->sql->query($query ? $query : $this->getQuery());
	}
*/
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