<?php 
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';

class CommentController extends Controller {

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

	public function getCommentByQuery(string $query) : array {
		$conn = (new DatabaseConnector())->getConnection();
		if(!isset($_SESSION['USERNAME'])) {
			$sql = "SELECT comments.comment_id, comments.body, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(comments.created_at) as createdFromNowInSeconds, users.username, users.id as ownerId, users.avatar_url,
			CASE WHEN EXISTS(SELECT comment_votes.user_id FROM comment_votes WHERE comment_votes.user_id = -1 AND comments.comment_id = comment_votes.comment_id) THEN 1 ELSE 0 END as voted,
			IF ((SELECT comment_votes.vote FROM comment_votes WHERE comment_votes.user_id = -1 AND comments.comment_id = comment_votes.comment_id AND comment_votes.vote = 1), 1, -1) as voteType,
			(SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 1 AND comments.comment_id = comment_votes.comment_id) - (SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 0 AND comments.comment_id = comment_votes.comment_id) as numOfVotes
			FROM comments JOIN users ON comments.user_id = users.id JOIN threads ON threads.thread_id = comments.thread_id LEFT JOIN comment_votes ON comment_votes.comment_id = comments.comment_id 
			WHERE comments.is_hidden = 0 AND comments.is_deleted = 0 AND comments.body LIKE '%$query%' GROUP BY comments.comment_id ORDER BY numOfVotes DESC";
			$response = mysqli_query($conn, $sql);

			$result = array();

			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"comment_id" => $row['comment_id'],
					"body" => $row['body'],
					"timestamp" => $row['createdFromNowInSeconds'],
					"username" => $row['username'],
					"ownerId" => $row['ownerId'],
					"avatar_url" => $row['avatar_url'],
					"isVoted" => 0,
					"typeVote" => 0,
					"numOfVotes" => $row['numOfVotes']
				]);
			}
			mysqli_close($conn);
			return $result;
		}
		$sql = "SELECT id FROM users WHERE username='".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$user = mysqli_fetch_row($result);
		$userId = $user[0];
		$sql = "SELECT comments.comment_id, comments.body, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(comments.created_at) as createdFromNowInSeconds, users.username, users.id as ownerId, users.avatar_url,
		CASE WHEN EXISTS(SELECT comment_votes.user_id FROM comment_votes WHERE comment_votes.user_id = $userId AND comments.comment_id = comment_votes.comment_id) THEN 1 ELSE 0 END as voted,
		IF ((SELECT comment_votes.vote FROM comment_votes WHERE comment_votes.user_id = $userId AND comments.comment_id = comment_votes.comment_id AND comment_votes.vote = 1), 1, -1) as voteType,
		(SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 1 AND comments.comment_id = comment_votes.comment_id) - (SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 0 AND comments.comment_id = comment_votes.comment_id) as numOfVotes
		FROM comments JOIN users ON comments.user_id = users.id JOIN threads ON threads.thread_id = comments.thread_id LEFT JOIN comment_votes ON comment_votes.comment_id = comments.comment_id 
		WHERE comments.is_hidden = 0 AND comments.is_deleted = 0 AND comments.body LIKE '%$query%' GROUP BY comments.comment_id ORDER BY numOfVotes DESC";
		$response = mysqli_query($conn, $sql);

		$result = array();

		while($row = mysqli_fetch_assoc($response)) {
			array_push($result, [
				"comment_id" => $row['comment_id'],
				"body" => $row['body'],
				"timestamp" => $row['createdFromNowInSeconds'],
				"username" => $row['username'],
				"ownerId" => $row['ownerId'],
				"avatar_url" => $row['avatar_url'],
				"isVoted" => $row['voted'],
				"typeVote" => $row['voteType'],
				"numOfVotes" => $row['numOfVotes']
			]);
		}
		mysqli_close($conn);
		return $result;
	}

	public function isExist(int $id) : bool {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT comment_id FROM comments WHERE comment_id = $id AND is_hidden = 0 AND is_deleted = 0 LIMIT 1";
		$response = mysqli_query($conn, $sql);
		while($row = mysqli_fetch_assoc($response)) {
			mysqli_close($conn);
			return true;
		}
		mysqli_close($conn);
		return false;
	}

	public function vote(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();

		$sql = "SELECT id FROM users WHERE username='".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$user = mysqli_fetch_row($result);
		$userId = $user[0];
		if ($params[1] === "voteUp") {
			$sql = "SELECT comment_id FROM comment_votes WHERE comment_id = $params[0] AND user_id = $userId LIMIT 1";
			$response = mysqli_query($conn, $sql);
			
			if(mysqli_num_rows($response) === 0){
				$sql = "INSERT INTO comment_votes VALUES($params[0], $userId, 1)";
				mysqli_query($conn, $sql);
			} else{
				$sql = "UPDATE comment_votes SET vote = 1 WHERE comment_id = $params[0] AND user_id = $userId";
				mysqli_query($conn, $sql);
			}
		} else {
			$sql = "SELECT comment_id FROM comment_votes WHERE comment_id = $params[0] AND user_id = $userId LIMIT 1";
			$response = mysqli_query($conn, $sql);
			
			if(mysqli_num_rows($response) === 0){
				$sql = "INSERT INTO comment_votes VALUES($params[0], $userId, 0)";
				mysqli_query($conn, $sql);
			} else{
				$sql = "UPDATE comment_votes SET vote = 0 WHERE comment_id = $params[0] AND user_id = $userId";
				mysqli_query($conn, $sql);
			}
		}
		$sql = "SELECT 
		(SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 1 AND comments.comment_id = comment_votes.comment_id) - (SELECT COUNT(*) FROM comment_votes WHERE comment_votes.vote = 0 AND comments.comment_id = comment_votes.comment_id) as numOfVotes
		FROM comments LEFT JOIN comment_votes ON comment_votes.comment_id = comments.comment_id 
		WHERE comments.is_hidden = 0 AND comments.is_deleted = 0 AND comments.comment_id = $params[0]";
		$result = mysqli_query($conn, $sql);
		$comment = mysqli_fetch_row($result);
		$numOfVotes = $comment[0];

		mysqli_close($conn);
		return array("response" => 200, "numOfVotes" => $numOfVotes);
	}

	public function loadAllComments() : array {
		$conn = (new DatabaseConnector())->getConnection();

		if (!isset($_SESSION['USERNAME'])) {
			$sql = "SELECT posts.post_id, posts.title, posts.body, posts.post_image, posts.media_url, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(posts.created_at) as createdFromNowInSeconds, users.username, users.id as ownerId, users.avatar_url, threads.thread_url, COUNT(comments.post_id) as totalComments,
			CASE WHEN EXISTS(SELECT post_votes.user_id FROM post_votes WHERE post_votes.user_id = -1 AND posts.post_id = post_votes.post_id) THEN 1 ELSE 0 END as voted,
			IF ((SELECT post_votes.votes FROM post_votes WHERE post_votes.user_id = -1 AND posts.post_id = post_votes.post_id AND post_votes.votes = 1), 1, -1) as voteType,
			(SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 1 AND posts.post_id = post_votes.post_id) - (SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 0 AND posts.post_id = post_votes.post_id) as numOfVotes
			FROM posts JOIN users ON posts.user_id = users.id JOIN threads ON threads.thread_id = posts.thread_id LEFT JOIN comments ON posts.post_id = comments.post_id LEFT JOIN post_votes ON post_votes.post_id = posts.post_id 
			WHERE posts.is_hidden = 0 AND posts.is_deleted = 0 
			GROUP BY posts.post_id ORDER BY numOfVotes DESC";
			$response = mysqli_query($conn, $sql);

			$result = array();
	
			while($row = mysqli_fetch_assoc($response)) {
				array_push($result, [
					"post_id" => $row['post_id'],
					"title" => $row['title'],
					"body" => $row['body'],
					"post_image" => $row['post_image'],
					"media_url" => $row['media_url'],
					"timestamp" => $row['createdFromNowInSeconds'],
					"username" => $row['username'],
					"ownerId" => $row['ownerId'],
					"avatar_url" => $row['avatar_url'],
					"thread_url" => $row['thread_url'],
					"totalComments" => $row['totalComments'],
					"isVoted" => 0,
					"typeVote" => 0,
					"numOfVotes" => $row['numOfVotes']
				]);
			}
			mysqli_close($conn);
			return $result;

		}

		$sql = "SELECT id FROM users WHERE username='".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$user = mysqli_fetch_row($result);
		$userId = $user[0];

		$sql = "SELECT posts.post_id, posts.title, posts.body, posts.post_image, posts.media_url, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(posts.created_at) as createdFromNowInSeconds, users.username, users.id as ownerId, users.avatar_url, threads.thread_url, COUNT(comments.post_id) as totalComments,
		CASE WHEN EXISTS(SELECT post_votes.user_id FROM post_votes WHERE post_votes.user_id = $userId AND posts.post_id = post_votes.post_id) THEN 1 ELSE 0 END as voted,
		IF ((SELECT post_votes.votes FROM post_votes WHERE post_votes.user_id = $userId AND posts.post_id = post_votes.post_id AND post_votes.votes = 1), 1, -1) as voteType,
		(SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 1 AND posts.post_id = post_votes.post_id) - (SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 0 AND posts.post_id = post_votes.post_id) as numOfVotes
		FROM posts JOIN users ON posts.user_id = users.id JOIN threads ON threads.thread_id = posts.thread_id LEFT JOIN comments ON posts.post_id = comments.post_id LEFT JOIN post_votes ON post_votes.post_id = posts.post_id 
		WHERE posts.is_hidden = 0 AND posts.is_deleted = 0 
		GROUP BY posts.post_id ORDER BY numOfVotes DESC";
		$response = mysqli_query($conn, $sql);

		$result = array();

		while($row = mysqli_fetch_assoc($response)) {
			array_push($result, [
				"post_id" => $row['post_id'],
				"title" => $row['title'],
				"body" => $row['body'],
				"post_image" => $row['post_image'],
				"media_url" => $row['media_url'],
				"timestamp" => $row['createdFromNowInSeconds'],
				"username" => $row['username'],
				"ownerId" => $row['ownerId'],
				"avatar_url" => $row['avatar_url'],
				"thread_url" => $row['thread_url'],
				"totalComments" => $row['totalComments'],
				"isVoted" => $row['voted'],
				"typeVote" => $row['voteType'],
				"numOfVotes" => $row['numOfVotes']
			]);
		}
		mysqli_close($conn);
		return $result;
	}
	public function findAll(array $params) : array {
		return array();
	}
}
?>