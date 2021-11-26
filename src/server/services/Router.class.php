<?php 

class Router {
	protected $titles = array(
		"" => array("Main"),
		"login" => array("Login"),
		"register" => array("Register", "Register Confirm"),
		"logout" => array("Logout"),
		"restore" => array("Restore", "Restore Confirm"),
		"t" => array("Thread", "Create Thread", "Create Post"),
		"account" => array("Account", "Account Edit"),
		"notifications" => array("Notifications"),
		"admin" => array("Admin Dashboard", "Admin"),
		"search" => array("Search")
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
				if (is_numeric($this->url[1]) && $this->url[0] == "account")
					return $this->titles[$this->url[0]][0];
				else if (isset( $this->titles[$this->url[0]][1]))
					return $this->titles[$this->url[0]][1];
				else "Page Not Found";
		} else if (count($this->url) == 3) {
			if (isset($this->titles[$this->url[0]][2]))
				return $this->titles[$this->url[0]][2];
			else "Page Not Found";
		}
		
		return 'Page Not Found';
	}

	public function show() : string {

		$auth = isset($_SESSION['IS_AUTHORIZED']);
		$admin = isset($_SESSION['IS_ADMIN']);

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
				case "account": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					return PUBLIC_DIR.'/account.php';
				};
				case "notifications": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					return PUBLIC_DIR.'/notifications.php';
				};
				case "search": {
					return PUBLIC_DIR.'/search.php';
				}
				case "": {
					return PUBLIC_DIR.'/layout/main.php';
				}
				case "logout": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					session_destroy();
					return PUBLIC_DIR.'/layout/main.php';
				}
				case "admin": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					else if ($admin && !$_SESSION['IS_ADMIN'])
						return PUBLIC_DIR.'/login.php';
					else 
						return PUBLIC_DIR.'/admin.php';
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
				};
				case "t": {
					if ($this->url[1] == "create") {
						if (!$auth)
							return PUBLIC_DIR.'/login.php';
						return PUBLIC_DIR.'/thread-create.php';
					} else if ($this->url[1] != "create") {
						return PUBLIC_DIR.'/thread.php';
					}
					return PUBLIC_DIR.'/layout/main.php';
				}
				case "admin": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					else if ($admin && !$_SESSION['IS_ADMIN'])
						return PUBLIC_DIR.'/login.php';
					else if ($this->url[1] == "users")
						return PUBLIC_DIR.'/admin-users.php';
					else if ($this->url[1] == "threads")
						return PUBLIC_DIR.'/admin-threads.php';
					return PUBLIC_DIR.'/layout/main.php';
				};
				case "account": {
					if (!$auth)
						return PUBLIC_DIR.'/login.php';
					if (!is_numeric($this->url[1]))
						return PUBLIC_DIR.'/account-settings.php';
					return PUBLIC_DIR.'/account.php';
				}
				default: return PUBLIC_DIR.'/error.php';
			}
		} else if (count($this->url) == 3) {
			
			switch ($this->url[0]) {
				case "t": {
					if ($this->url[2] == "create-post") {
						if (!$auth)
							return PUBLIC_DIR.'/login.php';
						return PUBLIC_DIR.'/post-create.php';
					}
				}
				default: return PUBLIC_DIR.'/error.php';
			}
		}
		return PUBLIC_DIR.'/error.php';
	}
}

?>