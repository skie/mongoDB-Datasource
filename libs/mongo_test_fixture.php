<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/view/1196/Testing>
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/view/1196/Testing CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.cake.tests.libs
 * @since         CakePHP(tm) v 1.2.0.4667
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.cake.tests.lib
 */
class MongoTestFixture extends Object {

/**
 * Name of the object
 *
 * @var string
 */
	var $name = null;

/**
 * Cake's DBO driver (e.g: DboMysql).
 *
 * @access public
 */
	var $db = null;

/**
 * Full Table Name
 *
 * @access public
 */
	var $table = null;

/**
 * Instantiate the fixture.
 *
 * @access public
 */
	function __construct() {
		App::import('Model', 'CakeSchema');
		$this->Schema = new CakeSchema(array('name' => 'MongoTestSuite', 'connection' => 'mongodb_test'));
		$this->db =& ConnectionManager::getDataSource('mongodb_test');
		$this->init();
	}

/**
 * Initialize the fixture.
 *
 * @param object	Cake's DBO driver (e.g: DboMysql).
 * @access public
 *
 */
	function init() {
		if (!isset($this->table)) {
			$this->table = Inflector::underscore(Inflector::pluralize($this->name));
		}

		if (!isset($this->primaryKey) && isset($this->fields['id'])) {
			$this->primaryKey = 'id';
		}
	}

/**
 * Run before all tests execute, should return SQL statement to create table for this fixture could be executed successfully.
 *
 * @param object	$db	An instance of the database object used to create the fixture table
 * @return boolean True on success, false on failure
 * @access public
 */
	function create(&$db) {
		//not required for mongodb
	}

/**
 * Run after all tests executed, should return SQL statement to drop table for this fixture.
 *
 * @param object	$db	An instance of the database object used to create the fixture table
 * @return boolean True on success, false on failure
 * @access public
 */
	function drop(&$db) {
		//just drop collection here
		$return = $this->db->drop($this->Model);
	}

/**
 * Run before each tests is executed, should return a set of SQL statements to insert records for the table
 * of this fixture could be executed successfully.
 *
 * @param object $db An instance of the database into which the records will be inserted
 * @return boolean on success or if there are no records to insert, or false on failure
 * @access public
 */
	function insert(&$db) {
		if (!isset($this->_insert)) {
			$values = array();
			$result = true;
			if (isset($this->records) && !empty($this->records)) {
				foreach ($this->records as $record) {
					$fields = array_keys($record);
					$revFields = array_flip($fields);
					
					$values = array_values($record);
					if (isset($revFields['_id'])) {
						$values[$revFields['_id']] = $this->getId($values[$revFields['_id']]);
					}
					$result &= $this->db->create($this->Model, $fields, $values);
				}
				return $result;
			}
			return true;
		}
	}

/**
 * Truncates the current fixture. Can be overwritten by classes extending CakeFixture to trigger other events before / after
 * truncate.
 *
 * @param object $db A reference to a db instance
 * @return boolean
 * @access public
 */
	function truncate(&$db) {
		//drop collection instead of trucate
		$return = $this->db->drop($this->Model);
		return true;
	}
	
/**
 * 
 */ 
	public function getId($id) {
		return $id; 
		$mongoId = new MongoId($id);
		return $mongoId . '';
	}
	
}
