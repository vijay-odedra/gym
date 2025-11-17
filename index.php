<?php
// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "user_auth";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session and check if user is logged in
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_email']);
$userInitial = '';
$userEmail = '';
if ($isLoggedIn) {
    $userEmail = $_SESSION['user_email'];
    $userInitial = strtoupper(substr($userEmail, 0, 1));
}

// When form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $full_name      = $_POST['full-name'];
    $email          = $_POST['email'];
    $phone          = $_POST['phone'];
    $membership_plan = $_POST['plan'];
    $age            = $_POST['age'];
    $gender         = $_POST['gender'];
    $fitness_goals  = $_POST['goals'];

    // Payment method (credit card, debit card, upi, net-banking)
    $payment_method = $_POST['payment-method'];

    // Insert query
    $sql = "INSERT INTO membership_form 
            (full_name, email, phone, membership_plan, age, gender, fitness_goals, payment)
            VALUES 
            ('$full_name', '$email', '$phone', '$membership_plan', '$age', '$gender', '$fitness_goals', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        // Show popup using PHP echo
        echo "<script>
                alert('Membership Registered Successfully! Welcome to PowerFit Gym!');
                window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerFit Gym - Train Hard, Stay Strong</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="index.css">
    <style>
        /* Login Button Styles */
        .login-btn {
            background: #ff6b35;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .login-btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }

        /* User Profile Styles */
        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ff6b35;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .user-avatar:hover {
            background: #e55a2b;
            transform: scale(1.05);
            border-color: #fff;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            padding: 10px 0;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 15px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid white;
        }

        .user-info {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 5px;
        }

        .user-email {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            word-break: break-all;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: #ff6b35;
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background: #eee;
            margin: 5px 0;
        }

        /* Mobile Three Dot Menu */
        .user-menu-mobile {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .user-menu-mobile:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .user-profile {
                margin-left: auto;
            }

            .user-avatar {
                display: none;
            }

            .user-menu-mobile {
                display: block;
            }

            .user-dropdown {
                position: fixed;
                top: 70px;
                right: 15px;
                left: 15px;
                width: auto;
                z-index: 1001;
            }

            .user-dropdown::before {
                right: 20px;
            }

            nav.active .user-avatar {
                display: flex;
                margin: 10px 0;
            }

            nav.active .user-menu-mobile {
                display: none;
            }
        }

        /* Desktop Styles */
        @media (min-width: 769px) {
            .user-menu-mobile {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="#" class="logo">Power<span>Fit</span></a>
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <nav>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#membership">Membership</a></li>
                    <li><a href="#trainers">Trainers</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li class="user-profile">
                            <!-- Desktop Avatar -->
                            <div class="user-avatar" id="userAvatar">
                                <?php echo $userInitial; ?>
                            </div>
                            <!-- Mobile Three Dot Menu -->
                            <button class="user-menu-mobile" id="userMenuMobile">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-info">
                                    <div class="user-name">Welcome!</div>
                                    <div class="user-email"><?php echo htmlspecialchars($userEmail); ?></div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="dashboard.php" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="profile.php" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>
                                <a href="membership.php" class="dropdown-item">
                                    <i class="fas fa-id-card"></i>
                                    My Membership
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="?logout=true" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login_and_singin.php" class="login-btn">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Rest of your HTML content remains exactly the same -->
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-content">
            <h1>Welcome to PowerFit Gym</h1>
            <p>Train hard, stay strong, and live healthy. We provide world-class training facilities, certified trainers, and motivational fitness programs designed to help you achieve your dream body.</p>
            <a href="#membership" class="btn">Start Your Fitness Journey Now!</a>
        </div>
    </section>

    <!-- Highlights Section -->
    <section class="highlights">
        <div class="container">
            <div class="section-title">
                <h2>Our Highlights</h2>
                <p>Discover what makes PowerFit Gym the best choice for your fitness journey</p>
            </div>
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Modern Equipment</h3>
                    <p>State-of-the-art fitness equipment for all your workout needs</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Experienced Trainers</h3>
                    <p>Certified professionals to guide you every step of the way</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>Personal Training</h3>
                    <p>One-on-one sessions tailored to your specific goals</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3>Flexible Plans</h3>
                    <p>Membership options to suit every budget and schedule</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <h3>Nutrition Support</h3>
                    <p>Customized diet plans to complement your workouts</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Health Focus</h3>
                    <p>Holistic approach to fitness and overall well-being</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-title">
                <h2>About Us</h2>
                <p>Learn more about our mission, vision, and what drives us</p>
            </div>
            <div class="about-content">
                <div class="about-image">
                    <img src="./images/2.jpg" alt="PowerFit Gym Interior">
                </div>
                <div class="about-text">
                    <h3>Your Fitness Journey Starts Here</h3>
                    <p>At PowerFit Gym, we believe that fitness is not just a goal — it's a lifestyle. Founded in 2018, our mission is to help every individual reach their peak physical and mental potential through proper guidance, motivation, and community support.</p>
                    <p>Our team of certified trainers ensures that every workout is effective, safe, and enjoyable. Whether you're a beginner or a professional athlete, we tailor your training program to suit your body and goals.</p>
                    <div class="mission-vision">
                        <div class="mission">
                            <h4>Our Mission</h4>
                            <p>To inspire people to stay active, healthy, and confident through fitness.</p>
                        </div>
                        <div class="vision">
                            <h4>Our Vision</h4>
                            <p>To be the most trusted and result-oriented gym in the city.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>We offer a wide range of fitness programs for all age groups and fitness levels</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/3.jpg" alt="Strength Training">
                    </div>
                    <div class="service-content">
                        <h3>Strength Training</h3>
                        <p>Build muscle, increase power, and boost endurance with our comprehensive strength training programs.</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/4.jpg" alt="Cardio Training">
                    </div>
                    <div class="service-content">
                        <h3>Cardio Training</h3>
                        <p>Burn fat and improve heart health with our state-of-the-art cardio equipment and guided sessions.</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/5.jpg" alt="CrossFit Training">
                    </div>
                    <div class="service-content">
                        <h3>CrossFit & Functional Training</h3>
                        <p>Full-body workouts for stamina and strength that prepare you for real-world physical challenges.</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/6.jpg" alt="Yoga & Meditation">
                    </div>
                    <div class="service-content">
                        <h3>Yoga & Meditation</h3>
                        <p>Relax your mind and body for complete wellness with our yoga and meditation classes.</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/7.jpg" alt="Zumba Dance">
                    </div>
                    <div class="service-content">
                        <h3>Zumba Dance</h3>
                        <p>Fun, energetic dance workouts to stay fit while having a great time with our community.</p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-image">
                        <img src="./images/8.jpg" alt="Personal Training">
                    </div>
                    <div class="service-content">
                        <h3>Personal Training</h3>
                        <p>One-on-one coaching designed specifically for your goals, with personalized attention and guidance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Plans Section -->
    <section class="membership" id="membership">
        <div class="container">
            <div class="section-title">
                <h2>Membership Plans</h2>
                <p>Choose a membership that fits your lifestyle and fitness goals</p>
            </div>
            <div class="plans-grid">
                <div class="plan-card">
                    <div class="plan-header">
                        <h3>Basic Plan</h3>
                        <div class="plan-price">₹999</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <div class="plan-features">
                        <ul>
                            <li>Access to gym floor</li>
                            <li>Basic equipment usage</li>
                            <li>Locker room access</li>
                            <li>Free fitness assessment</li>
                        </ul>
                        <a href="#membership-form" class="btn">Get Started</a>
                    </div>
                </div>
                <div class="plan-card">
                    <div class="plan-header">
                        <h3>Standard Plan</h3>
                        <div class="plan-price">₹1,999</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <div class="plan-features">
                        <ul>
                            <li>All Basic Plan features</li>
                            <li>Cardio zone access</li>
                            <li>2 group sessions per week</li>
                            <li>Fitness tracking</li>
                            <li>Nutrition guidance</li>
                        </ul>
                        <a href="#membership-form" class="btn">Get Started</a>
                    </div>
                </div>
                <div class="plan-card">
                    <div class="plan-header">
                        <h3>Premium Plan</h3>
                        <div class="plan-price">₹2,999</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <div class="plan-features">
                        <ul>
                            <li>All Standard Plan features</li>
                            <li>Unlimited group classes</li>
                            <li>Personal trainer sessions</li>
                            <li>Customized diet chart</li>
                            <li>Priority booking</li>
                            <li>Progress tracking</li>
                        </ul>
                        <a href="#membership-form" class="btn">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="special-offers">
                <h3>Special Offers</h3>
                <div class="offers-grid">
                    <div class="offer-card">
                        <i class="fas fa-percentage"></i>
                        <h3>10% Off Yearly</h3>
                        <p>Get 10% discount when you choose annual membership</p>
                    </div>
                    <div class="offer-card">
                        <i class="fas fa-user-plus"></i>
                        <h3>Free Trial</h3>
                        <p>First-time visitors get a free trial session to experience our facilities</p>
                    </div>
                </div>
            </div>
            
            <!-- Membership Form -->
            <div class="membership-form-container" id="membership-form">
                <h3>Join PowerFit Gym Today!</h3>
                
    <!-- YOUR SAME FORM (UNTOUCHED, NO CHANGES) -->

<form class="membership-form" id="membershipForm" method="post" action="index.php">

    <div class="form-group">
        <label for="full-name">Full Name</label>
        <input type="text" id="full-name" name="full-name" required>
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required>
    </div>

    <div class="form-group">
        <label for="plan">Select Membership Plan</label>
        <select id="plan" name="plan" required>
            <option value="">Choose a plan</option>
            <option value="Basic Plan">Basic Plan - ₹999/month</option>
            <option value="Standard Plan">Standard Plan - ₹1,999/month</option>
            <option value="Premium Plan">Premium Plan - ₹2,999/month</option>
        </select>
    </div>

    <div class="form-group">
        <label for="age">Age</label>
        <input type="number" id="age" name="age" min="16" max="80" required>
    </div>

    <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="form-group full-width">
        <label for="goals">Fitness Goals</label>
        <textarea id="goals" name="goals" placeholder="Tell us about your fitness goals and any health concerns"></textarea>
    </div>

    <!-- Payment Section -->
    <div class="payment-section">
        <h4>Payment Method</h4>

        <div class="payment-methods">
            <div class="payment-method" data-method="credit-card">
                <input type="radio" id="credit-card" name="payment-method" value="credit-card" required>
                <i class="fas fa-credit-card"></i>
                <span>Credit Card</span>
            </div>

            <div class="payment-method" data-method="debit-card">
                <input type="radio" id="debit-card" name="payment-method" value="debit-card">
                <i class="fas fa-credit-card"></i>
                <span>Debit Card</span>
            </div>

            <div class="payment-method" data-method="upi">
                <input type="radio" id="upi" name="payment-method" value="upi">
                <i class="fas fa-mobile-alt"></i>
                <span>UPI Payment</span>
            </div>

            <div class="payment-method" data-method="net-banking">
                <input type="radio" id="net-banking" name="payment-method" value="net-banking">
                <i class="fas fa-university"></i>
                <span>Net Banking</span>
            </div>
        </div>

        <!-- Card Details (Not Stored) -->
        <div class="payment-details" id="card-details">
            <h5>Card Details</h5>
            <div class="card-element">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-amex"></i>
                <i class="fab fa-cc-discover"></i>
            </div>

            <div class="form-group full-width">
                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456">
            </div>

            <div class="card-details">
                <div class="form-group">
                    <label for="expiry-date">Expiry Date</label>
                    <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="card-name">Name on Card</label>
                <input type="text" id="card-name" name="card-name" placeholder="John Doe">
            </div>
        </div>

        <!-- UPI (Not Stored) -->
        <div class="payment-details" id="upi-details">
            <h5>UPI Payment</h5>
            <div class="form-group full-width">
                <label for="upi-id">UPI ID</label>
                <input type="text" id="upi-id" name="upi-id" placeholder="yourname@upi">
            </div>
            <p>You will be redirected to your UPI app to complete the payment</p>
        </div>

        <!-- Net Banking (Not Stored) -->
        <div class="payment-details" id="net-banking-details">
            <h5>Net Banking</h5>
            <div class="form-group full-width">
                <label for="bank">Select Bank</label>
                <select id="bank" name="bank">
                    <option value="">Choose your bank</option>
                    <option value="sbi">State Bank of India</option>
                    <option value="hdfc">HDFC Bank</option>
                    <option value="icici">ICICI Bank</option>
                    <option value="axis">Axis Bank</option>
                    <option value="pnb">Punjab National Bank</option>
                </select>
            </div>
            <p>You will be redirected to your bank's secure payment gateway</p>
        </div>
    </div>

    <!-- Payment Button with Condition -->

    <button type="submit" name="submit" class="btn">Complete Payment & Join Now</button>

</form>
            </div>
        </div>
    </section>

    <!-- Trainers Section -->
    <section class="trainers" id="trainers">
        <div class="container">
            <div class="section-title">
                <h2>Our Trainers</h2>
                <p>Meet our professional and passionate fitness coaches</p>
            </div>
            <div class="trainers-grid">
                <div class="trainer-card">
                    <div class="trainer-image">
                        <img src="./images/9.png" alt="Rahul Mehta">
                    </div>
                    <div class="trainer-content">
                        <h3>Rahul Mehta</h3>
                        <div class="trainer-role">Strength Expert</div>
                        <p class="trainer-quote">"Push yourself, because no one else is going to do it for you."</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-image">
                        <img src="./images/10.jpg" alt="Priya Sharma">
                    </div>
                    <div class="trainer-content">
                        <h3>Priya Sharma</h3>
                        <div class="trainer-role">Yoga & Wellness Coach</div>
                        <p class="trainer-quote">"Balance your body, calm your mind, and find your inner peace."</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-image">
                        <img src="./images/11.jpg" alt="Arjun Singh">
                    </div>
                    <div class="trainer-content">
                        <h3>Arjun Singh</h3>
                        <div class="trainer-role">Personal Trainer</div>
                        <p class="trainer-quote">"Every day is another chance to improve yourself."</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-image">
                        <img src="./images/12.jpg" alt="Neha Patel">
                    </div>
                    <div class="trainer-content">
                        <h3>Neha Patel</h3>
                        <div class="trainer-role">Zumba Instructor</div>
                        <p class="trainer-quote">"Move your body, feel the rhythm, enjoy every beat."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery">
        <div class="container">
            <div class="section-title">
                <h2>Gallery</h2>
                <p>Explore moments of energy, power, and transformation at PowerFit Gym</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="./images/1.jpg" alt="Gym Facility" data-index="0">
                </div>
                <div class="gallery-item">
                    <img src="./images/13.jpg" alt="Training Session" data-index="1">
                </div>
                <div class="gallery-item">
                    <img src="./images/14.jpg" alt="Group Exercise" data-index="2">
                </div>
                <div class="gallery-item">
                    <img src="./images/15.jpg" alt="Weight Training" data-index="3">
                </div>
                <div class="gallery-item">
                    <img src="./images/16.jpg" alt="Yoga Class" data-index="4">
                </div>
                <div class="gallery-item">
                    <img src="./images/17.jpg" alt="Member Transformation" data-index="5">
                </div>
                <div class="gallery-item">
                    <img src="./images/18.jpeg" alt="Member Transformation" data-index="6">
                </div>
                <div class="gallery-item">
                    <img src="./images/19.jpg" alt="Member Transformation" data-index="7">
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox">
        <button class="lightbox-close">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-nav">
            <button class="lightbox-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="lightbox-next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="lightbox-content">
            <img src="" alt="">
        </div>
    </div>

    <!-- Schedule Section -->
    <section class="schedule">
        <div class="container">
            <div class="section-title">
                <h2>Workout Schedule</h2>
                <p>Plan your visits with our convenient opening hours</p>
            </div>
            <div class="schedule-content">
                <div class="schedule-image">
                    <img src="./images/20.jpg" alt="Gym Schedule">
                </div>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <div class="schedule-day">Monday - Saturday</div>
                        <div class="schedule-time">6:00 AM - 11:00 AM</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-day">Monday - Saturday</div>
                        <div class="schedule-time">4:00 PM - 10:00 PM</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-day">Sunday</div>
                        <div class="schedule-time">Closed</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-day">Special Events</div>
                        <div class="schedule-time">Occasionally on Sundays</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Diet Plans Section -->
    <section class="diet-plans">
        <div class="container">
            <div class="section-title">
                <h2>Diet Plans</h2>
                <p>Nutrition plays a vital role in fitness. We provide customized diet plans to match your workout and goals</p>
            </div>
            <div class="diet-grid">
                <div class="diet-card">
                    <div class="diet-header">
                        <h3>Weight Loss Plan</h3>
                    </div>
                    <div class="diet-content">
                        <ul>
                            <li>High protein, low carb meals</li>
                            <li>More fruits and green vegetables</li>
                            <li>Portion control guidance</li>
                            <li>Meal timing recommendations</li>
                            <li>Hydration plan</li>
                        </ul>
                    </div>
                </div>
                <div class="diet-card">
                    <div class="diet-header">
                        <h3>Muscle Gain Plan</h3>
                    </div>
                    <div class="diet-content">
                        <ul>
                            <li>High calorie, protein-rich foods</li>
                            <li>Pre- and post-workout nutrition</li>
                            <li>Supplement guidance</li>
                            <li>Calorie tracking</li>
                            <li>Macronutrient balancing</li>
                        </ul>
                    </div>
                </div>
                <div class="diet-card">
                    <div class="diet-header">
                        <h3>Maintenance Plan</h3>
                    </div>
                    <div class="diet-content">
                        <ul>
                            <li>Balanced nutrients for long-term fitness</li>
                            <li>Sustainable eating habits</li>
                            <li>Flexible dieting approach</li>
                            <li>Lifestyle integration</li>
                            <li>Periodic adjustments</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BMI Calculator Section -->
    <section class="bmi-calculator">
        <div class="container">
            <div class="section-title">
                <h2>BMI Calculator</h2>
                <p>Know your fitness level! Calculate your Body Mass Index (BMI)</p>
            </div>
            <div class="bmi-content">
                <div class="bmi-form">
                    <h3>Calculate Your BMI</h3>
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height" placeholder="Enter your height">
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" placeholder="Enter your weight">
                    </div>
                    <button class="btn" id="calculate-bmi">Calculate BMI</button>
                </div>
                <div class="bmi-result">
                    <h3>Your BMI Result</h3>
                    <div class="bmi-value" id="bmi-value">--</div>
                    <div class="bmi-category" id="bmi-category">--</div>
                    <p id="bmi-message">Enter your height and weight to calculate your BMI</p>
                </div>
            </div>
            <div class="bmi-info">
                <h3>BMI Categories</h3>
                <table class="bmi-table">
                    <thead>
                        <tr>
                            <th>BMI Range</th>
                            <th>Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Below 18.5</td>
                            <td>Underweight</td>
                        </tr>
                        <tr>
                            <td>18.5 – 24.9</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>25 – 29.9</td>
                            <td>Overweight</td>
                        </tr>
                        <tr>
                            <td>30 & above</td>
                            <td>Obese</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Testimonials</h2>
                <p>See what our members have to say about their experience at PowerFit Gym</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "I lost 10kg in 3 months! PowerFit Gym completely changed my life. The trainers are amazing and the environment is so motivating."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="./images/21.jpg" alt="Riya S.">
                        </div>
                        <div class="author-info">
                            <h4>Riya S.</h4>
                            <p>Member since 2021</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Trainers here are so motivating. The energy is amazing! I've never been this consistent with my workouts before joining PowerFit."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="./images/22.jpg" alt="Karan P.">
                        </div>
                        <div class="author-info">
                            <h4>Karan P.</h4>
                            <p>Member since 2020</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Clean gym, great machines, and the best guidance. The personal training sessions have helped me achieve my fitness goals faster than I expected."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="./images/23.jpeg" alt="Sonal M.">
                        </div>
                        <div class="author-info">
                            <h4>Sonal M.</h4>
                            <p>Member since 2022</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>We'd love to hear from you! Get in touch with any questions or to schedule a visit</p>
            </div>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Address</h3>
                            <p>PowerFit Gym, Near City Mall, Ahmedabad</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Phone</h3>
                            <p>+91 98765 43210</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>contact@powerfitgym.in</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Working Hours</h3>
                            <p>Monday - Saturday: 6:00 AM - 10:00 PM</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" placeholder="Your Name" required id="contact-name">
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email" required id="contact-email">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Subject" id="contact-subject">
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Your Message" required id="contact-message"></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about PowerFit Gym</p>
            </div>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Do I need prior gym experience?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>No, beginners are always welcome! Our trainers will guide you through every step and create a personalized program based on your fitness level and goals.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Can I freeze my membership?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can pause your membership for up to 2 months per year for medical reasons or travel. Please contact our front desk for assistance with freezing your membership.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Do you provide diet plans?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we provide customized diet plans. They are included free with our Premium membership, and available as an add-on for other membership levels.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What should I bring for my first visit?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>For your first visit, we recommend bringing workout clothes, athletic shoes, a water bottle, and a towel. We provide lockers for your belongings.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Are there age restrictions for joining?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Members must be at least 16 years old. For members between 16-18, a parent or guardian must sign the membership agreement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events">
        <div class="container">
            <div class="section-title">
                <h2>Events & Challenges</h2>
                <p>Join our exciting fitness events and challenges throughout the year</p>
            </div>
            <div class="events-grid">
                <div class="event-card">
                    <div class="event-image">
                        <img src="./images/23.jpg" alt="Annual Fitness Challenge">
                    </div>
                    <div class="event-content">
                        <h3>Annual Fitness Challenge</h3>
                        <p>Win prizes for best transformation in our year-long fitness challenge. Track your progress and compete with fellow members.</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-image">
                        <img src="./images/24.jpg" alt="Yoga Week">
                    </div>
                    <div class="event-content">
                        <h3>Yoga Week</h3>
                        <p>Relax and refresh with daily yoga sessions focused on mindfulness, flexibility, and stress relief. Suitable for all levels.</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-image">
                        <img src="./images/25.jpg" alt="Strength Showdown">
                    </div>
                    <div class="event-content">
                        <h3>Strength Showdown</h3>
                        <p>Compete and showcase your strength in various lifting categories. Test your limits in a supportive environment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>PowerFit Gym</h3>
                    <p>Train hard, stay strong, and live healthy. Your journey to fitness starts here with world-class facilities and expert guidance.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#membership">Membership</a></li>
                        <li><a href="#trainers">Trainers</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="#services">Strength Training</a></li>
                        <li><a href="#services">Cardio Training</a></li>
                        <li><a href="#services">CrossFit</a></li>
                        <li><a href="#services">Yoga & Meditation</a></li>
                        <li><a href="#services">Personal Training</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact Info</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> PowerFit Gym, Near City Mall, Ahmedabad</li>
                        <li><i class="fas fa-phone-alt"></i> +91 98765 43210</li>
                        <li><i class="fas fa-envelope"></i> contact@powerfitgym.in</li>
                        <li><i class="fas fa-clock"></i> Mon-Sat: 6:00 AM - 10:00 PM</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 PowerFit Gym. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a></p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const nav = document.querySelector('nav');
        
        mobileMenuBtn.addEventListener('click', () => {
            nav.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on a link
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('active');
            });
        });
        
        // User Profile Dropdown
        const userAvatar = document.getElementById('userAvatar');
        const userMenuMobile = document.getElementById('userMenuMobile');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userAvatar && userDropdown) {
            // Desktop avatar click
            userAvatar.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });
            
            // Mobile three-dot menu click
            if (userMenuMobile) {
                userMenuMobile.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userDropdown.classList.toggle('active');
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userAvatar.contains(e.target) && 
                    !userMenuMobile.contains(e.target) && 
                    !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });
            
            // Close dropdown on mobile when clicking a link
            const dropdownLinks = userDropdown.querySelectorAll('a');
            dropdownLinks.forEach(link => {
                link.addEventListener('click', () => {
                    userDropdown.classList.remove('active');
                    nav.classList.remove('active'); // Also close mobile menu
                });
            });
        }
        
        // FAQ Accordion
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
            });
        });
        
        // BMI Calculator
        const calculateBtn = document.getElementById('calculate-bmi');
        const heightInput = document.getElementById('height');
        const weightInput = document.getElementById('weight');
        const bmiValue = document.getElementById('bmi-value');
        const bmiCategory = document.getElementById('bmi-category');
        const bmiMessage = document.getElementById('bmi-message');
        
        calculateBtn.addEventListener('click', () => {
            const height = parseFloat(heightInput.value) / 100; // Convert cm to m
            const weight = parseFloat(weightInput.value);
            
            if (isNaN(height) || isNaN(weight) || height <= 0 || weight <= 0) {
                // No alert - just return
                return;
            }
            
            const bmi = weight / (height * height);
            const roundedBmi = bmi.toFixed(1);
            
            bmiValue.textContent = roundedBmi;
            
            let category = '';
            let message = '';
            
            if (bmi < 18.5) {
                category = 'Underweight';
                message = 'Consider consulting with our nutritionists for a healthy weight gain plan.';
            } else if (bmi >= 18.5 && bmi <= 24.9) {
                category = 'Normal';
                message = 'Great! Maintain your healthy weight with our fitness programs.';
            } else if (bmi >= 25 && bmi <= 29.9) {
                category = 'Overweight';
                message = 'Our weight management programs can help you achieve a healthier weight.';
            } else {
                category = 'Obese';
                message = 'We recommend consulting with our trainers for a personalized fitness plan.';
            }
            
            bmiCategory.textContent = category;
            bmiMessage.textContent = message;
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Gallery Lightbox
        const lightbox = document.querySelector('.lightbox');
        const lightboxImg = document.querySelector('.lightbox-content img');
        const lightboxClose = document.querySelector('.lightbox-close');
        const lightboxPrev = document.querySelector('.lightbox-prev');
        const lightboxNext = document.querySelector('.lightbox-next');
        const galleryItems = document.querySelectorAll('.gallery-item img');
        
        let currentImageIndex = 0;
        
        // Open lightbox when clicking on a gallery image
        galleryItems.forEach((img, index) => {
            img.addEventListener('click', () => {
                currentImageIndex = index;
                updateLightboxImage();
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        });
        
        // Close lightbox
        lightboxClose.addEventListener('click', () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        });
        
        // Navigate to previous image
        lightboxPrev.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
            updateLightboxImage();
        });
        
        // Navigate to next image
        lightboxNext.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex + 1) % galleryItems.length;
            updateLightboxImage();
        });
        
        // Close lightbox when clicking outside the image
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Update lightbox image
        function updateLightboxImage() {
            const imgSrc = galleryItems[currentImageIndex].getAttribute('src');
            const imgAlt = galleryItems[currentImageIndex].getAttribute('alt');
            lightboxImg.setAttribute('src', imgSrc);
            lightboxImg.setAttribute('alt', imgAlt);
        }
        
        // Contact Form Submission - No popup
        const contactForm = document.getElementById('contactForm');
        
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('contact-name').value;
            const email = document.getElementById('contact-email').value;
            const subject = document.getElementById('contact-subject').value;
            const message = document.getElementById('contact-message').value;
            
            // In a real application, you would send this data to a server
            console.log('Form submitted:', { name, email, subject, message });
            
            // No success message - just reset form
            contactForm.reset();
        });
        
        // Payment Methods Selection
        const paymentMethods = document.querySelectorAll('.payment-method');
        const cardDetails = document.getElementById('card-details');
        const upiDetails = document.getElementById('upi-details');
        const netBankingDetails = document.getElementById('net-banking-details');
        
        paymentMethods.forEach(method => {
            method.addEventListener('click', () => {
                // Remove selected class from all methods
                paymentMethods.forEach(m => m.classList.remove('selected'));
                
                // Add selected class to clicked method
                method.classList.add('selected');
                
                // Check the radio button
                const radio = method.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Show appropriate payment details
                const methodType = method.getAttribute('data-method');
                
                // Hide all payment details
                cardDetails.classList.remove('active');
                upiDetails.classList.remove('active');
                netBankingDetails.classList.remove('active');
                
                // Show selected payment details
                if (methodType === 'credit-card' || methodType === 'debit-card') {
                    cardDetails.classList.add('active');
                } else if (methodType === 'upi') {
                    upiDetails.classList.add('active');
                } else if (methodType === 'net-banking') {
                    netBankingDetails.classList.add('active');
                }
            });
        });
        
        // Membership Form Submission - No popup
        const membershipForm = document.getElementById('membershipForm');
        
        membershipForm.addEventListener('submit', (e) => {
            // Let the form submit normally to PHP
            // No JavaScript interference
            
            // Reset payment method selection
            paymentMethods.forEach(method => method.classList.remove('selected'));
            cardDetails.classList.remove('active');
            upiDetails.classList.remove('active');
            netBankingDetails.classList.remove('active');
        });
    </script>

</body>
</html>