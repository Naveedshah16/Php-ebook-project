<?php include("header.php");
    if(isset($_POST["addcategory"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        $categoryName = $_POST["categoryname"];
        $query = "INSERT INTO `categories`(`category_name`) VALUES ('$categoryName')";
        $result = mysqli_query($con, $query);

        if($result){
            echo "<script>window.location.href = 'all_categories.php'</script>";
        }
        else{
            echo "<script>alert('Somthing Went Wrong')</script>";
        }

    }
?>
<section class="p-5">
    <h1 class="h3 mb-3 text-gray-800">Add Category</h1>

    <div class="card position-relative">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Category Below</h6>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label for="" class="form-label">Category Name</label>
                    <input type="text" class="form-control" placeholder="Enter your category" required name="categoryname">
                </div>
                <div class="form-group mt-3">
                    <input type="submit" value="Add Category" class="btn btn-primary" name="addcategory">
                </div>
            </form>
        </div>
    </div>
</section>
<?php include("footer.php") ?>