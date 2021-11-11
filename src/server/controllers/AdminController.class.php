<?php 
	@session_start();
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';


	class AdminController extends Controller {
		public function get(array $params) : array {
			return array();
		}
	
		public function post(array $params) : array {
			return array();
		}
	
		public function update(array $params) : array {
			return array();
		}
	
		public function delete(array $params) : array {
			return array();
		}
	
		public function findById(int $id) : array {
			return array();
		}
	
		public function findAll(array $params) : array {
		}
	}
?>