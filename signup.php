<?php
// Include database connection
require 'config.php';

// Initialize variables
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start a session to persist data between requests
    session_start();

    // Handle form submission
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $role = $_POST['role'];

    // Password validation
    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/';
    if (!preg_match($passwordPattern, $password)) {
        $_SESSION['message'] = "Password must be at least 12 characters long, include at least one uppercase letter, one number, and one special character.";
    }
    // Check if password matches confirm password
    elseif ($password !== $confirmPassword) {
        $_SESSION['message'] = "Passwords do not match.";
    }
    // Date of Birth validation (minimum age: 18)
    elseif (strtotime($dob) > strtotime('-18 years')) {
        $_SESSION['message'] = "You must be at least 18 years old.";
    }
    // Phone number validation (Lebanese format)
    elseif (!preg_match('/^\+961 (3|70|71|76|78|79|81|03|70|71|76|78|79|81) \d{3} \d{3}$/', $mobile)) {
        $_SESSION['message'] = "Invalid phone number. Format: +961 XX XXX XXX";
    } else {
        try {
            // Insert data into the database (password not stored here)
            $stmt = $conn->prepare("
                INSERT INTO users (First_Name, Last_Name, Email, BirthDate, Gender, Phone_Number, Role)
                VALUES (:first_name, :last_name, :email, :dob, :gender, :mobile, :role)
            ");
            $stmt->execute([
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':email' => $email,
                ':dob' => $dob,
                ':gender' => $gender,
                ':mobile' => $mobile,
                ':role' => $role,
            ]);

            $_SESSION['message'] = "Signup successful!";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    }

    // Redirect to the same page to avoid form resubmission
    header("Location: main.php");
    exit();
}

// If redirected, display the message and clear it
session_start();
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .password-requirements, .phone-requirements {
            display: none;
            position: absolute;
            background-color: #ffffff; /* White background */
            color: #000000; /* Black text */
            border: 1px solid #ccc; /* Light gray border */
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            width: 300px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slight shadow for a better look */
        }

        .input-box:hover .password-requirements,
        .input-box:hover .phone-requirements {
            display: block;
        }

        .password-requirements ul, .phone-requirements ul {
            list-style-type: none;
            padding: 0;
        }

        .password-requirements li, .phone-requirements li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <img src="logo.jpg" alt="logo image" class="image">
    <div class="wrapper">
        <h1>Sign Up</h1>
        <!-- Display success or error message -->
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="" method="post">
            <div class="input-box">
                <input type="text" name="first_name" placeholder="First Name" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="text" name="last_name" placeholder="Last Name" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email Address" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" pattern="(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}" required>
                <i class="fas fa-lock"></i>
                <div class="password-requirements">
                    <p>Password must:</p>
                    <ul>
                        <li>Be at least 12 characters long</li>
                        <li>Contain at least one uppercase letter</li>
                        <li>Contain at least one number</li>
                        <li>Contain at least one special character</li>
                    </ul>
                </div>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-box">
                <input type="date" name="dob" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
            </div>
            <div class="input-box">
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <i class="fas fa-venus-mars"></i>
            </div>
            <div class="input-box">
                <input type="tel" name="mobile" placeholder="Mobile Number" pattern="^\+961 (3|70|71|76|78|79|81|03|70|71|76|78|79|81) \d{3} \d{3}$" required>
                <i class="fas fa-phone"></i>
                <div class="phone-requirements">
                    <p>Phone number must:</p>
                    <ul>
                        <li>Start with +961</li>
                        <li>Contain a valid Lebanese area code (e.g., 81, 70, etc.)</li>
                        <li>Follow the format: +961 XX XXX XXX</li>
                    </ul>
                </div>
            </div>
            <div class="input-box">
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="citizen">Citizen</option>
                    <option value="staff">Staff</option>
                </select>
                <i class="fas fa-user-tag"></i>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="signin.php">Sign In here</a></p>
        </div>
    </div>
</body>
</html>
