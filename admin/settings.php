<?php 
include("header.php");
$bannerColCheck = mysqli_query($con, "SHOW COLUMNS FROM settings LIKE 'banner_path'");
if ($bannerColCheck && mysqli_num_rows($bannerColCheck) === 0) {
    mysqli_query($con, "ALTER TABLE settings ADD COLUMN banner_path VARCHAR(255) NULL AFTER logo_path");
}
$squareImageColCheck = mysqli_query($con, "SHOW COLUMNS FROM settings LIKE 'square_image_path'");
if ($squareImageColCheck && mysqli_num_rows($squareImageColCheck) === 0) {
    mysqli_query($con, "ALTER TABLE settings ADD COLUMN square_image_path VARCHAR(255) NULL AFTER banner_path");
}
$settingsQuery = "SELECT * FROM settings LIMIT 1";
$settingsResult = mysqli_query($con, $settingsQuery);
$settings = mysqli_fetch_assoc($settingsResult);
if (isset($_POST["updateSettings"])) {
    $siteName = $_POST["site_name"];
    $logoPath = $settings['logo_path'];
    if (!empty($_FILES["site_logo"]["name"])) {
        $logoName = $_FILES["site_logo"]["name"];
        $logoTmp = $_FILES["site_logo"]["tmp_name"];
        $logoExtension = strtolower(pathinfo($logoName, PATHINFO_EXTENSION));
        $allowedExt = ["png", "jpg", "jpeg", "gif", "svg"];
        
        if (in_array($logoExtension, $allowedExt)) {
            $newLogoName = "logo." . $logoExtension;
            $newLogoPath = "assets/img/" . $newLogoName;
            
            if (move_uploaded_file($logoTmp, $newLogoPath)) {
                $logoPath = $newLogoPath;
            } else {
                echo "<script>alert('Failed to upload logo');</script>";
            }
        } else {
            echo "<script>alert('Invalid logo format. Only PNG, JPG, JPEG, GIF, SVG allowed.');</script>";
        }
    }
    $bannerPath = isset($settings['banner_path']) ? $settings['banner_path'] : '';
    if (!empty($_FILES["site_banner"]["name"])) {
        $bannerName = $_FILES["site_banner"]["name"];
        $bannerTmp = $_FILES["site_banner"]["tmp_name"];
        $bannerExtension = strtolower(pathinfo($bannerName, PATHINFO_EXTENSION));
        $allowedExt = ["png", "jpg", "jpeg", "gif", "webp"];
        if (in_array($bannerExtension, $allowedExt)) {
            $newBannerName = "banner." . $bannerExtension;
            $newBannerPath = "assets/img/" . $newBannerName;
            if (move_uploaded_file($bannerTmp, $newBannerPath)) {
                $bannerPath = $newBannerPath;
            } else {
                echo "<script>alert('Failed to upload banner');</script>";
            }
        } else {
            echo "<script>alert('Invalid banner format. Only PNG, JPG, JPEG, GIF, WEBP allowed.');</script>";
        }
    }
    $squareImagePath = isset($settings['square_image_path']) ? $settings['square_image_path'] : '';
    if (!empty($_FILES["square_image"]["name"])) {
        $squareImageName = $_FILES["square_image"]["name"];
        $squareImageTmp = $_FILES["square_image"]["tmp_name"];
        $squareImageExtension = strtolower(pathinfo($squareImageName, PATHINFO_EXTENSION));
        $allowedExt = ["png", "jpg", "jpeg", "gif", "webp", "svg"];
        if (in_array($squareImageExtension, $allowedExt)) {
            $newSquareImageName = "square_image." . $squareImageExtension;
            $newSquareImagePath = "assets/img/" . $newSquareImageName;
            if (move_uploaded_file($squareImageTmp, $newSquareImagePath)) {
                $squareImagePath = $newSquareImagePath;
            } else {
                echo "<script>alert('Failed to upload square image');</script>";
            }
        } else {
            echo "<script>alert('Invalid square image format. Only PNG, JPG, JPEG, GIF, WEBP, SVG allowed.');</script>";
        }
    }
    $updateQuery = "UPDATE settings SET site_name = '$siteName', logo_path = '$logoPath', banner_path = '$bannerPath', square_image_path = '$squareImagePath' WHERE setting_id = " . $settings['setting_id'];
    
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>
            alert('Settings updated successfully!');
            window.location.href = 'settings.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating settings: " . mysqli_error($con) . "');
        </script>";
    }
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cogs me-2"></i>Site Settings</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configure Site Settings</h6>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="siteName" class="form-label"><i class="fas fa-heading me-2"></i>Site Name</label>
                    <input type="text" class="form-control" id="siteName" name="site_name" value="<?php echo $settings['site_name']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="siteLogo" class="form-label"><i class="fas fa-image me-2"></i>Site Logo</label>
                    <input type="file" class="form-control" id="siteLogo" name="site_logo" accept="image/*">
                    <?php if(!empty($settings['logo_path'])): ?>
                        <div class="mt-2">
                            <small class="form-text text-muted">Current logo:</small>
                            <img src="<?php echo $settings['logo_path']; ?>" alt="Site Logo" width="100">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="siteBanner" class="form-label"><i class="fas fa-image me-2"></i>Homepage Banner Image</label>
                    <input type="file" class="form-control" id="siteBanner" name="site_banner" accept="image/*">
                    <?php if(!empty($settings['banner_path'])): ?>
                        <div class="mt-2">
                            <small class="form-text text-muted">Current banner:</small>
                            <img src="<?php echo $settings['banner_path']; ?>" alt="Site Banner" style="max-width: 100%; height: auto;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="squareImage" class="form-label"><i class="fas fa-image me-2"></i>Square Image (for banner overlay)</label>
                    <input type="file" class="form-control" id="squareImage" name="square_image" accept="image/*">
                    <?php if(!empty($settings['square_image_path'])): ?>
                        <div class="mt-2">
                            <small class="form-text text-muted">Current square image:</small>
                            <img src="<?php echo $settings['square_image_path']; ?>" alt="Square Image" width="100" height="100" style="object-fit: cover;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" name="updateSettings">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                    <a href="index.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview</h6>
        </div>
        <div class="card-body">
            <div class="preview-container p-4 border rounded">
                <h4>How your site will look with these settings:</h4>
                <div class="mt-3">
                    <h5>Site Name: <span class="text-primary"><?php echo $settings['site_name']; ?></span></h5>
                    <?php if(!empty($settings['logo_path'])): ?>
                        <div class="mt-2">
                            <p>Logo:</p>
                            <img src="<?php echo $settings['logo_path']; ?>" alt="Site Logo" width="150">
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($settings['banner_path'])): ?>
                        <div class="mt-3">
                            <p>Banner:</p>
                            <img src="<?php echo $settings['banner_path']; ?>" alt="Site Banner" style="max-width: 100%; height: auto;">
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($settings['square_image_path'])): ?>
                        <div class="mt-3">
                            <p>Square Image:</p>
                            <img src="<?php echo $settings['square_image_path']; ?>" alt="Square Image" width="100" height="100" style="object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <p>Theme Color Preview:</p>
                        <div style="width: 100px; height: 30px; background-color: <?php echo $settings['theme_color']; ?>; border: 1px solid #ddd;"></div>
                        <p class="mt-1">Color Code: <code><?php echo $settings['theme_color']; ?></code></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php") ?>