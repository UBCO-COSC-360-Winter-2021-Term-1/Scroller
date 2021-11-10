<?php 
include $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';

class TokenController extends Controller {
		
	/**
	 * get
	 *
	 * @param  array $params
	 * @return array
	 */
	public function get(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();
		
		if ($params[1] === 1) {
			$sql = "SELECT users.is_email_confirmed, expires_at FROM tokens JOIN users ON tokens.user_id = users.id WHERE is_email_confirmation = 1 AND token='$params[0]'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_row($result);	 
			
			if (is_null($row)) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Invalid token."));
			}
			
			if ((int) $row[0] === 1) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Email has been confirmed previously."));
			}
			
			if (strtotime($row[1]) < time()) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Token expired."));
			}
		} else if ($params[1] === 0) {
			$sql = "SELECT expires_at FROM tokens WHERE is_email_confirmation = 0 AND token='$params[0]'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_row($result);	 

			if (is_null($row)) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Invalid token."));
			}

			if (strtotime($row[0]) < time()) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Token expired."));
			}
		}

		mysqli_close($conn);
		return array("response" => 200);
	}
	
	/**
	 * post
	 *
	 * @param  array $params
	 * @return array
	 */
	public function post(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();
		if ($params[3] === 1) {
			$code = rand(1000, 99999);
			$sql = "INSERT INTO tokens(token, key_code, expires_at, user_id) VALUES ('$params[0]', $code, '".date('Y-m-d H:i:s', $params[1])."', $params[2])";
		} else if ($params[3] === 0) {
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		
			$end_token = time() + 86400;
			$token = '';

			for ($i = 0; $i < 60; $i++)
				$token .= $characters[mt_rand(0, 61)];
			
			$sql = "SELECT id FROM users WHERE email='$params[0]' LIMIT 1";
			$result = mysqli_query($conn, $sql);
			
			$row = mysqli_fetch_row($result);
			$sql = "INSERT INTO tokens(token, expires_at, user_id, is_email_confirmation) VALUES ('$token', '".date('Y-m-d H:i:s', $end_token)."', $row[0], 0)";
		}
		mysqli_query($conn, $sql);
	
		return array("response" => 200);
	}

	public function update(array $params) : array {
		$conn = (new DatabaseConnector())->getConnection();
		if ($params[2] === 1) {
			$sql = "SELECT key_code, user_id FROM tokens WHERE token='$params[1]'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_row($result);	 

			if ($row[0] !== $params[0]) {
				mysqli_close($conn);
				return array("response" => 400, "data" => array("message" => "Invalid confirmation code."));
			}

			$sql = "UPDATE users SET is_email_confirmed = 1 WHERE id=$row[1]";
			mysqli_query($conn, $sql);
		} else if ($params[2] === 0) {

			$sql = "SELECT user_id FROM tokens WHERE token='$params[1]'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_row($result);	 

			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			$hash = '';
			
			for($i = 0; $i < 10; $i++)
				$hash .= $characters[mt_rand(0, 61)];
					
			$password = hash('sha256', $params[0] . $hash);

			$sql = "UPDATE users SET password = '$password', salt = '$hash' WHERE id = $row[0]";
			mysqli_query($conn, $sql);

			$sql = "UPDATE tokens SET expires_at = '".date('Y-m-d H:i:s', time())."' WHERE token = '$params[1]'";
			mysqli_query($conn, $sql);
		}


		mysqli_close($conn);
		return array("response" => 200);
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