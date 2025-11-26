<?php
include "admin_auth.php";
include_once "../config.php";

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
try {
    $settingsResult = executeQuery($con, "SELECT * FROM settings LIMIT 1");
    $allData = [mysqli_fetch_assoc($settingsResult) ?: []];
} catch (Exception $e) {
    error_log("Settings error: " . $e->getMessage());
    $allData = [['site_name' => 'eBook Store']];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>eBook Store - Admin Dashboard</title>
	<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
	<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">

	<style id="dark-mode-styles">
		.dark-mode {
			background-color: #1a1a1a !important;
			color: #e0e0e0 !important;
		}
		.dark-mode .bg-white {
			background-color: #2d2d2d !important;
		}
		.dark-mode .text-gray-800 {
			color: #e0e0e0 !important;
		}
		.dark-mode .text-gray-600 {
			color: #b0b0b0 !important;
		}
		.dark-mode .card {
			background-color: #2d2d2d !important;
			border-color: #404040 !important;
		}
		.dark-mode .table {
			color: #e0e0e0 !important;
		}
		.dark-mode .table-bordered th,
		.dark-mode .table-bordered td {
			border-color: #404040 !important;
		}
		.dark-mode .form-control {
			background-color: #3d3d3d !important;
			border-color: #404040 !important;
			color: #e0e0e0 !important;
		}
		.dark-mode .navbar.bg-white {
			background-color: #2d2d2d !important;
		}
		.dark-mode .sidebar {
			background-color: #1a1a1a !important;
		}
	</style>

</head>

<body id="page-top">

	<div id="wrapper">
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
				<div class="sidebar-brand-icon">
					<i class="fas fa-book-open"></i>
				</div>
				<?php foreach($allData as $logodata){?>
				<div class="sidebar-brand-text mx-3"><?php echo $logodata['site_name']?></div>
				<?php }?>
			</a>
			<hr class="sidebar-divider my-0">
			<li class="nav-item active">
				<a class="nav-link" href="index.php">
					<i class="fas fa-fw fa-tachometer-alt"></i>
					<span>Dashboard</span></a>
			</li>
			<hr class="sidebar-divider">
			<div class="sidebar-heading">
				Library Management
			</div>
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBooks"
					aria-expanded="false" aria-controls="collapseBooks">
					<i class="fas fa-fw fa-book"></i>
					<span>Books</span>
				</a>
				<div id="collapseBooks" class="collapse" aria-labelledby="headingBooks" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Book Management:</h6>
						<a class="collapse-item" href="add_book.php"><i class="fas fa-plus-circle me-2"></i>Add Book</a>
						<a class="collapse-item" href="all_books.php"><i class="fas fa-list me-2"></i>All Books</a>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategories"
					aria-expanded="false" aria-controls="collapseCategories">
					<i class="fas fa-fw fa-tags"></i>
					<span>Categories</span>
				</a>
				<div id="collapseCategories" class="collapse" aria-labelledby="headingCategories" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Genre Management:</h6>
						<a class="collapse-item" href="add_category.php"><i class="fas fa-plus-circle me-2"></i>Add Genre</a>
						<a class="collapse-item" href="all_categories.php"><i class="fas fa-list me-2"></i>All Genres</a>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAuthors"
					aria-expanded="false" aria-controls="collapseAuthors">
					<i class="fas fa-fw fa-user-edit"></i>
					<span>Authors</span>
				</a>
				<div id="collapseAuthors" class="collapse" aria-labelledby="headingAuthors" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Author Management:</h6>
						<a class="collapse-item" href="add_author.php"><i class="fas fa-plus-circle me-2"></i>Add Author</a>
						<a class="collapse-item" href="all_authors.php"><i class="fas fa-list me-2"></i>All Authors</a>
					</div>
				</div>
			</li>
			<hr class="sidebar-divider">
			<div class="sidebar-heading">
				User Management
			</div>
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
					aria-expanded="false" aria-controls="collapseUsers">
					<i class="fas fa-fw fa-users"></i>
					<span>Users</span>
				</a>
				<div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">User Management:</h6>
						<a class="collapse-item" href="all_users.php"><i class="fas fa-users me-2"></i>All Users</a>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="orders.php">
					<i class="fas fa-fw fa-shopping-cart"></i>
					<span>Orders</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="messages.php">
					<i class="fas fa-fw fa-envelope"></i>
					<span>Messages</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="settings.php">
					<i class="fas fa-fw fa-cogs"></i>
					<span>Settings</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="../index.php" target="_blank">
					<i class="fas fa-fw fa-globe"></i>
					<span>Show Website</span>
				</a>
			</li>
			<hr class="sidebar-divider d-none d-md-block">
			<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>

		</ul>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
			     <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>
					<button id="darkModeToggle" class="btn btn-outline-primary btn-sm mr-3" title="Toggle Dark Mode">
						<i class="fas fa-moon"></i>
					</button>

					<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
						<div class="input-group">
							<input type="text" class="form-control bg-light border-0 small" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<button class="btn btn-primary" type="button">
									<i class="fas fa-search fa-sm"></i>
								</button>
							</div>
						</div>
					</form>

					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown no-arrow mx-1">
							<a class="nav-link" href="#" title="Notifications">
								<i class="fas fa-bell fa-fw"></i>
								<span class="badge badge-danger badge-counter">3+</span>
							</a>
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small">Hello, <?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest" ?></span>
								<img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="profile.php">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									Profile
								</a>
								<a class="dropdown-item" href="settings.php">
									<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
									Settings
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="../logout.php" data-toggle="modal" data-target="#logoutModal">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Logout
								</a>
							</div>
						</li>

					</ul>

				</nav>
				<script>
				document.addEventListener('DOMContentLoaded', function() {
					const darkModeToggle = document.getElementById('darkModeToggle');
					const body = document.body;
					const savedTheme = localStorage.getItem('theme');
					const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
					if (savedTheme === 'dark' || (savedTheme !== 'light' && prefersDarkScheme.matches)) {
						body.classList.add('dark-mode');
						darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
					}
					darkModeToggle.addEventListener('click', function() {
						body.classList.toggle('dark-mode');
						if (body.classList.contains('dark-mode')) {
							localStorage.setItem('theme', 'dark');
							darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
						} else {
							localStorage.setItem('theme', 'light');
							darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
						}
					});
				});
</script>