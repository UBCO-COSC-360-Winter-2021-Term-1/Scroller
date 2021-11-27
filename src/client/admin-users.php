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
						<li><a href="/admin" class="rounded"><i class="fas fa-toolbox"></i><span class="ms-2">Admin Portal</span></a></li>
						<li><a href="/admin/users" class="active rounded"><i class="fas fa-users-cog"></i><span class="ms-2">Users</span></a></li>
						<li><a href="/admin/threads" class="rounded"><i class="fas fa-question"></i><span class="ms-2">Threads</span></a></li>
					</ul>
				</nav>
			</div>
			
			<div class="col-md-10 admin-dashboard mx-auto mb-4">
				<h3 class="fw-bold mb-3">All Users</h3>

				<form class="admin-search-users-container bg-white p-3 rounded">
					<div>
						<label for="search-content" class="d-block fw-bold">Username</label>
						<input type="text" placeholder="Enter username" class="mt-2 p-2 w-100 search-input-box" name="search">
					</div>
				</form>

				<?php
					require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/AdminController.class.php';
					$users = (new AdminController())->getAllUsers([]);
					if (count($users) === 0) {
						echo '<div class="system-message error-content text-center bg-none p-3 account-no-content"><img src="http://'.$_SERVER['HTTP_HOST'].'/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto"><p class="pt-5">Information not found</p></div>';
					} else {
				?>
				<div class="admin-search-users-table table-responsive overflow-auto">
					
					<table class="mt-4 admin-dashboard-users w-100 table">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Username</th>
								<th scope="col">Register Date</th>
								<th scope="col">Email</th>
								<th scope="col">Email Status</th>
								<th scope="col">Admin</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 

								foreach ($users as $user) {
									echo "<tr><td scope='row'>".$user['id']."</td>";
									echo "<td><a href='/account/".$user['id']."'>".$user['username']."</a></td>";
									echo "<td>".$user['regdate']."</td>";
									echo "<td>".$user['email']."</td>";
									echo ($user['is_email_confirmed']) ? "<td><span class=\"bg-success rounded p-1 text-light\">Confirmed</span></td>" : "<td><span class=\"bg-danger rounded p-1 text-light\">Not Confirmed</span></td>";
									echo ($user['is_admin']) ? "<td><span class=\"admin-user-status true p-1 rounded text-light\">Yes</span></td>" : "<td><span class=\"admin-user-status false p-1 rounded text-light\">No</span></td>";
									echo "<td>";
									echo ($user['is_account_disabled']) ? "<button class=\"admin-users-act-block\" data-id=\"".$user['id']."\" data-status=\"unblock\">Unblock</button><br>" : "<button class=\"admin-users-act-block\" data-id=\"".$user['id']."\" data-status=\"block\">Block</button><br>";	
									echo ($user['is_admin']) ? "<button class=\"admin-users-act-admin\" data-id=\"".$user['id']."\" data-status=\"demote-admin\">Demote Admin</button>" : "<button class=\"admin-users-act-admin\" data-id=\"".$user['id']."\" data-status=\"new-admin\">Make Admin</button>";
									echo "</td></tr>";
								}
							?>
						</tbody>
					</table>
				</div>
				<?php } ?>
			</div>
		</div>
	</main>
