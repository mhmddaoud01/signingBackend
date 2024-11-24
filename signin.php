<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database configuration
include('config.php');

// Initialize error message
$error_message = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $firebaseUID = $_POST['firebaseUID'] ?? null;

    // Validate input
    if (!$email || !$firebaseUID) {
        $error_message = 'Authentication failed. Please try again.';
    } else {
        try {
            // Prepare and execute SQL query to retrieve user details
            $sql = "SELECT * FROM users WHERE Email = :email AND User_ID = :firebaseUID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':firebaseUID', $firebaseUID, PDO::PARAM_STR);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Start the session
                session_start();

                // Set session variables
                $_SESSION['user_id'] = $user['User_ID'];
                $_SESSION['firebaseUID'] = $firebaseUID;
                $_SESSION['email'] = $user['Email'];
                $_SESSION['first_name'] = $user['First_Name'];

                // Redirect to profile page
                header('Location: profile.php');
                exit;
            } else {
                $error_message = 'No user found with this email address and ID.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="signupStyle.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <img src="hasan.png" alt="logo image" class="sign-in-image">
    <br>
    <div class="wrapper">
        <form id="signin-form" method="POST" action="signin.php">
            <h2>Sign In</h2>
            <br>

            <!-- Display error message if it exists -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <a href="Resetpass.php">Forgot password?</a>
            </div>
            <div class="signin-link">
                <p>Don't have an account? <a href="signup2.html">Sign up here</a></p>
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-auth.js";

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCI-jiZK--TwHYoCEPQzXz7NGzcdvhowcw",
            authDomain: "my-beirut.firebaseapp.com",
            projectId: "my-beirut",
            storageBucket: "my-beirut.firebaseapp.com",
            messagingSenderId: "911192152729",
            appId: "1:911192152729:web:0309288871a01c80e83608",
            measurementId: "G-4X5L3L11D0"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Handle Sign-In Form Submission
        document.getElementById("signin-form").addEventListener("submit", async (e) => {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            try {
                // Firebase Authentication: Sign in with email and password
                const userCredential = await signInWithEmailAndPassword(auth, email, password);

                // Retrieve the Firebase UID
                const firebaseUID = userCredential.user.uid;

                // Add Firebase UID as a hidden field to the form
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "firebaseUID";
                hiddenInput.value = firebaseUID;

                const form = document.getElementById("signin-form");
                form.appendChild(hiddenInput);

                // Submit the form to the backend
                form.submit();
            } catch (error) {
                // Display Firebase authentication errors
                alert("Authentication Error: " + error.message);
            }
        });
    </script>
</body>

</html>
