<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';

header('Content-Type: application/json; charset=utf-8');

$response = array("response" => 400, "data" => array("message" => "Fields \"Email\" and \"Password\" are empty."));

if ($_SERVER['REQUEST_METHOD'] === "GET") {
	if (!empty($_GET['email']) && !empty($_GET['password'])) {
		$response = (new UserMiddleware())->login([$_GET['email'], $_GET['password']]);
	}
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
	if (!empty($_POST['email']) && !empty($_POST['state'])) {
		$response = (new UserMiddleware())->restore([$_POST['email'], 0]);
	}
	else if (!empty($_POST['username']) || !empty($_POST['email']) || (!empty($_POST['password']) && !empty($_POST['repeatpassword']))) {
		if (!empty($_POST['username']) && empty($_POST['email']) && empty($_POST['password']) && empty($_POST['repeatpassword'])) {
			$response = (new UserMiddleware())->register(["username" => $_POST['username']]);
		} else if (empty($_POST['username']) && !empty($_POST['email']) && empty($_POST['password']) && empty($_POST['repeatpassword'])) {
			$response = (new UserMiddleware())->register(["email" => $_POST['email']]);
		} else if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['repeatpassword'])) {
			$response = (new UserMiddleware())->register([$_POST['username'], $_POST['email'], $_POST['password'], $_POST['repeatpassword']]);
		} else {
			$response = array("response" => 400, "data" => array("message" => "Invalid information was passed."));
		}
	} else if (!empty($_FILES["img_profile"])) {
		$response = (new UserMiddleware())->validateProfileImage($_FILES["img_profile"]);
	} else if (empty($_FILES["img_profile"])) {
		$response = array("response" => 400, "data" => array("message" => "Invalid Server Request."));
	}
}

class UserMiddleware {

	public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

	public function validateProfileImage(array $params) : array {
		if (!$this->isLogged()) return array("response" => 403);
	
		if ($params['size'] == 0 || $params['size'] > (5 * 1024 * 1024)) return array("response" => 400, "data" => array("message" => "Image cannot be larger than 5 MB."));

		$target_dir  =  $_SERVER["DOCUMENT_ROOT"].'/server/uploads/user_images/';
		
		$imageFileType = strtolower(pathinfo($params["name"], PATHINFO_EXTENSION));

		$imgFile = "";
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		for($i = 0; $i < 16; $i++)
			$imgFile .= $characters[mt_rand(0, 61)];

		$target_file = $target_dir . basename($imgFile.'.'.strtolower(pathinfo($params["name"], PATHINFO_EXTENSION)));

		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif") return array("response" => 400, "data" => array("message" => "Only .jpg, .png, and .gif format accepted."));
		
		if (move_uploaded_file($params["tmp_name"], $target_file)) {
			return (new UserController())->updateProfileImage($imgFile.'.'.strtolower(pathinfo($params["name"], PATHINFO_EXTENSION)));
		} 

		return array("response" => 400, "data" => array("message" => "Invalid information was passed or server error has occured."));
	}

	public function restore(array $params) : array {
		if ($this->isLogged()) return array("response" => 403);

		if (!filter_var($params[0], FILTER_VALIDATE_EMAIL)) return array( "response" => 400, "data" => array("message" => "Field \"Email\" is not valid."));

		if (!(new UserController())->findByEmail($params[0])) return array( "response" => 400, "data" => array("message" => "Email doesn't exist"));
		
		if (!(new UserController())->isEmailConfirmed($params[0])) return array( "response" => 400, "data" => array("message" => "Email is not confirmed."));
		
		if (!$params[1]) {
			return (new TokenController())->post([$params[0], "", "", 0]);
		}

		return array("response" => 400, "data" => array("message" => "Invalid information was passed."));
	}

	public function register(array $params) : array {

		if ($this->isLogged()) return array("response" => 403);
	
		if (count($params) == 1 && array_key_exists("username", $params)) {
			if (strlen($params["username"]) < 3 || strlen($params["username"]) > 8)
				return array( "response" => 400, "data" => array("message" => "Username should be between 3 to 8 characters."));

			if (!preg_match("/^[a-z0-9]+$/", $params["username"]))
				return array( "response" => 400, "data" => array("message" => "Only small letters and numbers are allowed."));
			
			if ((new UserController())->findByUsername($params["username"]))
				return array( "response" => 400, "data" => array("message" => "Username already exists"));
			return array("response" => 200);

		} else if (count($params) == 1 && array_key_exists("email", $params)) {
			if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) return array( "response" => 400, "data" => array("message" => "Field \"Email\" is not valid."));

			if ((new UserController())->findByEmail($params["email"]))
				return array( "response" => 400, "data" => array("message" => "Email already exists"));
			return array("response" => 200);

		} else if (count($params) == 4) {
			// Extra validation of username
			if (strlen($params[0]) < 3 || strlen($params[0]) > 8) return array( "response" => 400, "data" => array("message" => "Username should be between 3 to 8 characters."));

			if (!preg_match("/^[a-z0-9]+$/", $params[0])) return array( "response" => 400, "data" => array("message" => "Only small letters and numbers are allowed."));
			
			if ((new UserController())->findByUsername($params[0])) return array( "response" => 400, "data" => array("message" => "Username already exists"));
			
			// Extra validation of email
			if (!filter_var($params[1], FILTER_VALIDATE_EMAIL)) return array( "response" => 400, "data" => array("message" => "Field \"Email\" is not valid."));

			if ((new UserController())->findByEmail($params[1])) return array( "response" => 400, "data" => array("message" => "Email already exists"));
		
			// Validation of passwords

			if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/", $params[2]))
				return array( "response" => 400, "data" => array("message" => "Password must be minimum 8 characters, one uppercase letter, and one special symbol."));
			
			if ($params[2] != $params[3])
				return array( "response" => 400, "data" => array("message" => "Passwords don't match."));
			
			return (new UserController())->register($params);
		}
	}

	public function login(array $params) : array {
		if (!filter_var($params[0], FILTER_VALIDATE_EMAIL)) return array( "response" => 400, "data" => array("message" => "Field \"Email\" is not valid."));

		if (strlen($params[1]) < 6) return array( "response" => 400, "data" => array("message" => "Password should be longer than 5 letters"));

		if ($this->isLogged()) return array("response" => 403, "data" => array("message" => "User is already logged in."));

		return (new UserController())->login($params);
	}
}

$response = json_encode($response, true);
echo $response;
?>