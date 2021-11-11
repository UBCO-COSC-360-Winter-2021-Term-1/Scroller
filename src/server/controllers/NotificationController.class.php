<?php 
	@session_start();
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';


	class NotificationController extends Controller {
		public function get(array $params) : array {
			$conn = (new DatabaseConnector())->getConnection();

			$sql = "SELECT notifications.replied_user_id, notification_types.description, notifications.action_type, DATE_FORMAT(notifications.created_at, '%Y-%m-%d') as date_created, u2.username as replied_username, threads.thread_url  FROM `notifications` JOIN `notification_types` ON notifications.action_type = notification_types.id JOIN users ON notifications.user_id = users.id JOIN users as u2 ON notifications.replied_user_id = u2.id JOIN threads ON notifications.thread_id = threads.thread_id WHERE users.username = '".$_SESSION["USERNAME"]."' ORDER BY notifications.created_at DESC";
			$response = mysqli_query($conn, $sql);

			$notifications = array();
			while($row = mysqli_fetch_assoc($response)) {
				
				if (date("Y-m-d", strtotime('today')) == $row["date_created"]) {
					if (!array_key_exists("Today", $notifications)) {
						$notifications["Today"][0] = [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						];
					} else {
						array_push($notifications["Today"], [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						]);
					}
				} else if (date("Y-m-d", strtotime('yesterday')) == $row["date_created"]) {
					if (!array_key_exists("Yesterday", $notifications)) {
						$notifications["Yesterday"][0] = [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						];
					} else {
						array_push($notifications["Yesterday"], [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						]);
					}
				} else if (strtotime("this week") <= strtotime($row["date_created"])) {
					if (!array_key_exists("This Week", $notifications)) {
						$notifications["This Week"][0] = [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						];
					} else {
						array_push($notifications["This Week"], [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						]);
					}
				} else {
					if (!array_key_exists("Long Time Ago", $notifications)) {
						$notifications["Long Time Ago"][0] = [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						];
					} else {
						array_push($notifications["Long Time Ago"], [
							"replied_user_id" => $row["replied_user_id"],
							"description" => $row["description"],
							"action_type" => $row["action_type"],
							"replied_username" => $row["replied_username"],
							"thread_url" => $row["thread_url"]
						]);
					}
				}
			}
			mysqli_close($conn);
			return $notifications;
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