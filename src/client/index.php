<?php

	global $router;

	$action = @htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
	$action = filter_var($action, FILTER_SANITIZE_URL);
	
	$action = substr($action, 1);
	$url = explode("/", $action);
	if (($router->getTitle() == "Login" || $router->getTitle() == "Register") && isset($_SESSION['IS_AUTHORIZED'])) header("Location: /");
	
	if ($router->getTitle() == "Logout") header("Location: /");
	if ($url[0] === "client") header("Location: /");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once $_SERVER["DOCUMENT_ROOT"].'/server/modules/header.php'; ?>
	<title>Scroller | <?php echo $router->getTitle(); ?></title>
</head>
<body>
	<?php if ($router->getTitle() != "Login" && $router->getTitle() != "Register") { ?>
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