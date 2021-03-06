<?php 																						
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");

	require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/ThreadController.class.php';
	require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/PostController.class.php';
	$threadInfo = (new ThreadController())->getThread($url[1]);
	$currentPost = (new PostController())->loadSpecificPost([$url[1], $url[2]]);
?>
   <div class="thread-navbar mb-5">
        <div class="img-thread-background" 
		style="background-image: url('<?php echo 'http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/thread_backgrounds/'.$threadInfo[0]['thread_background'];?>">
		</div>
        <div class="bg-light">																
            <div class="w-75 mx-auto">
                <div class="d-inline-flex justify-content-center w-50">
                    <img id="img-thread-profile" src="<?php echo 'http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/thread_profile/'.$threadInfo[0]['thread_profile'];?>" alt="thread_profile_picture" class="img-thumbnail me-2">
					<div class="py-2">
						<h3 class=""><?php echo $threadInfo[0]["thread_title"]?></h3>
						<a href="<?php echo "/t" ."/". "$url[1]"?>" class="thread-sm-link"><?php echo "t/" . $url[1]?></a>
					</div>
					<div class="py-2 ms-3">
						<button type="button" class="join-thread-button" data-status="<?php echo $threadInfo[0]["isSubscribed"];?>">
							<?php 
								if ($threadInfo[0]["isSubscribed"] == 0) {
									echo "Join";
								} else {
									echo "Leave";
								}
							?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Main Content -->
	<main class="w-75 mx-auto">
  	<div class="row">
			<div class="col-md-2 menu">
				<h6 class="text-uppercase">Menu</h6>
				<nav class="mt-3">
					<ul>
						<li><a href="/" class="rounded"><i class="fas fa-home"></i><span class="ms-2">Home</span></a></li>
						<?php if (isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) { ?>
						<li><a href="/admin" class="rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
						<?php } ?>
						<li><a href="/search" class="active rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
						<li><a href="/search" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
						<li><a href="/search" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
					</ul>
				</nav>
			</div>
			<div class="col-md-6 topic-threads topic-post-single overflow-auto mx-auto mb-4">
				<!-- Disabled Thread -->
				<?php 
					if (!empty($currentPost)) {
						if ($threadInfo[0]["is_locked"] == 1 || $currentPost['isHidden'] == 1) {
				?>
				<div class="system-message bg-danger mb-3">
					<div class="system-message-content d-inline-flex px-3 py-3 w-100">
						<i class="fas fa-ban text-center my-auto text-light"></i>
						<p class="ms-3 my-auto">This post was disabled by Administrator.<br><span class="fw-bolder">Reason:</span> Violation of Community Guidelines.</p>
					</div>
				</div>
				<?php } }?>
				<!-- Normal Content-->
				<?php if (!empty($currentPost)) { 
					require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/CommentController.class.php';
					echo '<article class="rounded p-4 mb-5">';
					echo '<div class="row">';
					echo '<div class="col-sm-2">';
					echo '<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting" data-post-id="'.$currentPost['post_id'].'">';
					if ($currentPost['isVoted'] == 0) {
						echo '<i class="fas fa-arrow-up my-auto"></i>';
						echo '<span class="d-block mt-2 mb-2"><a href="#">'.$currentPost['numOfVotes'].'</a></span>';
						echo '<i class="fas fa-arrow-down my-auto"></i>';
					} else if ($currentPost['isVoted'] == 1 && $currentPost['typeVote'] == 1) {
						echo '<i class="fas fa-arrow-up voted-up my-auto"></i>';
						echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-up">'.$currentPost['numOfVotes'].'</a></span>';
						echo '<i class="fas fa-arrow-down my-auto"></i>';
					} else if ($currentPost['isVoted'] == 1 && $currentPost['typeVote'] == -1) {
						echo '<i class="fas fa-arrow-up my-auto"></i>';
						echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">'.$currentPost['numOfVotes'].'</a></span>';
						echo '<i class="fas fa-arrow-down voted-down my-auto"></i>';
					}
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-10">';
					echo '<h4><a href="/t/'.$currentPost['thread_url'].'/'.$currentPost['post_id'].'">'.$currentPost["title"].'</a></h4>';
					echo '<p class="no-border">';
					if (is_null($currentPost['post_image']) && is_null($currentPost['media_url']) && !is_null($currentPost['body'])) {
						echo $currentPost['body'];
					} else if (!is_null($currentPost['post_image']) && is_null($currentPost['media_url']) && is_null($currentPost['body'])) {
						echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$currentPost['post_image'].'" alt="content-img">';
					} else if (is_null($currentPost['post_image']) && !is_null($currentPost['media_url']) && is_null($currentPost['body'])) {
						echo '<iframe width="100%" height="300" src="'.$currentPost['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					} else if (!is_null($currentPost['post_image']) && is_null($currentPost['media_url']) && !is_null($currentPost['body'])) {
						echo $currentPost['body'];
						echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$currentPost['post_image'].'" alt="content-img" class="pt-2">';
					} else if (is_null($currentPost['post_image']) && !is_null($currentPost['media_url']) && !is_null($currentPost['body'])) {
						echo $currentPost['body'];
						echo '<iframe class="pt-2" width="100%" height="300" src="'.$currentPost['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					} else if (!is_null($post['post_image']) && !is_null($currentPost['media_url']) && !is_null($currentPost['body'])) {
						echo $currentPost['body'];
						echo '<img src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/post_images/'.$currentPost['post_image'].'" alt="content-img" class="pt-2">';
						echo '<iframe class="pt-2" width="100%" height="300" src="'.$currentPost['media_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					} else {
						echo $currentPost['body'];
					}
					echo '</p>';
					echo '<div class="post-info-container override d-flex justify-content-between mt-0">';
					echo '<div class="profile-info-sm d-flex align-middle">';
					echo '<img id="img-header-profile" class="img-fluid" src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/user_images/'.$currentPost['avatar_url'].'" alt="'.$currentPost['username'].'-profile-picture" />';
					echo '<span class="ms-2">Posted by <a href="/account/'.$currentPost['ownerId'].'">'.$currentPost['username'].'</a></span>';
					echo '</div>';
					if ($currentPost['timestamp'] / 60 < 60) {
						echo '<span class="d-block time-post">'.ceil($currentPost['timestamp'] / 60).'m ago</span>';
					} else if ($currentPost['timestamp'] / 60 >= 60 && $currentPost['timestamp'] / 60 < 1409) {
						echo '<span class="d-block time-post">'.ceil($currentPost['timestamp'] / 3600).'h ago</span>';
					} else {
						echo '<span class="d-block time-post">'.ceil($currentPost['timestamp'] / 86400).'d ago</span>';
					}
					echo '<div class="post-info-comments">';
					echo '<a href="/t/'.$currentPost['thread_url'].'/'.$currentPost['post_id'].'"><i class="far fa-comment-alt"></i><span class="ms-1">'.$currentPost['totalComments'].'</span></a>';
					echo '</div>';
					echo '</div>';
					if ((isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) || $_SESSION["USERNAME"] == $currentPost["username"]) {
						echo '<div class="mt-2 mb-2">';
						$currentPost['isHidden'] == 0 ? $buttonText = 'Hide' : $buttonText = 'Unhide';
						echo '<button id="hide" class="me-4 post-hide" data-post-id="'.$currentPost['post_id'].'">'.$buttonText.'</button>';
						echo '<button id="delete" class="post-delete" data-post-id="'.$currentPost['post_id'].'">Delete</button>';
						echo '</div>';
					}
					if ($currentPost['isHidden'] == 0 && $threadInfo[0]['is_locked'] == 0) {
					?>
						<div class="reply-post my-3">
								<h6>Comment as <span><a href="<?php echo '/' . 'account/' . $currentPost["currentUserId"].''?>"><?php echo $_SESSION['USERNAME']; ?>.</a></span></h6>
								<textarea id="postComment" class="w-100"></textarea>
								<button class="btn btn-sm btn-reply-post">Post Reply</button>
						</div>
					<?php }
						$postComments = (new CommentController())->loadCommentsByPost($currentPost["post_id"], 1);
						echo '<div class="post-article-r">';
							foreach($postComments as $comment){
								echo '<article class="rounded p-4 px-0">';
								echo '<div class="row">';
								echo '<div class="col-sm-2">';
								echo '<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center comment-voting" data-comment-id="'.$comment['comment_id'].'">';
								if ($comment['isVoted'] == 0) {
									echo '<i class="fas fa-arrow-up my-auto"></i>';
									echo '<span class="d-block mt-2 mb-2"><a href="#">'.$comment['numOfVotes'].'</a></span>';
									echo '<i class="fas fa-arrow-down my-auto"></i>';
								} else if ($comment['isVoted'] == 1 && $comment['typeVote'] == 1) {
									echo '<i class="fas fa-arrow-up voted-up my-auto"></i>';
									echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-up">'.$comment['numOfVotes'].'</a></span>';
									echo '<i class="fas fa-arrow-down my-auto"></i>';
								} else if ($comment['isVoted'] == 1 && $comment['typeVote'] == -1) {
									echo '<i class="fas fa-arrow-up my-auto"></i>';
									echo '<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">'.$comment['numOfVotes'].'</a></span>';
									echo '<i class="fas fa-arrow-down voted-down my-auto"></i>';
								}
								echo '</div>';
								echo '</div>';
								echo '<div class="col-sm-10">';
								echo '<p class="no-border">';
								echo $comment['body'];
								echo '</p>';
								echo '<div class="post-info-container override d-flex justify-content-between">';
								echo '<div class="profile-info-sm d-flex align-middle">';
								echo '<img id="img-header-profile" class="img-fluid" src="http://'.''.$_SERVER['HTTP_HOST'].'/server/uploads/user_images/'.$comment['avatar_url'].'" alt="'.$comment['username'].'-profile-picture" />';
								echo '<span class="ms-2"><a href="/account/'.$comment["ownerId"].'">'.$comment["username"].'</a> replied</span>';
								echo '</div>';
								if ($comment['timestamp'] / 60 < 60) {
									echo '<span class="d-block time-post">'.ceil($comment['timestamp'] / 60).'m ago</span>';
								} else if ($comment['timestamp'] / 60 >= 60 && $comment['timestamp'] / 60 < 1409) {
									echo '<span class="d-block time-post">'.ceil($comment['timestamp'] / 3600).'h ago</span>';
								} else {
									echo '<span class="d-block time-post">'.ceil($comment['timestamp'] / 86400).'d ago</span>';
								}
								echo '</div>';
								echo '<div class="mt-2">';
								if ((isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) || $_SESSION["USERNAME"] == $comment["username"]) {
									echo '<button id="delete" class="comment-delete" data-comment-id="'.$comment['comment_id'].'">Delete</button>';
								}
								echo '</div>';
								echo '</div>';
								echo '</div>';
								echo '</article>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</article>';
					} else {
						echo '<div class="system-message error-content text-center bg-none p-3 mt-2">'; ?>
						<img src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto">
						<?php
						echo '<p class="pt-5">It\'s a little bit lonely here. We couldn\'t find anything...</p>';
						echo '</div>';
					}
				?>
			</div>
			<div class="col-md-3">
				<div class="post-create-block text-center rounded">
				<?php 
					if (!empty($currentPost))
						if ($threadInfo[0]["is_locked"] == 0 && $currentPost['isHidden'] == 0) {?>
				<div class="post-create-block text-center rounded"><?php echo '<a href=/t/'.$url[1].'/create-post>';?><i class="fas fa-plus"></i><span class="ms-3">Start a New Topic</span></a></div>
				<?php } ?>
				</div>

				<div class="top-threads-container mt-4 mb-4 rounded px-3 py-3">
					<h5>Top Users</h5>
					<div class="top-thread-container">
						<?php $topUsers = (new ThreadController())->getTopUsers($url[1]); 
							if (count($topUsers) != 0) {
								foreach($topUsers as $user) {
									echo '<div class="top-thread-container-info d-flex align-middle py-2">';
									echo '<div class="top-thread-info-name me-auto d-inline-flex">';
									echo '<img class="img-fluid img-header-profile" src="http://'.$_SERVER['HTTP_HOST'].'/server/uploads/user_images/'.$user['avatar_url'].'" alt="'.$user['username'].'_profile_picture">';
									echo '<span class="ms-1"><a href="/account/'.$user["userId"].'">';
									echo $user['username'];
									echo '</a></span>';
									echo '</div>';
									echo '<div class="top-thread-info-upvote">';
									echo '<span class="me-2">'.$user['count'].'</span><i class="fas fa-arrow-up"></i>';
									echo '</div>';
									echo '</div>';
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