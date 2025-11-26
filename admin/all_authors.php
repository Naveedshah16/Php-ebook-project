<?php 
include("header.php");

$query = "SELECT * FROM authors ORDER BY author_name";
$result = mysqli_query($con, $query);
?>

<section class="p-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-edit me-3"></i>All Authors</h1>
        <a href="add_author.php" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Add New Author</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Authors List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Bio</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($author = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $author['author_id']; ?></td>
                                    <td><?php echo $author['author_name']; ?></td>
                                    <td><?php echo substr($author['author_bio'], 0, 100); ?><?php echo strlen($author['author_bio']) > 100 ? '...' : ''; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($author['created_at'])); ?></td>
                                    <td>
                                        <a href="edit_author.php?id=<?php echo $author['author_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="author_delete.php?id=<?php echo $author['author_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this author? This will affect all books by this author.')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No authors found. <a href="add_author.php">Add the first author</a>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php") ?>