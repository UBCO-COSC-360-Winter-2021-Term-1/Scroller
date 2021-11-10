<?php
include $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
include $_SERVER["DOCUMENT_ROOT"].'/server/controllers/TokenController.class.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	header('Content-Type: application/json; charset=utf-8');

	$response = array("response" => 400, "data" => array("message" => "Invalid data."));
	
	if (!empty($_POST['code']) && !empty($_POST['token'])) {
		$response = (new TokenMiddleware())->updateToken([$_POST['code'], $_POST['token'], 1]);
	} else if (!empty($_POST['token']) && !empty($_POST['password']) && !empty($_POST['repeatpassword'])) {
		$response = (new TokenMiddleware())->updateToken([$_POST['password'], $_POST['token'], 0, $_POST['repeatpassword']]);
	}
	$response = json_encode($response, true);
	echo $response;
}

class TokenMiddleware {

	public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

	public function validateToken(array $params) : array {
		if (!empty($params[0])) {
			if ($this->isLogged()) return array("response" => 403);
			
			if (preg_match("/^[a-zA-Z0-9]+$/", $params[0])) {
				if ($params[1] == 1)
					return (new TokenController())->get([$params[0], 1]);
				else if ($params[1] == 0)
					return (new TokenController())->get([$params[0], 0]);
			}
			return array("response" => 400, "data" => array("message" => "Invalid token."));
		} else {
			return array("response" => 400, "data" => array("message" => "Token is empty."));
		}
	}
	
	public function updateToken(array $params) : array {
		if ($this->isLogged()) return array("response" => 403);
		
		if (!preg_match("/^[a-zA-Z0-9]+$/", $params[1])) return array("response" => 400, "data" => array("message" => "Invalid token."));

		if ($params[2] === 1) {
			if (!is_numeric($params[0])) return array("response" => 400, "data" => array("message" => "Invalid confirmation code."));

			if ((int) $params[0] < 1000 || (int) $params[0] > 99999) return array("response" => 400, "data" => array("message" => "Invalid confirmation code."));
			
			if ((new TokenController())->get([$params[1], 1])["response"] !== 200) return array("response" => 400, "data" => array("message" => "Invalid token."));
			
			return (new TokenController())->update([$params[0], $params[1], 1]);

		} else if ($params[2] === 0) {
			if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/", $params[0]))
				return array( "response" => 400, "data" => array("message" => "Password must be minimum 8 characters, one uppercase letter, and one special symbol."));
			
			if ($params[0] != $params[3])
				return array( "response" => 400, "data" => array("message" => "Passwords don't match."));

			if ((new TokenController())->get([$params[1], 0])["response"] !== 200) return array("response" => 400, "data" => array("message" => "Invalid token."));
			return (new TokenController())->update([$params[0], $params[1], 0]);
		}
	}
}
?>