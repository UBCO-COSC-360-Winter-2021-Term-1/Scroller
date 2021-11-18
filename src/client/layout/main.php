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
						<li><a href="/search" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
					</ul>
				</nav>
			</div>
			<div class="col-md-6 topic-threads overflow-auto mx-auto mb-4">
				<?php 
					require_once SERVER_DIR.'/controllers/PostController.class.php';

					$posts = (new PostController())->loadAllPosts();
					if (count($posts) != 0) {
						foreach ($posts as $post) {
							echo '<article class="rounded p-4 mb-5">';
								echo '<div class="row">';
									echo '<div class="col-sm-2">';
										echo '<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">';
											if ($post['isVoted'] == 0) {
												echo '<i class="fas fa-arrow-up my-auto"></i>';
												echo '<span class="d-block mt-2 mb-2"><a href="#">'.$post['numOfVotes'].'</a></span>';
												echo '<i class="fas fa-arrow-down my-auto"></i>';
											} else if ($post['isVoted'] == 1 && $post['typeVote'] == 1) {
												echo '<i class="fas fa-arrow-up voted-up my-auto"></i>';
												echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-up">'.$post['numOfVotes'].'</a></span>';
												echo '<i class="fas fa-arrow-down my-auto"></i>';
											} else if ($post['isVoted'] == 1 && $post['typeVote'] == -1) {
												echo '<i class="fas fa-arrow-up my-auto"></i>';
												echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">'.$post['numOfVotes'].'</a></span>';
												echo '<i class="fas fa-arrow-down voted-down my-auto"></i>';
											}
										echo '</div>';
									echo '</div>';
									echo '<div class="col-sm-10">';
										echo '<span class="thread-name">Posted in <a href="/t/'.$post['thread_url'].'">/t/'.$post['thread_url'].'</a></span>';
										echo '<h4><a href="/t/'.$post['thread_url'].'/'.$post['post_id'].'">'.$post['title'].'</a></h4>';
										echo '<p>';
											if (is_null($post['post_image']) && is_null($post['media_url']) && !is_null($post['body'])) {
												echo $post['body'];
											} else if (!is_null($post['post_image']) && is_null($post['media_url']) && is_null($post['body'])) {
												echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$post['post_image'].'" alt="content-img">';
											} else if (is_null($post['post_image']) && !is_null($post['media_url']) && is_null($post['body'])) {
												echo '<iframe width="100%" height="300" src="'.$post['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
											} else if (!is_null($post['post_image']) && is_null($post['media_url']) && !is_null($post['body'])) {
												echo $post['body'];
												echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$post['post_image'].'" alt="content-img" class="pt-2">';
											} else if (is_null($post['post_image']) && !is_null($post['media_url']) && !is_null($post['body'])) {
												echo $post['body'];
												echo '<iframe class="pt-2" width="100%" height="300" src="'.$post['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
											} else if (!is_null($post['post_image']) && !is_null($post['media_url']) && !is_null($post['body'])) {
												echo $post['body'];
												echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$post['post_image'].'" alt="content-img" class="pt-2">';
												echo '<iframe class="pt-2" width="100%" height="300" src="'.$post['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
											} else {
												echo $post['body'];
											}
										echo '</p>';
										echo '<div class="post-info-container d-flex justify-content-between">';
											echo '<div class="profile-info-sm d-flex align-middle">';
												echo '<img id="img-header-profile" class="img-fluid" src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/user_images/'.$post['avatar_url'].'" alt="'.$post['username'].'-profile-picture" />';
												echo '<span class="ms-2">Posted by <a href="/account/'.$post['ownerId'].'">'.$post['username'].'</a></span>';
											echo '</div>';
											if ($post['timestamp'] / 60 < 60) {
												echo '<span class="d-block time-post">'.ceil($post['timestamp'] / 60).'m ago</span>';
											} else if ($post['timestamp'] / 60 >= 60 && $post['timestamp'] / 60 < 1409) {
												echo '<span class="d-block time-post">'.ceil($post['timestamp'] / 3600).'h ago</span>';
											} else {
												echo '<span class="d-block time-post">'.ceil($post['timestamp'] / 86400).'d ago</span>';
											}
											echo '<div class="post-info-comments">';
												echo '<a href="/t/'.$post['thread_url'].'/'.$post['post_id'].'"><i class="far fa-comment-alt"></i><span class="ms-1">'.$post['totalComments'].'</a></span>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</article>';
						}
					} else { ?>
						<div class="system-message error-content text-center bg-none p-3">
							<img src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto">
							<p class="pt-5">It's a little bit lonely here. We couldn't find anything...</p>
						</div>
				<?php } ?>
			</div>
			<div class="col-md-3">
				<div class="post-create-block text-center rounded">
					<a href="<?php if (isset($_SESSION['IS_AUTHORIZED'])) { ?>/t/create<?php } else { ?>/login<?php }?>"><i class="fas fa-plus"></i><span class="ms-3">Start a New Thread</span></a>
				</div>

				<div class="top-threads-container mt-4 mb-4 rounded px-3 py-3">
					<h5>Top Threads</h5>
					<div class="top-thread-container">
					<?php 
					
						require_once SERVER_DIR.'/controllers/ThreadController.class.php';

						$threads = (new ThreadController())->getTopThreads();
						$counter = 0;

						if (count($threads) == 0) {
							echo '<p class="text-center mt-3">Not information.</p>';
						} else {
							foreach($threads as $thread) {
								echo '<div class="top-thread-container-info d-flex align-middle py-2">';
								echo '<div class="top-thread-info-name me-auto">';
								if ($counter == 0) {
									echo '<i class="fas fa-trophy first-place"></i>';
									echo '<span class="ms-1"><a href="/t/'.$thread['thread_url'].'/">t/'.$thread['thread_url'].'/</a></span>';
								} else if ($counter == 1) {
									echo '<i class="fas fa-trophy second-place"></i>';
									echo '<span class="ms-1"><a href="/t/'.$thread['thread_url'].'/">t/'.$thread['thread_url'].'/</a></span>';
								} else if ($counter == 2) {
									echo '<i class="fas fa-trophy third-place"></i>';
									echo '<span class="ms-1"><a href="/t/'.$thread['thread_url'].'/">t/'.$thread['thread_url'].'/</a></span>';
								} else {
									echo '<span class="ms-1"><a href="/t/'.$thread['thread_url'].'/">t/'.$thread['thread_url'].'/</a></span>';
								}
								echo '</div><div class="top-thread-info-upvote"><span class="me-2">'.$thread['total_posts'].'</span><i class="fas fa-arrow-up"></i>';
								echo '</div>';
								echo '</div>';
								$counter += 1;
							}
						}
					?>
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