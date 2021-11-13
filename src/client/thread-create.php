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
						
						<li><a href="/search" class="active rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
						<li><a href="/search" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
						<li><a href="/serach" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
					</ul>
				</nav>
			</div>
			<div class="create-thread-big-container col-md-9 overflow-auto mx-auto mb-4 p-4">
				<h2>Create Your Thread</h2>
				<!-- Error Thread Creating-->
				<div class="system-message bg-danger d-none mb-3 mt-4">
					<div class="system-message-content d-inline-flex px-3 py-3 w-100">
						<i class="fas fa-ban text-center my-auto text-light"></i>
						<p class="ms-3 my-auto">This thread cannot be created. <br><span class="fw-bolder">Reason: </span><span class="error-message"></span></p>
					</div>
				</div>
				<!-- Normal content flow -->
				<form enctype="multipart/form-data">
					<div class="create-thread-title-container mt-4">
						<div class="description">
							<h5 class="fw-bold">Thread Title</h5>
							<p>Before our community can access your thread, you need to come with a unique name. In future, users will be able to post their posts relevant to your thread theme.</p>
						</div>
						<div class="create-thread-content bg-white p-4 rounded">
							<label for="create-title" class="d-block">Thread Title:</label>
							<div class="d-inline-flex align-items-center mt-2 w-100">
								<input type="text" id="create-thread-name" class="p-1" name="title" placeholder="Cute Kittens" required>
								<span class="ms-4 create-thread-suggested-name">URL: <span id="create-thread-suggest-url"></span></span>
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

					<div class="create-thread-cover-container mt-5">
						<div class="description">
							<h5 class="fw-bold">Thread Background Image Cover</h5>
							<p>Each thread must have background cover image relevant to their theme.</p>
						</div>
						<div class="create-thread-content-thread-cover-image bg-white p-4 rounded">
							<label for="create-background-image" class="d-block">Thread Background Image:</label>
							<div class="d-inline-flex align-items-center mt-2 w-100">
								<input type="file" name="cover-thread-image" id="create-thread-upload-cover" accept="image/png, image/jpg, image/gif" required>
							</div>
							<div class="mt-2">
								<img alt="preview cover thread" class="d-none create-thread-cover-pic" id="profile-thread-create-cover">
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>

					<div class="create-thread-cover-container mt-5">
						<div class="description">
							<h5 class="fw-bold">Thread Profile Image</h5>
							<p>Each thread must have thread image relevant to their theme.</p>
						</div>
						<div class="create-thread-content-thread-image bg-white p-4 rounded">
							<label for="create-background-image" class="d-block">Thread Profile Image:</label>
							<div class="d-inline-flex justify-content-between align-items-center mt-2 w-100">
								<input type="file" name="profile-thread-image" id="create-thread-upload-photo" accept="image/png, image/jpg, image/gif" required>
								<div>
									<img alt="preview profile thread" class="d-none" id="profile-thread-create-preview">
								</div>
							</div>
							<div class="system-message error mt-2">
								<p class="d-none"></p>
							</div>
						</div>
					</div>
					<button type="submit" class=" p-3 mt-3 btn-create-thread">Create Thread</button>
				</form>
			</div>
		</div>
	</main>