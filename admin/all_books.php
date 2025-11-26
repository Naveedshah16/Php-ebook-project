<?php
include("header.php");
$query = "SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id";
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

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book me-2"></i>All Books</h1>
        <div>
            <a href="add_book.php" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Add New Book</a>
            <a href="all_authors.php" class="btn btn-info"><i class="fas fa-user-edit me-2"></i>Manage Authors</a>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">See all books below</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Publish Year</th>
                            <th>Delivery Options</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Publish Year</th>
                            <th>Delivery Options</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php while($data = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td>
                                    <?php if(!empty($data['book_cover'])): ?>
                                        <img src="images/<?php echo $data['book_cover']; ?>" alt="<?php echo $data['book_title']; ?>" width="50">
                                    <?php else: ?>
                                        <i class="fas fa-book text-gray-300"></i>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $data['book_title']; ?></td>
                                <td><?php echo $data['book_author']; ?></td>
                                <td><?php echo $data['category_name'] ?? 'N/A'; ?></td>
                                <td>$<?php echo number_format($data['book_price'], 2); ?></td>
                                <td><?php echo $data['publish_year'] ?? 'N/A'; ?></td>
                                <td>
                                    <?php 
                                    $deliveryOptions = explode(',', $data['delivery_options']);
                                    foreach($deliveryOptions as $option):
                                        switch($option):
                                            case 'download':
                                                echo '<span class="badge badge-success">Download</span> ';
                                                break;
                                            case 'cd':
                                                echo '<span class="badge badge-info">CD</span> ';
                                                break;
                                            case 'hardcopy':
                                                echo '<span class="badge badge-warning">Hard Copy</span> ';
                                                break;
                                        endswitch;
                                    endforeach;
                                    ?>
                                </td>
                                <td>
                                    <a href="show_book.php?id=<?php echo $data['book_id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="edit_book.php?id=<?php echo $data['book_id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $data['book_id'] ?>">
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
    while($data = mysqli_fetch_assoc($result)) { 
    ?>
    <div class="modal fade" id="deleteModal<?php echo $data['book_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $data['book_id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $data['book_id'] ?>">
                        <i class="fas fa-exclamation-triangle"></i> Delete Book Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete book: <strong><?php echo $data['book_title'] ?></strong>?</p>
                    <p class="text-muted mb-0"><small>Author: <?php echo $data['book_author'] ?></small></p>
                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <a href="book_delete.php?id=<?php echo $data['book_id'] ?>" class="btn btn-danger">
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