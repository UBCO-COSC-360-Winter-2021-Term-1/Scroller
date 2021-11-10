<?php 
session_start();
include $_SERVER["DOCUMENT_ROOT"].'/server/helpers/Controller.class.php';
include $_SERVER["DOCUMENT_ROOT"].'/server/services/DatabaseConnector.class.php';


/*
* 1. Write all the controller methods.
* 2. Create a thread in the database.  
* 
*/ 

//  Use these to connect to the database.
// $result = array("response" => 400, "data" => array("message" => "User doesn't exist."));
// 		$conn = (new DatabaseConnector())->getConnection();

// 		$sql = "SELECT username, email, password, is_email_confirmed, salt, is_account_disabled, avatar_url, is_admin FROM users WHERE email='$params[0]'";
// 		$response = mysqli_query($conn, $sql);
// once validated creating data in the database.
// create a folder in server to store the image path. The image path (The image path is the name of the file) is what we are storing in the database.


class ThreadController extends Controller {
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

	public function findAll(array $params) : array {

		return array();
	}
}
?>