<?php
	global $router;

	$action = @htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
	$action = filter_var($action, FILTER_SANITIZE_URL);
	
	$action = substr($action, 1);
	$url = explode("/", $action);

	if ($url[0] === "client") header("Location: /");

	if (($router->getTitle() == "Login" || $router->getTitle() == "Register" || $router->getTitle() == "Register Confirm" || $router->getTitle() == "Restore" || $router->getTitle() == "Restore Confirm") && isset($_SESSION['IS_AUTHORIZED'])) header("Location: /");
	
	if (($router->getTitle() == "Admin Dashboard" || $router->getTitle() == "Admin") && (!isset($_SESSION['IS_ADMIN']) || !$_SESSION['IS_ADMIN']))
		header("Location: /");
		
	if ($router->getTitle() == "Account" && !isset($_SESSION['IS_AUTHORIZED']))
		header("Location: /login");
	
	if (count($url) == 2 && $url[1] != "create" && $url[0] == "t" && $router->getTitle() == "Create Thread") {
		require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/ThreadController.class.php';
		if (!(new ThreadController())->findThreadByUrl($url[1]))
			header("Location: /");
	}

	if ($router->getTitle() == "Create Thread" && !isset($_SESSION['IS_AUTHORIZED']))
		header("Location: /login");
	
	if ($router->getTitle() == "Create Post" && !isset($_SESSION['IS_AUTHORIZED']))
		header("Location: /login");

	if (($router->getTitle() == "Account Edit" || $router->getTitle() == "Notifications") && !isset($_SESSION['IS_AUTHORIZED']))
		header("Location: /login");
	
	if (count($url) == 2 && $router->getTitle() == "Account Edit" && !is_numeric($url[1]) && $url[1] != "edit") {
		header("Location: /account");
	} else if (count($url) == 2 && $router->getTitle() == "Account" && is_numeric($url[1])) {
		require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';
		if (!(new UserController())->findUserById($url[1]))
			header("Location: /account");
	}

	if (empty($_GET['token']) && ($router->getTitle() == "Register Confirm" || $router->getTitle() == "Restore Confirm")) {
		header("Location: /");
	}
	
	if (!empty($_GET['token']) && $router->getTitle() == "Register Confirm") {
		include $_SERVER["DOCUMENT_ROOT"].'/server/middlewares/TokenMiddleware.class.php';

		$token = (new TokenMiddleware())->validateToken([$_GET['token'], 1]);
		if ($token["response"] == 400) header("Location: /");
	} else if (!empty($_GET['token']) && $router->getTitle() == "Restore Confirm") {
		require_once $_SERVER["DOCUMENT_ROOT"].'/server/middlewares/TokenMiddleware.class.php';
		
		$token = (new TokenMiddleware())->validateToken([$_GET['token'], 0]);
		if ($token["response"] == 400) header("Location: /");
	}

	if ($router->getTitle() == "Logout") header("Location: /");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once $_SERVER["DOCUMENT_ROOT"].'/server/modules/header.php'; ?>
	<title>Scroller | 
		<?php 
			if (count($url) == 2 && $url[0] == "t" && $url[1] != "create") {
				require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/ThreadController.class.php';
				echo (new ThreadController())->getTitle($url[1]);
			} else {
				echo $router->getTitle(); 
			}
		?></title>
</head>
<body>
	<?php if ($router->getTitle() != "Login" && $router->getTitle() != "Register" && $router->getTitle() != "Register Confirm" && $router->getTitle() != "Restore" && $router->getTitle() != "Restore Confirm") { ?>
	<header>
		<nav class="d-flex justify-content-center justify-content-md-between w-75 mx-auto py-3">
			<a href="/" class="d-flex align-items-center brand me-3">
				<i class="fas fa-mouse"></i><span class="ms-2">Scroller</span>
			</a>
			<div class="text-end ms-3">
				<ul>
					<?php if (isset($_SESSION['IS_AUTHORIZED'])) { ?> 
					<li class="me-3">
						<a href="/notifications" class="header-icon"><i class="far fa-bell"></i></a>
					</li>
					<li>
						<a href="/account" class="header-icon d-inline-flex">
							<img id="img-header-profile" class="img-fluid" src="<?php echo $_SESSION['USER_IMAGE']; ?>" alt="<?php echo $_SESSION['USERNAME']; ?>-profile-picture" />
							<span class="ms-2"><?php echo $_SESSION['USERNAME']; ?></span>
						</a>
					</li>
					<li class="ms-3">
						<a href="/logout" class="header-icon">
							<i class="fas fa-sign-out-alt"></i>
						</a>
					</li>
					<?php } else { ?>
					
					<li>
						<a href="/login" class="header-icon active-sign-in-header">
							Sign in
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	</header>
	<?php } ?>
	<?php require_once $router->show(); ?>

	<script src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/js/script.js"></script>
</body>
</html>