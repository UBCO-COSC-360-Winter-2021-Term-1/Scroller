<?php 
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");
?>
<main class="mt-5 w-75 mx-auto">
	<div class="row">
		<div class="col-md-2 menu">
			<div class="account-profile rounded p-3 mb-2 d-flex flex-column text-center align-items-center">
				<img class="d-block account-profile-picture" src="<?php echo $_SESSION['USER_IMAGE']; ?>" alt="<?php echo $_SESSION['USERNAME']; ?>-profile-picture">
				<h4 class="mt-3"><?php echo $_SESSION['USERNAME']; ?></h4>
				<a href="/account/edit">Settings</a>
			</div>
			<h6 class="text-uppercase mt-4">Menu</h6>
			<nav class="mt-3">
				<ul>
					<li><a href="/" class="active rounded"><i class="fas fa-home"></i><span class="ms-2">Home</span></a></li>
					<?php if (isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) { ?>
						<li><a href="/admin" class="rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
					<?php } ?>
					<li><a href="/search?q=all" class="rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
					<li><a href="/search?q=d3li0n" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
					<li><a href="/serach?q=d3li0n&comments=d3li0n" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
				</ul>
			</nav>
		</div>

			
		<div class="col-md-7 account-page overflow-auto mx-auto mb-4">
			<h3 class="mb-4">Profile Activity</h3>

			<div class="profile-activity overflow-auto">
					<?php 
						require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/UserController.class.php';

						if (count($url) == 2 && $router->getTitle() == "Account" && is_numeric($url[1])) {
							$content = (new UserController())->findPostsAndComments([$url[1], 1]);
						} else if (count($url) == 1 && $router->getTitle() == "Account") {
							$content = (new UserController())->findPostsAndComments([$_SESSION['USERNAME'], 0]);
						}
						$result = "";

						if (count($content) != 0) {
							foreach ($content as $value) {
								$result .= '<div class="profile-activity-content rounded bg-white d-inline-flex align-items-center p-3 w-100 mb-3"><div class="activity-icon d-block me-3">';
								$result .= ($value['type'] === "comments") ? '<i class="far fa-comment-alt"></i>' : '<i class="fas fa-question"></i>';
								
								$result .= '</div><div class="desc"><span>';
								$result .= ($value['type'] === "comments") ? 'New Comment:' : 'New Post:';

								$result .= '</span><p>';
								
								$result .= (strlen($value['content']) == 0) ? 'Image Attachment' : substr($value['content'], 0, 80).'...';

								$result .= '</p><div class="desc-link mt-0">';
								$result .= ($value['type'] === "comments") ? '<span>Commented in </span>' : '<span>Posted in </span>';		
								$result .= '<a href="/t/'.$value['url'].'s">/t/'.$value['url'].'/</a></div></div></div>';
							}
							echo $result;
						} else {
					?>
					<div class="system-message error-content text-center bg-none p-3 account-no-content">
						<img src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/img/error-empty-content.svg" alt="no content available" class=" d-block no-content mx-auto">
						<p class="pt-5">It's a little bit lonely here. We couldn't find anything...</p>
					</div>
					<?php } ?>
			</div>
		</div>

		<div class="col-md-3">
			<div class="useful-links mb-5 p-4 py-3 rounded d-flex flex-column">
				<div class="row">
					<div class="col-md-6">
						<h5 class="mt-2">General</h5>
						<nav>
							<ul class="list-group">
								<li><a href="/help">Help</a></li>
								<li><a href="/threads">Threads</a></li>
								<li><a href="/blog">Blog</a></li>
								<li><a href="/ads">Advertise</a></li>
							</ul>
						</nav>
					</div>
					<div class="col-md-6">
						<h5 class="mt-2">Company</h5>
						<nav>
							<ul class="list-group">
								<li><a href="/about">About</a></li>
								<li><a href="/careers">Careers</a></li>
								<li><a href="/press">Press</a></li>
								<li><a href="/terms">Terms</a></li>
								<li><a href="/privacy">Privacy</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>