<?php 

class Router {
	protected $titles = array(
		"" => array("Main"),
		"login" => array("Login"),
		"register" => array("Register", "Register Confirm"),
		"logout" => array("Logout"),
		"restore" => array("Restore", "Restore Confirm")
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
		
		if (count($this->url) == 1) {
			if (isset($this->titles[$this->url[0]][0]))
				return $this->titles[$this->url[0]][0];
		} else if (count($this->url) == 2) {
			if (isset($this->titles[$this->url[0]][0]))
				return $this->titles[$this->url[0]][1];
		}
		
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
				case "restore": {
					if (!$auth)
						return PUBLIC_DIR.'/restore.php';
					return PUBLIC_DIR.'/layout/main.php';
				};
				case "": {
					return PUBLIC_DIR.'/layout/main.php';
				}
				case "logout": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					session_destroy();
					return PUBLIC_DIR.'/layout/main.php';
				}
				default: return PUBLIC_DIR.'/error.php';
			}
		} else if (count($this->url) == 2) {

			switch ($this->url[0]) {
				case "register": {
					if (!$auth)
						return PUBLIC_DIR.'/register-confirm.php';
					return PUBLIC_DIR.'/layout/main.php';
				};
				case "restore": {
					if (!$auth)
						return PUBLIC_DIR.'/restore-confirm.php';
					return PUBLIC_DIR.'/layout/main.php';
				}
				
			}
		}
		return PUBLIC_DIR.'/error.php';
	}
}

?>