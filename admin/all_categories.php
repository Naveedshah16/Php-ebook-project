<?php
include("header.php");

$query = "SELECT * from categories";
$result = mysqli_query($con, $query);
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
    <a href="add_category.php" class="btn btn-primary my-2">Add Category</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">See all Categories below</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category_id</th>
                            <th>Category_name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Category_id</th>
                            <th>Category_name</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($result as $products) { ?>
                            <tr>
                                <td><?php echo $products["category_id"] ?></td>
                                <td><?php echo $products["category_name"] ?></td>
                                <td>
                                    <a href="category_update.php?id=<?php echo $products["category_id"] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $products["category_id"] ?>">
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
    foreach ($result as $products) { 
    ?>
    <div class="modal fade" id="deleteModal<?php echo $products["category_id"] ?>" tabindex="-1" role="dialog"
     aria-labelledby="deleteModalLabel<?php echo $products["category_id"] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $products["category_id"] ?>">
                        <i class="fas fa-exclamation-triangle"></i> Delete Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete category: <strong><?php echo $products["category_name"] ?></strong>?</p>
                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <a href="category_delete.php?id=<?php echo $products["category_id"] ?>" class="btn btn-danger">
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