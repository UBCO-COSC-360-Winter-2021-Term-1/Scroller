<?php 
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");
?>
<main class="mt-5 w-75 mx-auto">
	<div class="row">
		<div class="col-md-2 menu">
			<h6 class="text-uppercase">Menu</h6>
			<nav class="mt-3">
				<ul>
					<li><a href="/" class="active rounded"><i class="fas fa-home"></i><span class="ms-2">Home</span></a></li>
					<?php if (isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) { ?>
						<li><a href="/admin" class="rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
					<?php } ?>
					<li><a href="/search" class="rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
					<li><a href="/search" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
					<li><a href="/serach" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
				</ul>
			</nav>
		</div>
		<div class="col-md-6 notifications-container overflow-auto mx-auto mb-4">

			<?php 
			
				require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/NotificationController.class.php';
				
				$notifications = (new NotificationController())->get([]);
				if (count($notifications) === 0)
					echo '<div class="system-message error-content text-center bg-none p-3 account-no-content"><img src="http://'.$_SERVER['HTTP_HOST'].'/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto"><p class="pt-5">It\'s a little bit lonely here. We couldn\'t find anything...</p></div>';
				
				foreach($notifications as $key=>$notification_day) {
					echo '<div class="notification-container mb-4"><h3>'.$key.'</h3>';
					foreach($notification_day as $notification) {
					echo '<div class="notifications"><div class="notification-message d-inline-flex px-3 py-3 w-100 mt-3">';

					if ($notification['action_type'] == 1)
						echo '<i class="far fa-comment-alt text-center text-success my-auto"></i>';
					else if ($notification['action_type'] == 2)
						echo '<i class="far fa-comments text-center text-success my-auto"></i>';
					else if ($notification['action_type'] == 3)
						echo '<i class="fas fa-arrow-down text-center voted-down my-auto"></i>';
					else if ($notification['action_type'] == 4)
						echo '<i class="fas fa-arrow-up text-center voted-up my-auto"></i>';
					else if ($notification['action_type'] == 5)
						echo '<i class="far fa-trash-alt text-center my-auto text-danger"></i>';
					else 
						echo '<i class="fas fa-ban text-center my-auto text-danger"></i>';

					echo '<p class="ms-3 my-auto"><a href="/account/'.$notification['replied_user_id'].'">'.$notification['replied_username'].'</a> '.$notification['description'].' <a href="/t/'.$notification['thread_url'].'">/t/'.$notification['thread_url'].'</a>.</p></div></div>';
					}
					echo '</div>';
				}
			?>
		</div>

		<div class="col-md-3">
			<div class="post-create-block text-center rounded">
				<a href="/t/create"><i class="fas fa-plus"></i><span class="ms-3">Start a New Thread</span></a>
			</div>

			<div class="top-threads-container mt-4 mb-4 rounded px-3 py-3">
				<h5>Top Threads</h5>
				<div class="top-thread-container">
					<div class="top-thread-container-info d-flex align-middle py-2">
						<div class="top-thread-info-name me-auto">
							<i class="fas fa-trophy first-place"></i>
							<span class="ms-1"><a href="/t/cute-kittens/">t/cute-kittens/</a></span>
						</div>
						<div class="top-thread-info-upvote">
							<span class="me-2">15,5k</span><i class="fas fa-arrow-up"></i>
						</div>
					</div>
					<div class="top-thread-container-info d-flex align-middle py-2">
						<div class="top-thread-info-name me-auto">
							<i class="fas fa-trophy second-place"></i>
							<span class="ms-1"><a href="/t/cute-kittens/">t/cute-kittens/</a></span>
						</div>
						<div class="top-thread-info-upvote">
							<span class="me-2">15,5k</span><i class="fas fa-arrow-up"></i>
						</div>
					</div>
					<div class="top-thread-container-info d-flex align-middle py-2">
						<div class="top-thread-info-name me-auto">
							<i class="fas fa-trophy third-place"></i>
							<span class="ms-1"><a href="/t/cute-kittens/">t/cute-kittens/</a></span>
						</div>
						<div class="top-thread-info-upvote">
							<span class="me-2">15,5k</span><i class="fas fa-arrow-up"></i>
						</div>
					</div>
					<div class="top-thread-container-info d-flex align-middle py-2">
						<div class="top-thread-info-name me-auto">
							<span class="ms-1"><a href="/t/cute-kittens/">t/ubco-studies-bad/</a></span>
						</div>
						<div class="top-thread-info-upvote">
							<span class="me-2">15,5k</span><i class="fas fa-arrow-up"></i>
						</div>
					</div>
					<div class="top-thread-container-info d-flex align-middle py-2">
						<div class="top-thread-info-name me-auto">
							<span class="ms-1"><a href="/t/cute-kittens/">t/cute-kittens/</a></span>
						</div>
						<div class="top-thread-info-upvote">
							<span class="me-2">15,5k</span><i class="fas fa-arrow-up"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="useful-links mt-4 mb-5 p-4 py-3 rounded d-flex flex-column">
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