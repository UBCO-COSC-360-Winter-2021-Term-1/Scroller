<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/PostController.class.php';

header('Content-Type: application/json; charset=utf-8');

$response = array("response" => 400, "data" => array("message" => "Fields are empty."));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (!empty($_POST['postTitle'])) {
			if (!empty($_POST['postBody']) && !empty($_FILES['postImage']) && !empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([1, $_POST['postTitle'], $_POST['postBody'], $_FILES['postImage'], $_POST['postYoutubeLink'], $_POST['threadUrl']]);
			} else if (!empty($_POST['postBody']) && !empty($_FILES['postImage']) && empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([2, $_POST['postTitle'], $_POST['postBody'], $_FILES['postImage'], $_POST['threadUrl']]);
			} else if (!empty($_POST['postBody']) && empty($_FILES['postImage']) && !empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([3, $_POST['postTitle'], $_POST['postBody'], $_POST['postYoutubeLink'], $_POST['threadUrl']]);
			} else if (empty($_POST['postBody']) && !empty($_FILES['postImage']) && !empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([4, $_POST['postTitle'], $_FILES['postImage'], $_POST['postYoutubeLink'], $_POST['threadUrl']]);
			} else if (!empty($_POST['postBody']) && empty($_FILES['postImage']) && empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([5, $_POST['postTitle'], $_POST['postBody'], $_POST['threadUrl']]);
			} else if (empty($_POST['postBody']) && !empty($_FILES['postImage']) && empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([6, $_POST['postTitle'], $_FILES['postImage'], $_POST['threadUrl']]);
			} else if (empty($_POST['postBody']) && empty($_FILES['postImage']) && !empty($_POST['postYoutubeLink'])) {
				$response = (new PostMiddleware())->createPost([7, $_POST['postTitle'], $_POST['postYoutubeLink'], $_POST['threadUrl']]);
			}
    }
} else if ($_SERVER['REQUEST_METHOD'] === "GET") {
	if (!empty($_GET['query']) && isset($_GET['postSearch']) && (bool) $_GET['postSearch']) {
		$response = (new PostMiddleware())->searchPosts([$_GET['query']]);
	} else if (empty($_GET['query'])) {
		$response =(new PostController())->loadAllPosts();
	}
}

class PostMiddleware {
    
  public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

	public function searchPosts(array $params) : array {

		$query = filter_var($params[0], FILTER_SANITIZE_STRING);
		$query = trim($query);
		$query = stripslashes($query);
		$query = htmlspecialchars($query);

		return (new PostController())->getPostByQuery($query);
	}
	public function createPost(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
	
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
			
		$postTitle = $params[1];
		$threadUrl = end($params);

		if (empty($postTitle)) {
			return array("response" => 400, "data" => array("message" => "Post title cannot be empty."));
		}

		if (!preg_match("/^[a-zA-Z0-9\s]+$/", $postTitle)) {
			return array("response" => 400, "data" => array("message" => "The thread title cannot contain special characters."));
		}

		if (strlen($postTitle) > 75) {
			return array("response" => 400, "data" => array("message" => "The post title cannot be longer than 75 characters."));
		}

		if (strlen($postTitle) < 5) {
			return array("response" => 400, "data" => array("message" => "The post title cannot be shorter than 5 characters."));
		}

		$caseNumber = $params[0];
		switch ($caseNumber) {
			case 1:
				$postBody = $params[2];
				$postImage = $params[3];
				$youtubeLink = $params[4];

				if ($postImage['size'] == 0 || $postImage['size'] > (5 * 1024 * 1024)) {
						return array("response" => 400, "data" => array("message" => "The post image must be less than 5MB."));
				}

				$targetDir = $_SERVER["DOCUMENT_ROOT"].'/server/uploads/post_images/';
				$imgFileType = strtolower(pathinfo($postImage['name'], PATHINFO_EXTENSION));

				if ($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "gif")
			return array("response" => 400, "data" => array("message" => "Only .jpg, .png, and .gif format accepted."));
				
				$imgFile = "";
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				for ($i = 0; $i < 16; $i++)
						$imgFile .= $characters[mt_rand(0, 61)];
				
				$targetFile = $targetDir . 
												basename($imgFile.'.'.
												strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)));
				
				move_uploaded_file($postImage["tmp_name"], $targetFile);


				if (strlen($youtubeLink) > 0 && !preg_match("/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $youtubeLink)) {
						return array("response" => 400, "data" => array("message" => "The YouTube link is not valid."));
				}

				return (new PostController())->post([
						$caseNumber,
						$postTitle,
						$postBody,
						$imgFile . '.' . strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)),
						$youtubeLink,
						$threadUrl
				]);
		
			case 2:
				$postBody = $params[2];
				$postImage = $params[3];
				
				if ($postImage['size'] == 0 || $postImage['size'] > (5 * 1024 * 1024)) {
						return array("response" => 400, "data" => array("message" => "The post image must be less than 5MB."));
				}

				$targetDir = $_SERVER["DOCUMENT_ROOT"].'/server/uploads/post_images/';
				$imgFileType = strtolower(pathinfo($postImage['name'], PATHINFO_EXTENSION));

				if ($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "gif")
			return array("response" => 400, "data" => array("message" => "Only .jpg, .png, and .gif format accepted."));
				
				$imgFile = "";
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				for ($i = 0; $i < 16; $i++)
						$imgFile .= $characters[mt_rand(0, 61)];
				
				$targetFile = $targetDir . 
												basename($imgFile.'.'.
												strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)));
				
				move_uploaded_file($postImage["tmp_name"], $targetFile);

				return (new PostController())->post([
						$caseNumber,
						$postTitle,
						$postBody,
						$imgFile . '.' . strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)),
						$threadUrl
				]);
			
			case 3:
				$postBody = $params[2];
				$youtubeLink = $params[3];

				if (strlen($youtubeLink) > 0 && !preg_match("/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $youtubeLink)) {
						return array("response" => 400, "data" => array("message" => "The YouTube link is not valid."));
				}

				return (new PostController())->post([
						$caseNumber,
						$postTitle,
						$postBody,
						$youtubeLink,
						$threadUrl
				]);
		
			case 4:
				$postImage = $params[2];
				$youtubeLink = $params[3];

				if ($postImage['size'] == 0 || $postImage['size'] > (5 * 1024 * 1024)) {
						return array("response" => 400, "data" => array("message" => "The post image must be less than 5MB."));
				}

				$targetDir = $_SERVER["DOCUMENT_ROOT"].'/server/uploads/post_images/';
				$imgFileType = strtolower(pathinfo($postImage['name'], PATHINFO_EXTENSION));

				if ($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "gif") {
					return array("response" => 400, "data" => array("message" => "Only .jpg, .png, and .gif format accepted."));
				}
				$imgFile = "";
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				for ($i = 0; $i < 16; $i++)
					$imgFile .= $characters[mt_rand(0, 61)];
				
				$targetFile = $targetDir . 
												basename($imgFile.'.'.
												strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)));
				
				move_uploaded_file($postImage["tmp_name"], $targetFile);
				
				if (strlen($youtubeLink) > 0 && !preg_match("/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $youtubeLink)) {
					return array("response" => 400, "data" => array("message" => "The YouTube link is not valid."));
				}

				return (new PostController())->post([
					$caseNumber,
					$postTitle,
					$imgFile . '.' . strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)),
					$youtubeLink,
					$threadUrl
				]);
		
			case 5:
				return (new PostController())->post([
					$caseNumber,
					$postTitle,
					$params[2],
					$threadUrl
				]);
					
			case 6:
				$postImage = $params[2];
				if ($postImage['size'] == 0 || $postImage['size'] > (5 * 1024 * 1024)) {
						return array("response" => 400, "data" => array("message" => "The post image must be less than 5MB."));
				}

				$targetDir = $_SERVER["DOCUMENT_ROOT"].'/server/uploads/post_images/';
				$imgFileType = strtolower(pathinfo($postImage['name'], PATHINFO_EXTENSION));

				if ($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "gif")
			return array("response" => 400, "data" => array("message" => "Only .jpg, .png, and .gif format accepted."));
				
				$imgFile = "";
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				for ($i = 0; $i < 16; $i++)
						$imgFile .= $characters[mt_rand(0, 61)];
				
				$targetFile = $targetDir . basename($imgFile.'.'. strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)));
				
				move_uploaded_file($postImage["tmp_name"], $targetFile);

				return (new PostController())->post([
						$caseNumber,
						$postTitle,
						$imgFile . '.' . strtolower(pathinfo($postImage["name"], PATHINFO_EXTENSION)),
						$threadUrl
			]);

			case 7:
				$youtubeLink = $params[2];
				if (strlen($youtubeLink) > 0 && !preg_match("/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $youtubeLink)) {
					return array("response" => 400, "data" => array("message" => "The YouTube link is not valid."));
				}

				return (new PostController())->post([
					$caseNumber,
					$postTitle,
					$youtubeLink,
					$threadUrl
			]);
		}
	}
}

$response = json_encode($response, true);
echo $response;
?>