<?php 
session_start();
include $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
include $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';

class ThreadController extends Controller {
    public function get(array $params) : array {
		return array();
	}

	public function post(array $params) : array {
		$result = array("response" => 400, "data" => array("message" => "Cannot create thread."));
		
		$conn = (new DatabaseConnector())->getConnection();

		$get_user_query = "SELECT id FROM users WHERE username = '".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $get_user_query);

		while ($row = mysqli_fetch_assoc($result)) {
			$user_id = $row["id"];
		}

		$sql = "INSERT INTO threads(thread_title, thread_url, background_picture, thread_picture, owner_id) 
				VALUES ('$params[0]', '$params[1]', '$params[2]', '$params[3]', $user_id)";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		return array("response" => 200);
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

		return array();
	}
}
?>