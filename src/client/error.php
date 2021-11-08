<?php 
	$url = $_SERVER['REQUEST_URI'];
	$url = substr($url, strpos($url, ".") + 1);
	if ($url === "php")
		header("Location: /");
?>
<main class="mt-5 w-75 mx-auto">
	<div class="row">
		<div class="col-md-6 mt-5">
			<img src="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/client/img/error-page-vector.svg" class="img-error-vector" alt="Error Page Description">
		</div>
		<div class="col-md-6 mt-5">
			<h1 class="fw-bold">Oops...</h1>
			<p class="mt-5">Seems like you experienced some error.</p>
			<p>Error Code: <span class="text-danger fw-bold">404 - Page Not Found</span></p>
			<a href="/" class="d-block mt-5 header-icon btn-back-err">
				Take me back
			</a>
		</div>
	</div>
</main>
