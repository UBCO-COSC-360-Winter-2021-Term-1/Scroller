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
						<li><a href="/admin/users" class="rounded"><i class="fas fa-users-cog"></i><span class="ms-2">Users</span></a></li>
						<li><a href="/admin/threads" class="active rounded"><i class="fas fa-question"></i><span class="ms-2">Threads</span></a></li>
					</ul>
				</nav>
			</div>
			
			<div class="col-md-10 admin-dashboard mx-auto mb-4">
				<h3 class="fw-bold mb-3">All Threads</h3>

				<form class="admin-search-threads-container bg-white p-3 rounded">
					<div>
						<label for="search-content" class="d-block fw-bold">Thread Name</label>
						<input type="text" placeholder="Thread Name" class="mt-2 p-2 w-100 search-input-thread" name="search">
					</div>
				</form>

				<?php
					require_once $_SERVER["DOCUMENT_ROOT"].'/server/controllers/AdminController.class.php';
					$threads = (new AdminController())->getAllThreads([]);
					if (count($threads) === 0) {
						echo '<div class="system-message error-content text-center bg-none p-3 account-no-content"><img src="http://'.$_SERVER['HTTP_HOST'].'/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto"><p class="pt-5">Information not found</p></div>';
					} else {
				?>

				<div class="admin-search-threads-table table-responsive overflow-auto">
					<table class="mt-4 admin-dashboard-threads w-100 table">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Name</th>
								<th scope="col">URL</th>
								<th scope="col">Created</th>
								<th scope="col">Owner</th>
								<th scope="col">Status</th>
								<th scope="col">Members</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach ($threads as $thread) {
									echo "<tr><td scope='row'>".$thread['thread_id']."</td>";
									echo "<td>".$thread['thread_title']."</td>";
									echo "<td><a href=\"/t/".$thread['thread_url']."\">/t/".$thread['thread_url']."/</a></td>";
									echo "<td>".$thread['created_date']."</td>";
									echo "<td><a href=\"/account/".$thread['ownerId']."\">".$thread['ownerName']."</a></td>";

									if (!$thread['is_locked'] && !$thread['is_deleted'])
										echo "<td><span class=\"bg-success rounded p-1 text-light\">Active</span></td>";
									else if ($thread['is_locked'] && !$thread['is_deleted'])
										echo "<td><span class=\"bg-warning rounded p-1 text-light\">Hidden</span></td>";
									else
										echo "<td><span class=\"bg-danger rounded p-1 text-light\">Deleted</span></td>";
									
									echo "<td>".$thread['members']."</td>";
									echo "<td>";
									if (!$thread['is_locked'] && !$thread['is_deleted']) 
										echo "<button class=\"admin-threads-act-delete\" data-id=\"".$thread['thread_id']."\" data-status=\"delete\">Delete</button><br><button class=\"admin-threads-act-hide hide\" data-id=\"".$thread['thread_id']."\" data-status=\"hide\">Hide</button><br>";
									else
										echo "<button class=\"admin-threads-act-restore\" data-id=\"".$thread['thread_id']."\" data-status=\"restore\">Restore</button>";
									echo "</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
				<?php } ?>
			</div>
		</div>
	</main>