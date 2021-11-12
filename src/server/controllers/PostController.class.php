<?php 
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';

class PostController extends Controller {

	public function get(array $params) : array {
		return array();
	}

	public function post(array $params) : array {
		$result = array("response" => 400, "data" => array("message" => "Cannot create thread."));
		$conn = (new DatabaseConnector())->getConnection();
		
		$threadUrl = end($params);
		$thread_id_query = "SELECT thread_id from threads WHERE thread_url = '$threadUrl' AND is_deleted != 1";
		$result = mysqli_query($conn, $thread_id_query);
		while ($row = mysqli_fetch_assoc($result)) {
			$thread_id = $row["thread_id"];
		}
		
		$get_user_query = "SELECT id FROM users WHERE username = '".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $get_user_query);
		while ($row = mysqli_fetch_assoc($result)) {
			$user_id = $row["id"];
		}

		$caseNumber = (int)$params[0];
        switch ($caseNumber) {
            case 1:
				$sql = "INSERT INTO posts(user_id, thread_id, title, body, post_image, media_url) 
						VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."')";
				mysqli_query($conn, $sql);
				break;

            case 2:
                $sql = "INSERT INTO posts(user_id, thread_id, title, body, post_image) 
						VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."', '".$params[3]."')";
				mysqli_query($conn, $sql);
                break;
            case 3:
                $sql = "INSERT INTO posts(user_id, thread_id, title, body, media_url) 
						VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."', '".$params[3]."')";
				mysqli_query($conn, $sql);
                break;
            case 4:
                $sql = "INSERT INTO posts(user_id, thread_id, title, post_image, media_url) 
						VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."', '".$params[3]."')";
				mysqli_query($conn, $sql);
                break;
            case 5:
				$sql = "INSERT INTO posts(user_id, thread_id, title, body) VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."')";
				mysqli_query($conn, $sql);
				break;
            case 6:
				$sql = "INSERT INTO posts(user_id, thread_id, title, post_image) VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."')";
				mysqli_query($conn, $sql);
				break;
            case 7:
				$sql = "INSERT INTO posts(user_id, thread_id, title, media_url) VALUES ($user_id, $thread_id, '".$params[1]."', '".$params[2]."')";
				mysqli_query($conn, $sql);
				break;
		}
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