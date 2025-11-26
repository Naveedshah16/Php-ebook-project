<?php
include "admin_auth.php";
include_once "../config.php";

try {
    $userId = $_SESSION["userid"];
    $result = executeQuery($con, "SELECT * FROM users WHERE role_id = 1 LIMIT 1");
    $adminUser = mysqli_fetch_assoc($result);
    
    if (!$adminUser) {
        $adminUser = [
            'user_name' => $_SESSION["username"],
            'user_email' => 'admin@ebook.local',
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    $totalBooksResult = executeQuery($con, "SELECT COUNT(*) as count FROM books");
    $totalBooks = mysqli_fetch_assoc($totalBooksResult)['count'];
    
    $totalCategoriesResult = executeQuery($con, "SELECT COUNT(*) as count FROM categories");
    $totalCategories = mysqli_fetch_assoc($totalCategoriesResult)['count'];
    
    $totalUsersResult = executeQuery($con, "SELECT COUNT(*) as count FROM users");
    $totalUsers = mysqli_fetch_assoc($totalUsersResult)['count'];
    
    $totalOrdersResult = executeQuery($con, "SELECT COUNT(*) as count FROM orders");
    $totalOrders = mysqli_fetch_assoc($totalOrdersResult)['count'];
    
} catch (Exception $e) {
    error_log("Profile error: " . $e->getMessage());
    $adminUser = [
        'user_name' => $_SESSION["username"],
        'user_email' => 'admin@ebook.local'
    ];
    $totalBooks = 0;
    $totalCategories = 0;
    $totalUsers = 0;
    $totalOrders = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Profile">
    <meta name="author" content="">

    <title>Admin Profile - eBook Store</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "header.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- User -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hello, <?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest" ?></span>
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin Profile</h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-profile rounded-circle img-fluid px-4 mb-3" 
                                             src="assets/img/undraw_profile.svg" 
                                             style="width: 150px; height: 150px;">
                                        <h4 class="mb-1"><?php echo htmlspecialchars($adminUser['user_name'] ?? 'Administrator'); ?></h4>
                                        <p class="text-muted">Administrator</p>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-5 font-weight-bold">Email:</div>
                                        <div class="col-7"><?php echo htmlspecialchars($adminUser['user_email'] ?? 'admin@ebook.local'); ?></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-5 font-weight-bold">Member Since:</div>
                                        <div class="col-7"><?php echo date('F j, Y', strtotime($adminUser['created_at'] ?? 'now')); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-7">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Total Books</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalBooks; ?></div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card border-left-success shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                        Categories</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCategories; ?></div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-tags fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Total Users</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUsers; ?></div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card border-left-warning shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                        Total Orders</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalOrders; ?></div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Account Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Logged in to admin panel</td>
                                                    <td><?php echo date('F j, Y, g:i A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Viewed profile page</td>
                                                    <td><?php echo date('F j, Y, g:i A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Accessed dashboard</td>
                                                    <td><?php echo date('F j, Y', strtotime('-1 day')); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>

    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>