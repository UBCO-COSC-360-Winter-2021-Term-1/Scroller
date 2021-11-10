<?php
include $_SERVER["DOCUMENT_ROOT"].'/server/controllers/ThreadController.class.php';

header('Content-Type: application/json; charset=utf-8');

$response = array("response" => 400, "data" => array("message" => "Fields are empty."));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	var_dump($_POST);
	// $data = json_decode(file_get_contents('php://input'), true);
	// echo "testing this: ";
	// echo $data;

	// if (!empty($_POST['title']) && !empty($_POST['url']) && !empty($_POST['threadBackground']) && !empty($_POST['threadProfile'])) {
	// 	$response = (new ThreadMiddleware())->register([$_POST['title'], $_POST['url'], $_POST['threadBackground'], $_POST['threadProfile']]);
	// 	var_dump($_POST);
	// }
}

class ThreadMiddleware {
    
    public function isLogged() : bool {
		if (isset($_SESSION['IS_AUTHORIZED'])) return true;
		return false;
	}

    // Validate title
    // Validate images
    // If everything is validate return threadcontroller
    // 5mb
    public function createThread(array $params) : array {
		// print thread title echo
		var_dump($_POST);
        return (new ThreadController())->post($params);
    } 


}

$response = json_encode($response, true);
echo $response;
?>