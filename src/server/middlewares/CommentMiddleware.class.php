<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/CommentController.class.php';

header('Content-Type: application/json; charset=utf-8');

$response = array("response" => 400, "data" => array("message" => "Fields are empty."));
if ($_SERVER['REQUEST_METHOD'] === "GET") {
	if (!empty($_GET['query']) && isset($_GET['commentSearch']) && (bool) $_GET['commentSearch']) {
		$response = (new CommentMiddleware())->searchComments([$_GET['query']]);
	} else if (empty($_GET['query'])) {
		$response =(new CommentMiddleware())->loadAllComments();
	}
}

class CommentMiddleware {
    
  public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

	public function searchComments(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
	
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		$query = filter_var($params[0], FILTER_SANITIZE_STRING);
		$query = trim($query);
		$query = stripslashes($query);
		$query = htmlspecialchars($query);

		return (new CommentController())->getCommentByQuery($query);
	}
}

$response = json_encode($response, true);
echo $response;
?>