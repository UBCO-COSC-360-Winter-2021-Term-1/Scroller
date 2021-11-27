<?php 
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");
?>
<main class="login-page w-75">
	<div class="row">
		<div class="col-md-6 bg-white">
			<div class="login-container p-4 py-3 mt-5 mx-auto w-100">
				<a href="/" class="d-flex align-items-center brand me-3 fs-2 mb-5">
					<i class="fas fa-mouse"></i><span class="ms-2">Scroller</span>
				</a>		

				<form method="POST" class="form-login">
					<div class="mb-4">
						<label for="emailInput" class="form-label text-uppercase">Email Address</label>
						<input type="email" id="emailLoginInput" placeholder="john@scroller.ca" required>
					</div>
					<div class="mb-4">
						<label for="passwordInput" class="form-label text-uppercase">Password</label>
						<input type="password" id="passwordLoginInput" placeholder="Password" required>
					</div>
					<div class="btn-container text-uppercase d-flex justify-content-between mt-5">
						<button type="submit" class="login-btn-login">Login</button>
						<a href="/register" class="text-capitalize">Create Account</a>
					</div>

					<p class="text-center mt-4 login-container-help-restore mb-4">Forgotten your login details? <a href="/restore">Get Help Signing in</a></p>
					
					<div class="system-message mt-3 bg-danger d-inline-flex px-3 py-2 fade-in-text w-100 d-none">
						
						<div class="align-self-center">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
						<div class="ms-3 mt-1 align-self-center">
							<h5>Oops...</h5>
							<p></p>
						</div>
					
					</div>
				</form>
			</div>
		</div>
		
		<div class="col-md-6 d-none d-md-block d-xl-block bg-white">
			<div class="login-desc-container text-light position-relative">
				<div class="login-desc-bg-image"></div>
				<div class="login-desc-container-content position-absolute top-50 start-50 translate-middle fade-in-text">
					<h5 class="text-uppercase mt-5 fw-light">Welcome to</h5>
					<h2 class="text-uppercase mt-3 fw-bolder mb-3">Our Community</h2>
					<hr>
					<p class="mt-3">Please, login to access our community</p>
				</div>
			</div>
		</div>
	</div>
</main>
