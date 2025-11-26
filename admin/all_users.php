<?php
include("header.php");
$result = mysqli_query($con, "SELECT * FROM `users` INNER JOIN roles on users.role_id = roles.role_id;");

?>
<style>
.modal-backdrop {
    z-index: 1040 !important;
}
.modal {
    z-index: 1050 !important;
}
</style>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">All Categories</h1>
    <a href="add_users.php" class="btn btn-primary my-2">Add Users</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">See all users below</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User Id</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Role</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>User_Id</th>
                            <th>User_Name</th>
                            <th>User_Email</th>
                            <th>User Role</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($result as $data) { ?>
                            <tr>
                                <td><?php echo $data['user_id'] ?></td>
                                <td><?php echo $data['user_name'] ?></td>
                                <td><?php echo $data['user_email'] ?></td>
                                <td><?php echo $data['role_name'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#roleModal<?php echo $data['user_id'] ?>">
                                        <i class="fas fa-user-tag"></i> Role Change
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $data['user_id'] ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php 
    mysqli_data_seek($result, 0);
    foreach ($result as $data) { 
    ?>
    <div class="modal fade" id="roleModal<?php echo $data['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel<?php echo $data['user_id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="change_role.php" method="POST">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="roleModalLabel<?php echo $data['user_id'] ?>">
                            <i class="fas fa-user-tag"></i> Change User Role
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $data['user_id'] ?>">
                        <p><strong>User:</strong> <?php echo $data['user_name'] ?> (<?php echo $data['user_email'] ?>)</p>
                        <p><strong>Current Role:</strong> <?php echo $data['role_name'] ?></p>
                        <hr>
                        <div class="form-group">
                            <label for="role">Select New Role:</label>
                            <select name="role" class="form-control" required>
                                <option value="" disabled selected>-- Select Role --</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php 
    mysqli_data_seek($result, 0);
    foreach ($result as $data) { 
    ?>
    <div class="modal fade" id="deleteModal<?php echo $data['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $data['user_id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $data['user_id'] ?>">
                        <i class="fas fa-exclamation-triangle"></i> Delete User Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete user: <strong><?php echo $data['user_name'] ?></strong>?</p>
                    <p class="text-muted mb-0"><small>Email: <?php echo $data['user_email'] ?></small></p>
                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <a href="user_delete.php?id=<?php echo $data['user_id'] ?>" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Confirm Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

</div>
</div>
<?php include("footer.php") ?>