<?php 
require_once __DIR__ . '/session_manager.php';
include "config.php";

$name = $email = $subject = $messageText = "";
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $messageText = trim($_POST["message"] ?? "");
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }
    
    if (empty($messageText)) {
        $errors[] = "Message is required.";
    }
    
    if (empty($errors)) {
        try {
              $userId = null;
            if (isset($_SESSION['userid'])) {
                $checkUserSql = "SELECT user_id FROM users WHERE user_id = ?";
                $checkUserStmt = mysqli_prepare($con, $checkUserSql);
                if ($checkUserStmt) {
                    mysqli_stmt_bind_param($checkUserStmt, "i", $_SESSION['userid']);
                    if (mysqli_stmt_execute($checkUserStmt)) {
                        $userResult = mysqli_stmt_get_result($checkUserStmt);
                        if (mysqli_num_rows($userResult) > 0) {
                            $userId = (int)$_SESSION['userid'];
                        }
                    }
                    mysqli_stmt_close($checkUserStmt);
                }
            }
            if ($userId !== null) {
                $sql = "INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "issss", $userId, $name, $email, $subject, $messageText);
                }
            } else {
                $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $messageText);
                }
            }
            
            if ($stmt) {
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                    $name = $email = $subject = $messageText = "";
                } else {
                    $errors[] = "Error saving message: " . mysqli_stmt_error($stmt);
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = "Error preparing statement: " . mysqli_error($con);
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Contact eBook Store"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<title>Contact - eBook Store</title>
<style>
    .contact-hero {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%), 
                    url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=1600&auto=format&fit=crop') no-repeat center/cover;
        background-attachment: fixed;
        padding: 4rem 0;
        margin-bottom: 3rem;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .contact-glass-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px) saturate(180%);
        -webkit-backdrop-filter: blur(10px) saturate(180%);
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        z-index: 1;
    }
    
    .contact-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        animation: fadeInUp 1s ease-out;
    }
    
    .contact-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .contact-hero p {
        font-size: 1.25rem;
        max-width: 700px;
        margin: 0 auto 2rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .contact-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 3rem;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .contact-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        border-radius: 20px 0 0 20px;
    }
    
    .contact-info h3 {
        font-weight: 700;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .contact-info h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 3px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
    }
    
    .contact-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        font-size: 1.2rem;
    }
    
    .contact-details h5 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .contact-details p, .contact-details a {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .contact-details a:hover {
        color: #ffc107;
        text-decoration: underline;
    }
    
    .contact-form {
        padding: 3rem;
    }
    
    .form-title {
        font-weight: 700;
        margin-bottom: 2rem;
        color: #333;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .form-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }
    
    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .btn-contact {
        padding: 12px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-contact:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 2rem;
    }
    
    .social-link {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .social-link:hover {
        background: #ffc107;
        transform: translateY(-3px);
    }
    
    @media (max-width: 992px) {
        .contact-info {
            border-radius: 20px 20px 0 0;
        }
        
        .contact-hero h1 {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .contact-hero {
            padding: 3rem 0;
        }
        
        .contact-hero h1 {
            font-size: 2rem;
        }
        
        .contact-hero p {
            font-size: 1rem;
        }
        
        .contact-info, .contact-form {
            padding: 2rem;
        }
    }
</style>
</head>
<body>
    <?php include "header.php"; ?>

    <section class="contact-hero">
        <div class="contact-glass-overlay"></div>
        <div class="container contact-hero-content">
            <h1><i class="fas fa-envelope me-3"></i>Get In Touch</h1>
            <p class="lead">Have questions or feedback? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </section>

    <div class="container contact-container">
        <div class="contact-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Our Location</h5>
                                <p>123 Book Street, Library City</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Phone Number</h5>
                                <p>+92 (333) 123-4567</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email Address</h5>
                                <p><a href="mailto:naveed16@gmail.com">naveed16@gmail.com</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Working Hours</h5>
                                <p>Monday - Friday: 9AM - 6PM</p>
                                <p>Saturday: 10AM - 4PM</p>
                            </div>
                        </div>
                        
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form">
                        <h3 class="form-title">Send Us a Message</h3>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> Your message has been sent successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Please correct the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form class="needs-validation" novalidate method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required placeholder="Enter your full name">
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email"  class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="Enter your email address">
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required placeholder="Enter message subject">
                                <div class="invalid-feedback">Please enter a subject.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Your Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="message" rows="5" required placeholder="Type your message here..."><?php echo htmlspecialchars($messageText); ?></textarea>
                                <div class="invalid-feedback">Please enter your message.</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-contact">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

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
</body>
</html>