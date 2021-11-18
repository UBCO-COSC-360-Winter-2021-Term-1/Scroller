<?php 
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';

class ThreadController extends Controller {
  public function get(array $params) : array {
		return array();
	}

	public function getAllThreads() : array {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT threads.thread_title, threads.thread_url, threads.background_picture, threads.thread_picture FROM threads";

		$response = mysqli_query($conn, $sql);

		$result = array();

		while($row = mysqli_fetch_assoc($response)) {
			array_push($result, [
				"thread_title" => $row['thread_title'],
				"thread_url" => $row['thread_url'],
				"thread_background_picture" => $row['background_picture'],
				"thread_cover_picture" => $row['thread_picture']
			]);
		}
		mysqli_close($conn);
		return $result;
	}

	public function getThreadByQuery(string $query) : array {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT threads.thread_title, threads.thread_url, threads.background_picture, threads.thread_picture FROM threads WHERE thread_title LIKE '%$query%' OR thread_url LIKE '%$query%'";
		$response = mysqli_query($conn, $sql);

		$result = array();

		while($row = mysqli_fetch_assoc($response)) {
			array_push($result, [
				"thread_title" => $row['thread_title'],
				"thread_url" => $row['thread_url'],
				"thread_background_picture" => $row['background_picture'],
				"thread_cover_picture" => $row['thread_picture']
			]);
		}
		mysqli_close($conn);
		return $result;
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

	public function findThreadByUrl(string $url) : bool {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT thread_url FROM threads where thread_url = '$url' AND is_deleted = 0";
		
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result)) {
			mysqli_close($conn);
			return true;
		}
		mysqli_close($conn);
		return false;
	}

	public function update(array $params) : array {
		return array();
	}

	public function delete(array $params) : array {

		$conn = (new DatabaseConnector())->getConnection();

		$sql = "UPDATE threads SET is_deleted = 1 WHERE thread_id = ".$params[0]."";
		mysqli_query($conn, $sql);

		$sql = "SELECT id FROM users WHERE username = '".$_SESSION['USERNAME']."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$admin = mysqli_fetch_row($result);

		$sql = "SELECT owner_id FROM threads WHERE thread_id = ".$params[0]." LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$row = mysqli_fetch_row($result);

		$sql = "INSERT INTO notifications(user_id, replied_user_id, action_type, thread_id) VALUES (".$row[0].", ".$admin[0].", 6, ".$params[0].")";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		return array("response" => 200);
	}

	public function hide(array $params) : array {

		$conn = (new DatabaseConnector())->getConnection();

		$sql = "UPDATE threads SET is_locked = 1 WHERE thread_id = ".$params[0]."";
		mysqli_query($conn, $sql);

		$sql = "SELECT id FROM users WHERE username = '".$_SESSION['USERNAME']."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$admin = mysqli_fetch_row($result);

		$sql = "SELECT owner_id FROM threads WHERE thread_id = ".$params[0]." LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$row = mysqli_fetch_row($result);

		$sql = "INSERT INTO notifications(user_id, replied_user_id, action_type, thread_id) VALUES (".$row[0].", ".$admin[0].", 6, ".$params[0].")";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		return array("response" => 200);
	}

	public function restore(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();

		$sql = "UPDATE threads SET is_locked = 0, is_deleted = 0 WHERE thread_id = ".$params[0]."";
		mysqli_query($conn, $sql);

		mysqli_close($conn);
		return array("response" => 200);
	}

	public function findById(int $id) : array {
		return array();
	}

	public function findAll(array $params) : array {

		return array();
	}

	public function getTitle(string $params): string {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT thread_title FROM threads WHERE thread_url = '$params' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$title = mysqli_fetch_row($result);
		return $title[0];
	}

	public function getThread(string $threadUrl) {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT threads.thread_title, threads.background_picture, threads.thread_picture, threads.is_locked, CASE WHEN EXISTS(SELECT user_threads.user_id FROM user_threads JOIN users ON user_threads.user_id = users.id WHERE users.username = '".$_SESSION["USERNAME"]."' AND user_threads.thread_id = threads.thread_id) THEN 1 ELSE 0 END as isSubscribed FROM threads WHERE threads.thread_url = '$threadUrl'";
		$response = mysqli_query($conn, $sql);

		$result = array();

		while($row = mysqli_fetch_assoc($response)) {
			array_push($result, [
				"thread_title" => $row['thread_title'],
				"thread_background" => $row['background_picture'],
				"thread_profile" => $row['thread_picture'],
				"is_locked" => $row['is_locked'],
				"isSubscribed" => $row['isSubscribed']
			]);
		}
		mysqli_close($conn);
		return $result;
	}

	public function userThreadsOperations(array $params): array {
		$conn = (new DatabaseConnector())->getConnection();
		$dataStatus = (int)$params[1];
		$get_user_query = "SELECT id FROM users WHERE username = '".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $get_user_query);
		while ($row = mysqli_fetch_assoc($result)) {
			$user_id = $row["id"];
		}
		
		$sql = "SELECT thread_id FROM threads WHERE thread_url = '$params[0]' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result)) {
			$thread_id = $row["thread_id"];
		}
		
		switch ($dataStatus) {
			case 0:
				$sql = "INSERT INTO user_threads(thread_id, user_id) VALUES ($thread_id, $user_id)";
				break;
			case 1:
				$sql = "DELETE FROM user_threads WHERE thread_id=$thread_id AND user_id=$user_id";
				break;
		}

		mysqli_query($conn, $sql);
		mysqli_close($conn);
		return array("response" => 200);
	}

	public function getTopUsers(string $url): array {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT threads.thread_id FROM threads WHERE threads.thread_url = '$url' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result)) {
			$thread_id = $row["thread_id"];
		}
		
		$sql = "SELECT count(posts.user_id), users.id, users.username, users.avatar_url FROM posts JOIN users ON posts.user_id=users.id WHERE posts.thread_id=$thread_id GROUP BY (posts.user_id) ORDER BY count(posts.user_id) DESC LIMIT 5";
		$result = mysqli_query($conn, $sql);
		$resultArray = array();
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($resultArray, [
				"count" => $row["count(posts.user_id)"],
				"username" => $row["username"],
				"avatar_url" => $row["avatar_url"],
				"userId" => $row["id"]
			]);
		}
		return $resultArray;
	}
}
?>