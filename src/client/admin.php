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
							return round($size/pow(1000,($i=floor(log($size,1000)))),$round).$unit[$i];
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
						<!--<article class="rounded p-4 mb-4 bg-white">
							<div class="row">
								<div class="col-sm-2">
									<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
										<i class="fas fa-arrow-up my-auto"></i>
										<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">56</a></span>
										<i class="fas fa-arrow-down voted-down my-auto"></i>
									</div>
								</div>
								<div class="col-sm-10">
									<h4><a href="/t/1">What is your Favorite Anime?</a></h4>
									<p class="no-border">
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
									</p>
									<div class="post-info-container override d-flex justify-content-between mt-0">
										<div class="profile-info-sm d-flex align-middle">
											<img class="img-fluid my-auto img-header-profile" src="data:image/jpeg;base64,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" alt="profile picture"/>
											<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
										</div>
										<span class="d-block time-post">52m ago</span>
										<div class="post-info-comments">
											<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</span></a>
										</div>
									</div>
									<div class="mt-2">
										<button id="hide" class="me-4 thread-hide">Hide</button>
										<button id="delete" class="thread-delete">Delete</button>
									</div>			
								</div>
							</div>
						</article>

						<article class="rounded p-4 mb-4 bg-white">
							<div class="row">
								<div class="col-sm-2">
									<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
										<i class="fas fa-arrow-up my-auto"></i>
										<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">56</a></span>
										<i class="fas fa-arrow-down voted-down my-auto"></i>
									</div>
								</div>
								<div class="col-sm-10">
									<h4><a href="/t/1">What is your Favorite Anime?</a></h4>
									<p class="no-border">
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
									</p>
									<div class="post-info-container override d-flex justify-content-between mt-0">
										<div class="profile-info-sm d-flex align-middle">
											<img class="img-fluid my-auto img-header-profile" src="data:image/jpeg;base64,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" alt="profile picture"/>
											<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
										</div>
										<span class="d-block time-post">52m ago</span>
										<div class="post-info-comments">
											<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</span></a>
										</div>
									</div>
									<div class="mt-2">
										<button id="hide" class="me-4 thread-hide">Hide</button>
										<button id="delete" class="thread-delete">Delete</button>
									</div>			
								</div>
							</div>
						</article>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</main>