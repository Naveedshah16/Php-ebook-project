<?php 
include("header.php");
$query = "SELECT m.*, u.user_name FROM messages m 
          LEFT JOIN users u ON m.user_id = u.user_id 
          ORDER BY m.created_at DESC";
$result = mysqli_query($con, $query);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-envelope me-2"></i>User Messages</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Messages</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($message = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $message['message_id']; ?></td>
                                <td><?php echo $message['user_name'] ?? 'N/A'; ?></td>
                                <td><?php echo $message['subject']; ?></td>
                                <td><?php echo substr($message['message'], 0, 50); ?><?php echo strlen($message['message']) > 50 ? '...' : ''; ?></td>
                                <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <?php if($message['is_read']): ?>
                                        <span class="badge badge-success">Read</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewMessageModal<?php echo $message['message_id']; ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <a href="mark_message.php?id=<?php echo $message['message_id']; ?>&action=<?php echo $message['is_read'] ? 'unread' : 'read'; ?>" class="btn btn-<?php echo $message['is_read'] ? 'secondary' : 'success'; ?> btn-sm">
                                        <i class="fas fa-<?php echo $message['is_read'] ? 'envelope' : 'envelope-open'; ?>"></i> 
                                        <?php echo $message['is_read'] ? 'Mark Unread' : 'Mark Read'; ?>
                                    </a>
                                    <a href="delete_message.php?id=<?php echo $message['message_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
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
while($message = mysqli_fetch_assoc($result)) { 
?>
<div class="modal fade" id="viewMessageModal<?php echo $message['message_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewMessageModalLabel<?php echo $message['message_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewMessageModalLabel<?php echo $message['message_id']; ?>">
                    <i class="fas fa-envelope me-2"></i>Message Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th>From:</th>
                        <td><?php echo $message['user_name'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Subject:</th>
                        <td><?php echo $message['subject']; ?></td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td><?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <th>Message:</th>
                        <td><?php echo nl2br($message['message']); ?></td>
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
<?php } ?>

<?php include("footer.php") ?>