<?php
include_once "config.php";
require_once __DIR__ . '/session_manager.php';
if(isset($_SESSION["username"]) && isset($_SESSION["userid"])){
    if($_SESSION["userid"] == 1){
        header("Location: admin/index.php");
        exit();
    }
    header("Location: index.php");
    exit();
}
if(isset($_POST["userlogin"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    if(empty($email) || empty($password)){
        redirectWithError("login.php", "Email and password are required");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        redirectWithError("login.php", "Invalid email format");
    }
    try {
        $result = executeQuery($con, "SELECT * FROM users WHERE user_email = ?", [$email]);
        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            $PasswordVerification = password_verify($password, $user["user_password"]);
            
            if($PasswordVerification){
                session_regenerate_id(true);
                
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["user_name"];
                $_SESSION["useremail"] = $user["user_email"];
                $_SESSION["userid"] = $user["role_id"];
                
                if($user["role_id"] == 1){
                    header("location: admin/index.php");
                    exit();
                } else {
                    header("location: index.php");
                    exit();
                }
            } else {
                redirectWithError("login.php", "Email or Password is Wrong");
            }
        } else {
            redirectWithError("login.php", "Account Not Found");
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        redirectWithError("login.php", "An error occurred. Please try again later.");
    }
}
?>
<?php include "header.php"; ?>
<div class="main-container">
    <div class="form-container">
        <div class="text-center mb-3">
            <i class="fas fa-book-open fa-3x" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h2 class="text-center fw-bold">Welcome Back!</h2>
        <p class="text-center text-muted mb-3">Sign in to continue to your account</p>
        <hr>
        <?php if(isset($_GET["error"]) && isset($_GET["errorType"])) {?>
        <div class="alert alert-<?php echo escapeHTML($_GET["errorType"])?> alert-dismissible fade show" role="alert">
        <strong><?php echo escapeHTML($_GET["error"])?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"aria-label="Close"></button>
        </div>
        <?php }?>
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="form-group mt-3">
                <label for="" class="form-label"><i class="fas fa-envelope me-2"></i>Email:</label>
                <input type="email" class="form-control" placeholder="Enter your email" name="email" required>
                <div class="invalid-feedback">
                    Please enter a valid email.
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label"><i class="fas fa-lock me-2"></i>Password:</label>
                <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>
            <div class="form-group mt-4">
                <input type="submit" value="Login" name="userlogin" class="btn btn-primary w-100">
            </div>
            <div class="form-group mt-3 text-center">
                <p class="mb-0">Don't have an account? <a href="register.php">Create one</a></p>
            </div>
        </form>
    </div>
</div>
<script>
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
<?php include "footer.php"; ?>