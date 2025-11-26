<?php 
include("header.php");
$booksQuery = "SELECT COUNT(*) as total FROM books";
$booksResult = mysqli_query($con, $booksQuery);
$booksCount = mysqli_fetch_assoc($booksResult)['total'];

$usersQuery = "SELECT COUNT(*) as total FROM users";
$usersResult = mysqli_query($con, $usersQuery);
$usersCount = mysqli_fetch_assoc($usersResult)['total'];

$authorsQuery = "SELECT COUNT(*) as total FROM authors";
$authorsResult = mysqli_query($con, $authorsQuery);
$authorsCount = mysqli_fetch_assoc($authorsResult)['total'];

$categoriesQuery = "SELECT COUNT(*) as total FROM categories";
$categoriesResult = mysqli_query($con, $categoriesQuery);
$categoriesCount = mysqli_fetch_assoc($categoriesResult)['total'];
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book-reader me-3"></i>eBook Store Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 me-2"></i> Generate Report
        </a>
    </div>
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $booksCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $usersCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Authors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $authorsCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $categoriesCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-area me-2"></i>Book Downloads Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Period:</div>
                            <a class="dropdown-item" href="#">Last Week</a>
                            <a class="dropdown-item" href="#">Last Month</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Popular Genres</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Category:</div>
                            <a class="dropdown-item" href="#">All Genres</a>
                            <a class="dropdown-item" href="#">Fiction</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Non-Fiction</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Fiction
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Mystery
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Romance
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fire me-2"></i>Trending Books</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold">"The Digital Mind" <span
                            class="float-right">95%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 95%"
                            aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h4 class="small font-weight-bold">"Mystery of the Lost Key" <span
                            class="float-right">80%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h4 class="small font-weight-bold">"Romance in Paris" <span
                            class="float-right">70%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: 70%"
                            aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h4 class="small font-weight-bold">"The Science of Success" <span
                            class="float-right">60%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"
                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h4 class="small font-weight-bold">"History of Ancient Civilizations" <span
                            class="float-right">50%</span></h4>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 50%"
                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="add_book.php" class="btn btn-primary btn-block">
                                <i class="fas fa-plus-circle me-2"></i>Add Book
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="add_category.php" class="btn btn-success btn-block">
                                <i class="fas fa-tags me-2"></i>Add Category
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="add_author.php" class="btn btn-info btn-block">
                                <i class="fas fa-user-edit me-2"></i>Add Author
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="all_users.php" class="btn btn-warning btn-block">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>Recently Added Books</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>The Digital Mind</td>
                                    <td>Alex Johnson</td>
                                    <td>Technology</td>
                                </tr>
                                <tr>
                                    <td>Mystery of the Lost Key</td>
                                    <td>Sarah Williams</td>
                                    <td>Mystery</td>
                                </tr>
                                <tr>
                                    <td>Romance in Paris</td>
                                    <td>Emily Roberts</td>
                                    <td>Romance</td>
                                </tr>
                                <tr>
                                    <td>The Science of Success</td>
                                    <td>Michael Chen</td>
                                    <td>Self-Help</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-edit me-2"></i>Popular Authors</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Author</th>
                                    <th>Books Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>J.K. Rowling</td>
                                    <td>12</td>
                                </tr>
                                <tr>
                                    <td>Stephen King</td>
                                    <td>8</td>
                                </tr>
                                <tr>
                                    <td>Agatha Christie</td>
                                    <td>7</td>
                                </tr>
                                <tr>
                                    <td>George R.R. Martin</td>
                                    <td>5</td>
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
<?php include("footer.php") ?>