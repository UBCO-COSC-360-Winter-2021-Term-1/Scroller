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