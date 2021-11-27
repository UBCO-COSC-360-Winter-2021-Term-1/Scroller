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
					<li><a href="/search" class="rounded"><i class="far fa-compass"></i><span class="ms-2">Threads</span></a></li>
					<li><a href="/search" class="rounded"><i class="fas fa-question"></i><span class="ms-2">My Threads</span></a></li>
					<li><a href="/search" class="rounded"><i class="far fa-comment-alt"></i><span class="ms-2">My Replies</span></a></li>
				</ul>
			</nav>
		</div>

		
		<div class="col-md-7 account-page overflow-auto mx-auto mb-4">
			<h3 class="mb-4">General</h3>
			<div class="general-account-block-content bg-white p-3">
				<form enctype="multipart/form-data">
					<h5>Change Profile Picture</h5>
					<div class="mt-3 w-100 account-change-profile-picture d-inline-flex align-items-center">
						<img class="d-block account-updated-profile-picture" src="<?php echo $_SESSION['USER_IMAGE']; ?>" alt="<?php echo $_SESSION['USERNAME']; ?>-profile-picture">
				
						<input type="file" name="profile-picture-update" id="profile-settings-picture" class="ms-3" accept="image/png, image/jpg, image/gif">
					</div>

					<h5 class="mt-5">Change Username</h5>
					<div class="account-settings-change-username d-block mt-3">
						<label for="profile-settings-username">Username:</label>
						<input type="text" name="profile-username-update" id="profile-settings-username" class="w-100 p-2" placeholder="<?php echo $_SESSION['USERNAME']; ?>">
					
					</div>


					<h5 class="mt-5">Change Password</h5>
					<div class="account-settings-change-password row mt-3">
						<div class="old-password-block col-sm-6 mb-3">
							<label for="profile-settings-oldpassword" class="d-block mb-2">Old Password:</label>
							<input type="password" name="profile-oldpassword-update" id="profile-settings-oldpassword" class="p-2 w-100" placeholder="Old Password">
						</div>
						<div class="new-password-block col-sm-6 mb-3">
							<label for="profile-settings-newpassword" class="d-block mb-2">New Password:</label>
							<input type="password" name="profile-newpassword-update" id="profile-settings-newpassword" class="p-2 w-100" placeholder="New Password">
						</div>
					</div>

					<div class="system-message bg-danger mt-3 d-none">
						<div class="system-message-content d-inline-flex px-3 py-3 w-100">
							<i class="fas fa-ban text-center my-auto text-light"></i>
							<p class="ms-3 my-auto">Error in updating settings.<br><span class="fw-bolder">Reason:</span> <span class="reason"></span></p>
						</div>
					</div>

					<button class="mt-4 btn-account-update rounded p-2">Update Settings</button>
					
				</form>
			</div>

			<h3 class="mb-3 mt-4">Other</h3>
			<div class="delete-block-content bg-white p-3">
				<p>Once you disable your account, there is no going back. Please be certain.</p>
				<button class="btn btn-account-delete">Deactivate Account</button>
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
