<?php 
include("header.php");
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: all_authors.php");
    exit();
}

$authorId = intval($_GET['id']);
$authorQuery = "SELECT * FROM authors WHERE author_id = $authorId";
$authorResult = mysqli_query($con, $authorQuery);

if (mysqli_num_rows($authorResult) == 0) {
    header("Location: all_authors.php");
    exit();
}

$author = mysqli_fetch_assoc($authorResult);
if (isset($_POST["updateauthor"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $authorName = trim($_POST["authorname"]);
    $authorBio = trim($_POST["authorbio"]);
    
    if (!empty($authorName)) {
        $checkQuery = "SELECT * FROM authors WHERE author_name = '$authorName' AND author_id != $authorId";
        $checkResult = mysqli_query($con, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('Another author already exists with this name!');</script>";
        } else {
            $query = "UPDATE `authors` SET `author_name`='$authorName', `author_bio`='$authorBio' WHERE `author_id`='$authorId'";
            
            if (mysqli_query($con, $query)) {
                echo "<script>
                    alert('Author Updated Successfully!');
                    window.location.href = 'all_authors.php';
                </script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Author name is required!');</script>";
    }
}
?>

<section class="p-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-edit me-3"></i>Edit Author</h1>
        <a href="all_authors.php" class="btn btn-secondary"><i class="fas fa-list me-2"></i>View All Authors</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit me-2"></i>Edit Author Details Below</h6>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="authorname" class="form-label"><i class="fas fa-user me-2"></i>Author Name *</label>
                    <input type="text" class="form-control" id="authorname" placeholder="Enter author name" required name="authorname" value="<?php echo htmlspecialchars($author['author_name']); ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="authorbio" class="form-label"><i class="fas fa-align-left me-2"></i>Author Bio</label>
                    <textarea class="form-control" id="authorbio" rows="4" placeholder="Enter author biography" name="authorbio"><?php echo htmlspecialchars($author['author_bio']); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" name="updateauthor">
                        <i class="fas fa-save me-2"></i>Update Author
                    </button>
                    <a href="all_authors.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include("footer.php") ?>