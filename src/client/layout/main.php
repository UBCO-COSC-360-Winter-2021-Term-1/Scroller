<?php 
	$url = $_SERVER['REQUEST_URI'];
	$url = substr($url, strpos($url, ".") + 1);
	if ($url === "php")
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
						<li><a href="/search?q=all" class="rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
						<li><a href="/search?q=d3li0n" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
						<li><a href="/serach?q=d3li0n&comments=d3li0n" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
					</ul>
				</nav>
			</div>
			<div class="col-md-6 topic-threads overflow-auto mx-auto mb-4">
				<?php 
					require_once SERVER_DIR.'/controllers/PostController.class.php';

					$result = (new PostController())->findAll(array());
					if ($result["response"] === 200) {
				?>
				
				<?php } else { ?>
					<div class="system-message error-content text-center bg-none p-3">
						<img src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto">
						<p class="pt-5">It's a little bit lonely here. We couldn't find anything...</p>
					</div>
				<?php } ?>
				<!--<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#">56</a></span>
								<i class="fas fa-arrow-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/ubco-directed-studies</a></span>
							<h4><a href="/t/1">My GPA</a></h4>
							<p>
								<img src="https://cloudinary-res.cloudinary.com/image/upload/c_limit,w_770/f_auto,fl_lossy,q_auto/Mario_1.gif" alt="content-img">
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up voted-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#" class="voted-up">56</a></span>
								<i class="fas fa-arrow-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/ylyl-videos</a></span>
							<h4><a href="/t/1">YLYL. Challenge #1</a></h4>
							<p>
								<iframe width="100%" height="300" src="https://www.youtube.com/embed/W4KpyYm_u8w" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">56</a></span>
								<i class="fas fa-arrow-down voted-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/cute-kittens</a></span>
							<h4><a href="/t/1">Title goes here</a></h4>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#">56</a></span>
								<i class="fas fa-arrow-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/cute-kittens</a></span>
							<h4><a href="/t/1">Title goes here</a></h4>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up voted-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#" class="voted-up">56</a></span>
								<i class="fas fa-arrow-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/cute-kittens</a></span>
							<h4><a href="/t/1">Title goes here</a></h4>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>
				<article class="rounded p-4 mb-5">
					<div class="row">
						<div class="col-sm-2">
							<div class="d-flex flex-md-column flex-sm-row justify-content-center justify-content-evenly text-center post-voting">
								<i class="fas fa-arrow-up my-auto"></i>
								<span class="d-block mt-2 mb-2"><a href="#" class="voted-down">56</a></span>
								<i class="fas fa-arrow-down voted-down my-auto"></i>
							</div>
						</div>
						<div class="col-sm-10">
							<span class="thread-name">Posted in <a href="/t/cute-kittens">/t/cute-kittens</a></span>
							<h4><a href="/t/1">Title goes here</a></h4>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum sem quis ex porta, porta eleifend est lacinia. Quisque elementum pretium congue. Phasellus euismod nisi vitae vestibulum lacinia. Aenean at nunc mauris. Phasellus dignissim ultrices nulla ac pretium...
							</p>
							<div class="post-info-container d-flex justify-content-between">
								<div class="profile-info-sm d-flex align-middle">
									<img id="img-header-profile" class="img-fluid" src="https://thumbor.forbes.com/thumbor/fit-in/416x416/filters%3Aformat%28jpg%29/https%3A%2F%2Fspecials-images.forbesimg.com%2Fimageserve%2F5f47d4de7637290765bce495%2F0x0.jpg%3Fbackground%3D000000%26cropX1%3D1699%26cropX2%3D3845%26cropY1%3D559%26cropY2%3D2704" alt="d3li0n-profile-picture" />
									<span class="ms-2">Posted by <a href="/account/1">d3li0n</a></span>
								</div>
								<span class="d-block time-post">52m ago</span>
								<div class="post-info-comments">
									<a href="/t/1"><i class="far fa-comment-alt"></i><span class="ms-1">50+</a></span>
								</div>
							</div>
						</div>
					</div>
				</article>-->
				
			</div>
			<div class="col-md-3">
				<div class="post-create-block text-center rounded">
					<a href="<?php if (isset($_SESSION['IS_AUTHORIZED'])) { ?>/t/create<?php } else { ?>/login<?php }?>"><i class="fas fa-plus"></i><span class="ms-3">Start a New Thread</span></a>
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