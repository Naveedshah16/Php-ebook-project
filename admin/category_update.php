<?php
include("header.php");

$idToBeUpdated = $_GET["id"];

$query = "SELECT * from categories where category_id = $idToBeUpdated";
$result = mysqli_query($con, $query);
$resultSet = mysqli_fetch_assoc($result);

if (isset($_POST["updatecategory"])) {
    $updateCategoryName = $_POST["updatecategoryname"];
    $res = mysqli_query($con, "UPDATE `categories` SET `category_name`='$updateCategoryName' WHERE category_id = $idToBeUpdated");

    if ($res) {
        echo "<script>window.location.href='all_categories.php'</script>";
    } else {
        echo "<script>alert('Somthing went wrong')</script>";
    }
}

?>
<section class="p-5">
    <h1 class="h3 mb-3 text-gray-800">Update Category</h1>

    <div class="card position-relative">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Update Category Below</h6>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label for="" class="form-label">Category Name</label>
                    <input type="text" class="form-control" placeholder="Enter your category" required name="updatecategoryname" value="<?php echo $resultSet["category_name"]; ?>">
                </div>
                <div class="form-group mt-3">
                    <input type="submit" value="Update Category" class="btn btn-primary" name="updatecategory">
                </div>
            </form>
        </div>
    </div>
</section>
<?php include("footer.php") ?>