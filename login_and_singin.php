<?php
session_start();

$login_msg = "";
$signup_msg = "";

// Connect to database
$conn = new mysqli("localhost", "root", "", "user_auth");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------------- SIGNUP ----------------
if (isset($_POST['signup'])) {
    $email = $_POST['signup_email'];
    $phone = $_POST['signup_phone'];
    $password = $_POST['signup_password'];
    $confirm_password = $_POST['signup_confirm_password'];

    if($password != $confirm_password){
        $signup_msg = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $conn->query("INSERT INTO users (email, phone, password) VALUES ('$email', '$phone', '$hashed_password')");
        $signup_msg = "Registration successful! You can login now.";
    }
}

// ---------------- LOGIN ----------------
if (isset($_POST['login'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    // Fetch user from database
    $result = $conn->query("SELECT * FROM users WHERE email='$username' OR phone='$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to index.html after login
            header("Location: index.php");
            exit();
        } else {
            $login_msg = "Incorrect password!";
        }
    }
}
$conn->close();
?>





<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitFlex Gym | Login & Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>

    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <i class="fas fa-dumbbell"></i>
        <h1>PowerFit</h1>
    </div>
    
    <div class="card">
        <div class="tabs">
            <div class="tab active" id="login-tab">LOGIN</div>
            <div class="tab" id="signup-tab">SIGN UP</div>
        </div>
        
        <!-- Login Form Container -->
        <div class="login-form-container" id="login-container">
            <form class="form" id="login-form" method="post" action="">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="login_username" placeholder="Username or Email" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="login_password" placeholder="Password" required>
                </div>
                
                <div class="options">
                    <div class="remember">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot">Forgot Password?</a>
                </div>
                
                <button type="submit" name="login" class="btn">LOGIN</button>
                <p style="color:red;"><?php echo $login_msg; ?></p>
                
                <div class="social-login">
                    <p>Or login with</p>
                    <div class="social-buttons">
                        <button class="social-btn google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                </div>
            </form>
        </div>
    
        <!-- Sign Up Form Container -->
        <div class="signup-form-container" id="signup-container">
            <form class="form" id="signup-form" method="post" action="">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="signup_email" placeholder="Email Address" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="tel" name="signup_phone" placeholder="Phone Number" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="signup_password" placeholder="Password" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="signup_confirm_password" placeholder="Confirm Password" required>
                </div>
                
                <button type="submit" name="signup" class="btn">SIGN UP</button>
                <p style="color:green;"><?php echo $signup_msg; ?></p>
            
                <div class="social-login">
                    <p>Or sign up with</p>
                    <div class="social-buttons">
                        <button class="social-btn google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div class="floating-elements">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginTab = document.getElementById('login-tab');
            const signupTab = document.getElementById('signup-tab');
            const loginContainer = document.getElementById('login-container');
            const signupContainer = document.getElementById('signup-container');
            
            loginTab.addEventListener('click', function() {
                loginTab.classList.add('active');
                signupTab.classList.remove('active');
                loginContainer.style.display = 'block';
                signupContainer.style.display = 'none';
            });
            
            signupTab.addEventListener('click', function() {
                signupTab.classList.add('active');
                loginTab.classList.remove('active');
                loginContainer.style.display = 'none';
                signupContainer.style.display = 'block';
            });
            
            
            // Additional buttons
            const additionalButtons = document.querySelectorAll('.additional-btn');
            additionalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.backgroundColor = 'rgba(255, 107, 53, 0.2)';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                        alert(`Navigating to: ${this.textContent}`);
                    }, 300);
                });
            });
        });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // <- This stops PHP from receiving POST data
        alert('Form submitted successfully!');
    });

    </script>
</body>
</html>