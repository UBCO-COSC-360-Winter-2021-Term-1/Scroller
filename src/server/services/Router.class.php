<?php 

class Router {
	protected $titles = array(
		"" => array("Main"),
		"login" => array("Login"),
		"register" => array("Register")
	);
	protected $url = array();

	public function __construct() {
    $action = @htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
    $action = filter_var($action, FILTER_SANITIZE_URL);
    
    $action = substr($action, 1);
    $url = explode("/", $action);
		$this->url = $url;

	}

	public function getTitle() : string {
		
		if (isset($this->titles[$this->url[0]][0]))
			return $this->titles[$this->url[0]][0];
		
		return 'Page Not Found';
	}

	public function show() : string {

		$auth = isset($_SESSION['IS_AUTHORIZED']);

		if (count($this->url) == 1) {

			switch ($this->url[0]) {
				case "login": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					return PUBLIC_DIR.'/layout/main.php';
				};
				case "register": {
					if (!$auth)
						return PUBLIC_DIR.'/register.php';
					return PUBLIC_DIR.'/layout/main.php';
				};
				case "": {
					return PUBLIC_DIR.'/layout/main.php';
				}
				default: return PUBLIC_DIR.'/error.php';
			}
		}
		return PUBLIC_DIR.'/error.php';
	}
}

?>