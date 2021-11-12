<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/AdminController.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/ThreadController.class.php';
header('Content-Type: application/json; charset=utf-8');

$response = array("response" => 400, "data" => array("message" => "Fields are empty."));

if ($_SERVER['REQUEST_METHOD'] === "GET") {
	if(isset($_GET['query']) && !empty($_GET['query']) && strlen($_GET['query']) != 0) {
		$response = (new AdminMiddleware())->searchUsersByUsername($_GET['query']);
	} else if (isset($_GET['query']) && empty($_GET['query']) && strlen($_GET['query']) == 0 && empty($_GET['queryThread'])) {
		$response = (new AdminMiddleware())->searchUsersByUsername("");
	} else if (!empty($_GET['queryThread'])) {
		$response = (new AdminMiddleware())->searchThreadByTitle($_GET['queryThread']);
	} else if (isset($_GET['queryThread']) && empty($_GET['queryThread']) && strlen($_GET['queryThread']) == 0) {
		$response = (new AdminMiddleware())->searchThreadByTitle("");
	}
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
	if(!empty($_POST['action']) && !empty($_POST['userId'])) {
		$response = (new AdminMiddleware())->updateUserBlockStatus([$_POST['action'], $_POST['userId']]);
	} else if (!empty($_POST['actionAdmin']) && !empty($_POST['userId'])) {
		$response = (new AdminMiddleware())->updateUserAdminStatus([$_POST['actionAdmin'], $_POST['userId']]);
	} else if (!empty($_POST['actionTypeDelete']) && !empty($_POST['threadId'])) {
		$response = (new AdminMiddleware())->deleteThread([$_POST['actionTypeDelete'], $_POST['threadId']]);
	} else if (!empty($_POST['actionTypeHide']) && !empty($_POST['threadId'])) {
		$response = (new AdminMiddleware())->hideThread([$_POST['actionTypeHide'], $_POST['threadId']]);
	} else if (!empty($_POST['actionTypeRecover']) && !empty($_POST['threadId'])) {
		$response = (new AdminMiddleware())->recoverThread([$_POST['actionTypeRecover'], $_POST['threadId']]);
	}
}

class AdminMiddleware {
	
	public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

	public function isAdmin() : bool {
		if (!isset($_SESSION['IS_ADMIN']) && !$_SESSION['IS_ADMIN']) return true;
		return false;
	}

	public function recoverThread(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		(new ThreadController())->restore([$params[1]]);

		return array("response" => 200);
	}

	public function hideThread(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		(new ThreadController())->hide([$params[1]]);

		return array("response" => 200);
	}

	public function deleteThread(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		(new ThreadController())->delete([$params[1]]);

		return array("response" => 200);
	}

	public function updateUserAdminStatus(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		if (!(new UserController())->findUserById((int)$params[1])) return array( "response" => 400, "data" => array("message" => "User Not Found"));
		
		(new AdminController())->updateUserAdmin((bool) ($params[0] !== "false") ? 1 : 0, (int) $params[1]);

		return array("response" => 200);

	}

	public function updateUserBlockStatus(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		if (!(new UserController())->findUserById((int)$params[1])) return array( "response" => 400, "data" => array("message" => "User Not Found"));
		
	
		(new AdminController())->updateUserAccess((bool) ($params[0] !== "false") ? 1 : 0, (int) $params[1]);

		return array("response" => 200);
	}

	public function searchUsersByUsername(string $query) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		if (strlen($query) === 0) {
			return (new AdminController())->getAllUsers();
		}
		if (!preg_match("/^[a-z0-9]+$/", $query)) return array( "response" => 400, "data" => array("message" => "Only small letters and numbers are allowed."));
			
		return (new AdminController())->searchUsersByUsername($query);
	}

	public function searchThreadByTitle(string $query) : array {
		if (!$this->isLogged()) return array("response" => 403);
		if ($this->isAdmin()) return array("response" => 403);

		if (!(new UserController())->isEmailConfirmedByUserName($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Email is not verified."));
		
		if ((new UserController())->isAccountDisabled($_SESSION['USERNAME'])) return array( "response" => 400, "data" => array("message" => "Unathorized attempt. Account is disabled."));
		
		if (strlen($query) === 0) {
			return (new AdminController())->getAllThreads();
		}

		if (!preg_match("/^[a-zA-Z0-9\s]+$/", $query)) return array( "response" => 400, "data" => array("message" => "Title shouldn't contain numbers or special characters."));
		return (new AdminController())->searchThreadByTitle($query);
	}
}

$response = json_encode($response, true);
echo $response;
?>