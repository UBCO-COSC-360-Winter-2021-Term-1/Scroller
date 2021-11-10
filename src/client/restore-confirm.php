<main class="recover-page w-75">
	<div class="row">
		<div class="col-md-6 bg-white">
			<div class="recover-container p-4 py-3 mt-5 mx-auto w-100">
				<a href="/" class="d-flex align-items-center brand me-3 fs-2 mb-5">
					<i class="fas fa-mouse"></i><span class="ms-2">Scroller</span>
				</a>		
				
				<h4 class="mb-5">Almost There</h4>

				<form method="POST">
					<div class="mb-4 inputs">
						<label for="codeInput" class="form-label text-uppercase">New Password</label>
						<input type="password" id="passwordNewInput" required placeholder="New Password">
					</div>
					<div class="mb-4 inputs">
						<label for="codeInput" class="form-label text-uppercase">Confirm New Password</label>
						<input type="password" id="passwordNewConfirmInput" required placeholder="Confirm New Password">
					</div>
					<div class="btn-container text-uppercase d-flex justify-content-between mt-5 mb-4 w-50">
						
						<button type="submit" class="recover-confirm-final">Recover</button>
					</div>
				</form>

				<div class="system-message mt-3 bg-danger d-inline-flex px-3 py-1 fade-in-text mb-4 w-100 d-none">
						
					<div class="align-self-center">
						<i class="fas fa-exclamation-triangle"></i>
					</div>
					<div class="ms-3 mt-1 align-self-center">
						<h5>Error</h5>
						<p></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 d-none d-md-block d-xl-block bg-white">
			<div class="recover-desc-container text-light position-relative">
				<div class="recover-confirm-desc-bg-image"></div>
				<div class="recover-desc-container-content position-absolute top-50 start-50 translate-middle fade-in-text">
					<h5 class="text-uppercase mt-5 fw-light">Welcome to</h5>
					<h2 class="text-uppercase mt-3 fw-bolder mb-3">Our Community</h2>
					<hr>
					<p class="mt-3">We can't wait to get you back</p>
				</div>
			</div>
		</div>
	</div>
</main>