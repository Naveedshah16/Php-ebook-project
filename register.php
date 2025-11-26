<?php
include_once "config.php";
require_once __DIR__ . '/session_manager.php';

if(isset($_POST["addUser"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $userName = trim($_POST["Name"] ?? '');
    $userEmail = trim($_POST["Email"] ?? '');
    $userPassword = $_POST["Password"] ?? '';
    $userConPassword = $_POST["ConPassword"] ?? '';
    
    // Validate inputs
    if(empty($userName) || empty($userEmail) || empty($userPassword) || empty($userConPassword)){
        redirectWithError("register.php", "All fields are required");
    }
    
    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
        redirectWithError("register.php", "Invalid email format");
    }
    
    if(strlen($userPassword) < 6){
        redirectWithError("register.php", "Password must be at least 6 characters");
    }
    
    if($userPassword != $userConPassword){
        redirectWithError("register.php", "Password and Confirm Password don't match");
    }
    
    try {
        // Check if email already exists using prepared statement
        $result = executeQuery($con, "SELECT user_id FROM users WHERE user_email = ?", [$userEmail]);
        
        if(mysqli_num_rows($result) > 0){
            redirectWithError("register.php", "Email Already Exists");
        }
        
        $encryptedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
        executeQuery($con, "INSERT INTO users (user_name, user_email, user_password, role_id) VALUES (?, ?, ?, 2)", 
              [$userName, $userEmail, $encryptedPassword]);
        
        redirectWithSuccess("login.php", "Registration successful! Please login.");
        
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        redirectWithError("register.php", "Something went wrong. Please try again.");
    }
}
?>
<?php include "header.php"; ?>
<style>
.register-wrapper {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}

.register-card {
    max-height: 90vh;
    overflow-y: auto;
}

.register-card::-webkit-scrollbar {
    display: none;
}

.register-card {
    -ms-overflow-style: none;  
    scrollbar-width: none; 
}

@supports (scrollbar-width: thin) {
    .register-card {
        scrollbar-width: thin;
        scrollbar-color: #667eea #f8f9fa;
    }
    
    .register-card::-webkit-scrollbar {
        display: block;
        width: 8px;
    }
    
    .register-card::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .register-card::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }
    
    .register-card::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }
}
</style>

<div class="container-fluid register-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="form-container register-card bg-white p-4 p-md-5 rounded shadow">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <h2 class="fw-bold">Create Account</h2>
                        <p class="text-muted">Join us and start your reading journey</p>
                    </div>
                    <hr class="mb-4">
                    <?php if(isset($_GET["error"]) && isset($_GET["errorType"])) {?>
                    <div class="alert alert-<?php echo escapeHTML($_GET["errorType"])?> alert-dismissible fade show" role="alert">
                    <strong><?php echo escapeHTML($_GET["error"])?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php }?>
                    
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="form-group mb-3">
                            <label for="Name" class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                            <input type="text" class="form-control form-control-lg" id="Name" placeholder="Enter your full name" name="Name" required>
                            <div class="invalid-feedback">
                                Please enter your full name.
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" class="form-control form-control-lg" id="Email" placeholder="Enter your email" name="Email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email.
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                            <input type="password" class="form-control form-control-lg" id="Password" placeholder="Enter your password" name="Password" required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="ConPassword" class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                            <input type="password" class="form-control form-control-lg" id="ConPassword" placeholder="Confirm your password" name="ConPassword" required>
                            <div class="invalid-feedback">
                                Please confirm your password.
                            </div>  
                        </div>
                        <div class="form-group mb-4">
                            <input type="submit" value="Create Account" class="btn btn-primary btn-lg w-100" name="addUser">
                        </div>
                        <div class="form-group text-center">
                            <p class="mb-0">Already have an account? <a href="login.php">Sign in</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
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