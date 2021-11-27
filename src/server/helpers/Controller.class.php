<?php 

abstract class Controller {	
	/**
	 * get
	 *
	 * @param  array $params
	 * @return array
	 */
	abstract public function get(array $params) : array;	

	/**
	 * post
	 *
	 * @param  array $params
	 * @return array
	 */
	abstract public function post(array $params) : array;	

	/**
	 * update
	 *
	 * @param  array $params
	 * @return array
	 */
	abstract public function update(array $params) : array;	

	/**
	 * delete
	 *
	 * @param  array $params
	 * @return array
	 */
	abstract public function delete(array $params) : array;	

	/**
	 * findById
	 *
	 * @param  int $id
	 * @return array
	 */
	abstract public function findById(int $id) : array;
	
	/**
	 * findAll
	 *
	 * @param  array $params
	 * @return array
	 */
	abstract public function findAll(array $params) : array;
}
?>