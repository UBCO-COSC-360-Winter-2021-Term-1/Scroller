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

	public function isExist(int $id) : bool {
		$conn = (new DatabaseConnector())->getConnection();
		$sql = "SELECT post_id FROM posts WHERE post_id = $id AND is_hidden = 0 AND is_deleted = 0 LIMIT 1";
		$response = mysqli_query($conn, $sql);
		while($row = mysqli_fetch_assoc($response)) {
			mysqli_close($conn);
			return true;
		}
		mysqli_close($conn);
		return false;
	}

	public function findById(int $id) : array {
		return array();
	}

	public function vote(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();

		$sql = "SELECT id FROM users WHERE username='".$_SESSION["USERNAME"]."' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		$user = mysqli_fetch_row($result);
		$userId = $user[0];
		if ($params[1] === "voteUp") {
			$sql = "SELECT post_id FROM post_votes WHERE post_id = $params[0] AND user_id = $userId LIMIT 1";
			$response = mysqli_query($conn, $sql);
			
			if(mysqli_num_rows($response) === 0){
				$sql = "INSERT INTO post_votes VALUES($params[0], $userId, 1)";
				mysqli_query($conn, $sql);

				$sql = "SELECT user_id, thread_id FROM posts WHERE post_id = $params[0] LIMIT 1";
				$result = mysqli_query($conn, $sql);
				$post = mysqli_fetch_row($result);
				$postOwner = $post[0];
				$postThreadId = $post[1];
				$sql = "INSERT INTO notifications(user_id, replied_user_id, action_type, thread_id) VALUES($postOwner, $userId, 4, $postThreadId)";
				mysqli_query($conn, $sql);
			} else{
				$sql = "UPDATE post_votes SET votes = 1 WHERE post_id = $params[0] AND user_id = $userId";
				mysqli_query($conn, $sql);
			}
		} else {
			$sql = "SELECT post_id FROM post_votes WHERE post_id = $params[0] AND user_id = $userId LIMIT 1";
			$response = mysqli_query($conn, $sql);
			
			if(mysqli_num_rows($response) === 0){
				$sql = "INSERT INTO post_votes VALUES($params[0], $userId, 0)";
				mysqli_query($conn, $sql);

				$sql = "SELECT user_id, thread_id FROM posts WHERE post_id = $params[0] LIMIT 1";
				$result = mysqli_query($conn, $sql);
				$post = mysqli_fetch_row($result);
				$postOwner = $post[0];
				$postThreadId = $post[1];
				$sql = "INSERT INTO notifications(user_id, replied_user_id, action_type, thread_id) VALUES($postOwner, $userId, 3, $postThreadId)";
				mysqli_query($conn, $sql);
			} else{
				$sql = "UPDATE post_votes SET votes = 0 WHERE post_id = $params[0] AND user_id = $userId";
				mysqli_query($conn, $sql);
			}
		}
		$sql = "SELECT 
		(SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 1 AND posts.post_id = post_votes.post_id) - (SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 0 AND posts.post_id = post_votes.post_id) as numOfVotes
		FROM posts LEFT JOIN post_votes ON post_votes.post_id = posts.post_id 
		WHERE posts.is_hidden = 0 AND posts.is_deleted = 0 AND posts.post_id = $params[0]";
		$result = mysqli_query($conn, $sql);
		$post = mysqli_fetch_row($result);
		$numOfVotes = $post[0];

		mysqli_close($conn);
		return array("response" => 200, "numOfVotes" => $numOfVotes);
	}

	public function getPostByQuery(string $query) : array {
		$conn = (new DatabaseConnector())->getConnection();
		if(!isset($_SESSION['USERNAME'])) {
			$sql = "SELECT posts.post_id, posts.title, posts.body, posts.post_image, posts.media_url, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(posts.created_at) as createdFromNowInSeconds, users.username, users.id as ownerId, users.avatar_url, threads.thread_url, COUNT(comments.post_id) as totalComments,
			CASE WHEN EXISTS(SELECT post_votes.user_id FROM post_votes WHERE post_votes.user_id = -1 AND posts.post_id = post_votes.post_id) THEN 1 ELSE 0 END as voted,
			IF ((SELECT post_votes.votes FROM post_votes WHERE post_votes.user_id = -1 AND posts.post_id = post_votes.post_id AND post_votes.votes = 1), 1, -1) as voteType,
			(SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 1 AND posts.post_id = post_votes.post_id) - (SELECT COUNT(*) FROM post_votes WHERE post_votes.votes = 0 AND posts.post_id = post_votes.post_id) as numOfVotes
			FROM posts JOIN users ON posts.user_id = users.id JOIN threads ON threads.thread_id = posts.thread_id LEFT JOIN comments ON posts.post_id = comments.post_id LEFT JOIN post_votes ON post_votes.post_id = posts.post_id 
			WHERE posts.is_hidden = 0 AND posts.is_deleted = 0 AND (posts.title LIKE '%$query%' OR posts.body LIKE '%$query%')
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
		WHERE posts.is_hidden = 0 AND posts.is_deleted = 0 AND (posts.title LIKE '%$query%' OR posts.body LIKE '%$query%')
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
	
	public function loadAllPosts() : array {
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