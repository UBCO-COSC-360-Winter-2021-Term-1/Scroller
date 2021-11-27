<?php 
	$urlSecurity = $_SERVER['REQUEST_URI'];
	$urlSecurity = substr($urlSecurity, strpos($urlSecurity, ".") + 1);
	if ($urlSecurity === "php")
		header("Location: /");
?>
	<main class="register-page w-75">
		<div class="row">
			<div class="col-md-6 bg-white">
				<div class="register-container p-4 py-3 mt-5 mx-auto w-100">
					<a href="/" class="d-flex align-items-center brand me-3 fs-2 mb-5">
						<i class="fas fa-mouse"></i><span class="ms-2">Scroller</span>
					</a>		
					
					<h4 class="mb-5">Let's Get Started</h4>

					<form>
						<div class="mb-4 inputs">
							<label for="userNameInput" class="form-label text-uppercase">Username</label>
							<input type="text" id="userNameRegisterInput" required placeholder="scroller">
						</div>
						<div class="btn-container text-uppercase d-flex justify-content-between mt-5 mb-4">
							<button type="submit" class="go-back step-one">Go Back</button>
							<button type="submit" class="go-next step-one">Next</button>
						</div>
					</form>

					<div class="system-message mt-1 bg-info-custom d-inline-flex px-3 py-1 fade-in-text mb-2 w-100">
							
						<div class="align-self-center">
							<i class="fas fa-info"></i>
						</div>
						<div class="ms-3 mt-1 align-self-center">
							<h5>Your Username</h5>
							<p>Our community guidelines say that your username should not be longer than 8 characters and doesn't include any special characters.</p>
						</div>
					</div>

					<div class="system-message bg-danger d-inline-flex px-3 py-2 fade-in-text w-100 d-none">
						
						<div class="align-self-center">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
						<div class="ms-3 mt-1 align-self-center">
							<h5>Oops...</h5>
							<p></p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 d-none d-md-block d-xl-block bg-white">
				<div class="register-desc-container text-light position-relative">
					<div class="register-desc-bg-image"></div>
					<div class="login-desc-container-content position-absolute top-50 start-50 translate-middle fade-in-text">
						<h5 class="text-uppercase mt-5 fw-light">Welcome to</h5>
						<h2 class="text-uppercase mt-3 fw-bolder mb-3">Our Community</h2>
						<hr>
						<p class="mt-3">Your journey here just begins...</p>
					</div>
				</div>
			</div>
		</div>
	</main>