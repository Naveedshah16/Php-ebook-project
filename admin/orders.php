<?php 
include("header.php");
$query = "SELECT o.*, u.user_name, b.book_title FROM orders o 
          LEFT JOIN users u ON o.user_id = u.user_id 
          LEFT JOIN books b ON o.book_id = b.book_id 
          ORDER BY o.order_date DESC";
$result = mysqli_query($con, $query);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-shopping-cart me-2"></i>Orders Management</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 me-2"></i> Generate Report
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Orders</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Book Title</th>
                            <th>Order Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $order['order_id']; ?></td>
                                <td><?php echo $order['user_name'] ?? 'N/A'; ?></td>
                                <td><?php echo $order['book_title'] ?? 'N/A'; ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo $order['payment_method']; ?></td>
                                <td>
                                    <?php 
                                    $status = $order['order_status'];
                                    $badgeClass = '';
                                    switch($status) {
                                        case 'Pending':
                                            $badgeClass = 'badge-warning';
                                            break;
                                        case 'Completed':
                                            $badgeClass = 'badge-success';
                                            break;
                                        case 'Cancelled':
                                            $badgeClass = 'badge-danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php 
mysqli_data_seek($result, 0);
while($order = mysqli_fetch_assoc($result)) { 
?>
<div class="modal fade" id="viewOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewOrderModalLabel<?php echo $order['order_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewOrderModalLabel<?php echo $order['order_id']; ?>">
                    <i class="fas fa-receipt me-2"></i>Order Details #<?php echo $order['order_id']; ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Order ID:</th>
                        <td><?php echo $order['order_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td><?php echo $order['user_name'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Book:</th>
                        <td><?php echo $order['book_title'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Order Date:</th>
                        <td><?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td><?php echo $order['payment_method']; ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php 
                            $status = $order['order_status'];
                            $badgeClass = '';
                            switch($status) {
                                case 'Pending':
                                    $badgeClass = 'badge-warning';
                                    break;
                                case 'Completed':
                                    $badgeClass = 'badge-success';
                                    break;
                                case 'Cancelled':
                                    $badgeClass = 'badge-danger';
                                    break;
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel<?php echo $order['order_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="update_order.php" method="POST">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editOrderModalLabel<?php echo $order['order_id']; ?>">
                        <i class="fas fa-edit me-2"></i>Edit Order #<?php echo $order['order_id']; ?>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <div class="form-group">
                        <label for="orderStatus">Order Status</label>
                        <select class="form-control" id="orderStatus" name="order_status">
                            <option value="Pending" <?php echo ($order['order_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Completed" <?php echo ($order['order_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo ($order['order_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <input type="text" class="form-control" id="paymentMethod" name="payment_method" value="<?php echo $order['payment_method']; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<?php include("footer.php") ?>