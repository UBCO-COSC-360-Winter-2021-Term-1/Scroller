<?php 
	@session_start();
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';


	class AdminController extends Controller {
		public function get(array $params) : array {
			return array();
		}
		
		public function getStats() : array {
			$conn = (new DatabaseConnector())->getConnection();
			$sql = "SELECT 'users' AS table_name, COUNT(*) as result FROM users UNION SELECT 'threads' AS table_name, COUNT(*) as result FROM threads UNION SELECT 'comments' AS table_name, COUNT(*) as result FROM comments UNION SELECT 'posts' AS table_name, COUNT(*) as result FROM posts";
			$response = mysqli_query($conn, $sql);
			$result = array();
			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, $row['result']);
			}

			mysqli_close($conn);
			return $result;
		}

		public function getAllThreads(array $params) : array {
			$conn = (new DatabaseConnector())->getConnection();
			$sql = "SELECT threads.thread_id, threads.thread_title, threads.thread_url, DATE_FORMAT((threads.created_at), '%M %D, %Y') as created_date, users.id as ownerId, users.username as ownerName, threads.is_locked, threads.is_deleted, COUNT(user_threads.user_id) as members FROM `threads` JOIN users ON threads.owner_id = users.id LEFT JOIN user_threads ON user_threads.thread_id = threads.thread_id GROUP BY threads.thread_id ORDER BY threads.thread_id";

			$response = mysqli_query($conn, $sql);
			$result = array();

			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"thread_id" => $row['thread_id'],
					"thread_title" => $row['thread_title'],
					"thread_url" => $row['thread_url'],
					"created_date" => $row['created_date'],
					"ownerId" => $row['ownerId'],
					"ownerName" => $row['ownerName'],
					"is_locked" => $row['is_locked'],
					"is_deleted" => $row['is_deleted'],
					"members" => $row['members']
				]);
			}
			mysqli_close($conn);
			return $result;
		}

		public function getAllUsers() : array {
			$conn = (new DatabaseConnector())->getConnection();
			$sql = "SELECT id, username, DATE_FORMAT((created_at), '%M %D, %Y') as regdate, email, is_email_confirmed, is_admin, is_account_disabled FROM users";

			$response = mysqli_query($conn, $sql);
			$result = array();

			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"id" => $row['id'],
					"username" => $row['username'],
					"regdate" => $row['regdate'],
					"email" => $row['email'],
					"is_email_confirmed" => $row['is_email_confirmed'],
					"is_admin" => $row['is_admin'],
					"is_account_disabled" => $row['is_account_disabled']
				]);
			}
			mysqli_close($conn);
			return $result;
		}

		public function post(array $params) : array {
			return array();
		}

		public function updateUserAccess(bool $param, int $userId) : void {
			$conn = (new DatabaseConnector())->getConnection();
			
			if ((int)$param === 1) {
				$sql = "UPDATE users SET is_account_disabled = 1 WHERE id = $userId";
				mysqli_query($conn, $sql);
			} else if ((int)$param === 0){
				$sql = "UPDATE users SET is_account_disabled = 0 WHERE id = $userId";
				mysqli_query($conn, $sql);
			}
			mysqli_close($conn);
		}

		public function updateUserAdmin(bool $param, int $userId) : void {
			$conn = (new DatabaseConnector())->getConnection();
			
			if ((int)$param === 1) {
				$sql = "UPDATE users SET is_admin = 1 WHERE id = $userId";
				mysqli_query($conn, $sql);
			} else if ((int)$param === 0){
				$sql = "UPDATE users SET is_admin = 0 WHERE id = $userId";
				mysqli_query($conn, $sql);
			}
			mysqli_close($conn);
		}

		public function searchThreadByTitle(string $query) : array {
			$conn = (new DatabaseConnector())->getConnection();
			$sql = "SELECT threads.thread_id, threads.thread_title, threads.thread_url, DATE_FORMAT((threads.created_at), '%M %D, %Y') as created_date, users.id as ownerId, users.username as ownerName, threads.is_locked, threads.is_deleted, COUNT(user_threads.user_id) as members FROM `threads` JOIN users ON threads.owner_id = users.id JOIN user_threads ON user_threads.thread_id = threads.thread_id WHERE threads.thread_title LIKE '%$query%' GROUP BY threads.thread_id ORDER BY threads.thread_id";

			$response = mysqli_query($conn, $sql);
			$result = array();
			$found = false;

			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"thread_id" => $row['thread_id'],
					"thread_title" => $row['thread_title'],
					"thread_url" => $row['thread_url'],
					"created_date" => $row['created_date'],
					"ownerId" => $row['ownerId'],
					"ownerName" => $row['ownerName'],
					"is_locked" => $row['is_locked'],
					"is_deleted" => $row['is_deleted'],
					"members" => $row['members']
				]);
				$found = true;
			}
			mysqli_close($conn);
			if (!$found) return array("response" => 400);
			return ["response" => 200, "data" => [$result]];
		}

		public function searchUsersByUsername(string $query) : array {
			$conn = (new DatabaseConnector())->getConnection();
			$sql = "SELECT id, username, DATE_FORMAT((created_at), '%M %D, %Y') as regdate, email, is_email_confirmed, is_admin, is_account_disabled FROM users WHERE username LIKE '%".$query."%'";

			$response = mysqli_query($conn, $sql);
			$result = array();
			$found = false;

			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"id" => $row['id'],
					"username" => $row['username'],
					"regdate" => $row['regdate'],
					"email" => $row['email'],
					"is_email_confirmed" => $row['is_email_confirmed'],
					"is_admin" => $row['is_admin'],
					"is_account_disabled" => $row['is_account_disabled']
				]);
				$found = true;
			}
			mysqli_close($conn);
			if (!$found) return array("response" => 400);
			return ["response" => 200, "data" => [$result]];
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