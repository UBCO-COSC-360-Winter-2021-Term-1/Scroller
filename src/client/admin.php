<?php 
	$url = $_SERVER['REQUEST_URI'];
	$url = substr($url, strpos($url, ".") + 1);
	if ($url === "php")
		header("Location: /");
?>
<!-- Main Content -->
<main class="w-75 mx-auto mt-5">
	<div class="row">
		<div class="col-md-2 menu">
			<h6 class="text-uppercase">Menu</h6>
			<nav class="mt-3">
				<ul>
					<li><a href="/" class="rounded"><i class="fas fa-home"></i><span class="ms-2">Home</span></a></li>
					<li><a href="/admin" class="active rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
					<li><a href="/admin/users" class="rounded"><i class="fas fa-users-cog"></i><span class="ms-2">Users</span></a></li>
					<li><a href="/admin/threads" class="rounded"><i class="fas fa-question"></i><span class="ms-2">Threads</span></a></li>
				</ul>
			</nav>
		</div>
		
		<div class="col-md-10 admin-dashboard overflow-auto mx-auto mb-4">
			<div class="row">
				<div class="col-sm-5">
					<?php 
						require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/AdminController.class.php';
				
						$stats = (new AdminController())->getStats([]);

						function numsize($size, $round=2){
							$unit=['', 'K', 'M', 'B', 'T'];
							if ((int)$size !== 0)
								return round($size/pow(1000,($i=floor(log($size,1000)))),$round).$unit[$i];
							return $size;
						}
					?>
					<div class="admin-d-users d-flex align-items-center p-3 rounded mb-4">
						<div class="icon">
							<i class="fas fa-users"></i>
						</div>
						<div class="ms-auto desc d-block align-right">
							<h5>Registered Users</h5>
							<span><?php echo numsize($stats[0]); ?></span>
						</div>
					</div>

					<div class="admin-d-threads d-flex align-items-center p-3 rounded mb-4">
						<div class="icon">
							<i class="fas fa-question"></i>
						</div>
						<div class="ms-auto desc d-block align-right">
							<h5>Threads</h5>
							<span><?php echo numsize($stats[1]); ?></span>
						</div>
					</div>

					<div class="admin-d-posts d-flex align-items-center p-3 rounded mb-4">
						<div class="icon">
							<i class="far fa-clone"></i>
						</div>
						<div class="ms-auto desc d-block align-right">
							<h5>Posts</h5>
							<span><?php echo numsize($stats[3]); ?></span>
						</div>
					</div>

					<div class="admin-d-comments d-flex align-items-center p-3 rounded mb-4">
						<div class="icon">
							<i class="far fa-comment"></i>
						</div>
						<div class="ms-auto desc d-block align-right">
							<h5>Comments</h5>
							<span><?php echo numsize($stats[2]); ?></span>
						</div>
					</div>

				</div>
				<div class="col-sm-7">
					<h3 class="fw-bold mb-3">🔥 Trending</h3>
					<div class="overflow-auto admin-d-trending">
					<?php 
					require_once SERVER_DIR.'/controllers/PostController.class.php';

					$posts = (new PostController())->loadAllPosts();
					if (count($posts) != 0) {
						foreach ($posts as $post) {
							echo '<article class="rounded p-4 mb-5 bg-white">';
								echo '<div class="row">';
									echo '<div class="col-sm-2">';
										echo '<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting" data-post-id="'.$post['post_id'].'">';
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
				</div>
			</div>
		</div>
	</div>
</main>