<?php 
require_once SERVER_DIR.'/helpers/Controller.class.php';

class PostController extends Controller {

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

		$result = array(
			"response" => 400,
			"data" => array()
		);

		if ($_SERVER['REQUEST_METHOD'] == "GET") {

			$result["response"] = 200;
			return $result;
		}

		return $result;
	}
}
?>