<?php 
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");
?>
	<!-- Main Content -->
	<main class="w-75 mx-auto mt-5">
  	<div class="row">
			<div class="col-md-3 menu">
				<h6 class="text-uppercase">Menu</h6>
				<nav class="mt-3">
					<ul>
						<li><a href="/" class="rounded"><i class="fas fa-home"></i><span class="ms-2">Home</span></a></li>
						
						<?php if (isset($_SESSION['IS_AUTHORIZED']) && isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN']) { ?>
                        <li><a href="/admin" class="rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
                        <?php } ?>
						
						<li><a href="/search?q=all" class="active rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
						<li><a href="/search?q=d3li0n" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
						<li><a href="/serach?q=d3li0n&comments=d3li0n" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
					</ul>
				</nav>
			</div>
			<div class="create-post-big-container col-md-9 overflow-auto mx-auto mb-4 p-4">
				<h2>Create a Post</h2>
				<!-- Error Thread Creating-->
				<!--<div class="system-message bg-danger mb-3 mt-4">
					<div class="system-message-content d-inline-flex px-3 py-3 w-100">
						<i class="fas fa-ban text-center my-auto text-light"></i>
						<p class="ms-3 my-auto">This thread cannot be created. <br><span class="fw-bolder">Reason: </span> Invalid title. Image Error.</p>
					</div>
				</div>-->
				<!-- Normal content flow -->
				<form enctype="multipart/form-data">
					<div class="create-post-title-container mt-4">
						<div class="description">
							<h5 class="fw-bold mb-3">Post Title</h5>
                            <p>Before our community can access your post, you need to come up with a unique title.</p>
						</div>
						<div class="create-post-content bg-white p-4 rounded">
							<label for="create-post-name" class="d-block">Post Title:</label>
							<div class="d-inline-flex align-items-center mt-2 w-100">
								<input type="text" id="create-post-name" class="p-1" name="post-title" placeholder="An Interesting Title" required>
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

                    <div class="create-post-cover-container mt-5">
						<div class="description">
							<h5 class="fw-bold">Post Body Text</h5>
							<p>Here you can add additional text to your post.</p>
						</div>
						<div class="create-post-content-text-body bg-white p-4 rounded">
							<label for="create-post-text" class="d-block">Post Text:</label>
							<div class="d-inline-flex justify-content-between align-items-center mt-2 w-100">
								<textarea class="ps-2 py-1" name="post-text" id="create-post-text" rows="5" placeholder=" Text (optional)"></textarea>
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

					<div class="create-post-cover-container mt-5">
						<div class="description">
							<h5 class="fw-bold">Upload Images</h5>
							<p>If you would like, you can upload an image to your post!</p>
						</div>
						<div class="create-post-content-post-image bg-white p-4 rounded">
							<label for="create-post-image" class="d-block">Post Image:</label>
							<div class="d-inline-flex align-items-center mt-2 w-100">
								<input type="file" name="cover-post-image" id="create-post-image" accept="image/png, image/jpg, image/gif" required>
							</div>
							<div class="mt-2">
								<img alt="preview post image" class="d-none create-post-create-post-cover-pic" id="profile-post-create-preview">
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

                    <div class="create-post-cover-container mt-5">
						<div class="description">
							<h5 class="fw-bold">YouTube Link</h5>
							<p>If you would like, you can post a link to a YouTube video.</p>
						</div>
						<div class="create-post-content-post-url bg-white p-4 rounded">
							<label for="create-post-text-url" class="d-block">YouTube URL</label>
							<div class="d-inline-flex justify-content-between align-items-center mt-2 w-100">
								<input type="text" class="ps-2 py-1" name="post-text" id="create-post-text-url" placeholder=" URL"></textarea>
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

					<button type="submit" class=" p-3 mt-3 btn-create-post">Create Post</button>
				</form>
			</div>
		</div>
	</main>