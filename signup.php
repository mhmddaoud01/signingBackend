<?php
// Ensure session starts at the very top of the file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$host = '127.0.0.1';
$db = 'mybeirut';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $data['action'] ?? '';

    if ($action === 'register') {
        // Handle user registration
        $user_id = $data['user_id'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $mobile = $data['mobile'];
        $dob = $data['dob'];
        $gender = $data['gender'];

        try {
            $stmt = $conn->prepare("INSERT INTO users (User_ID, First_Name, Last_Name, Email, Phone_Number, Gender, BirthDate)
                                    VALUES (:user_id, :first_name, :last_name, :email, :mobile, :gender, :dob)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);

            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } elseif ($action === 'start_session') {
        // Start user session
        $_SESSION['firebaseUID'] = $data['user_id'];
        echo json_encode(['status' => 'success', 'message' => 'Session started']);
    } elseif ($action === 'end_session') {
        // End user session
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Session ended']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signupStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="wrapper">
        <h1>Sign Up</h1>
        <form id="signup-form">
            <div class="input-box">
                <input type="text" id="fname" placeholder="First Name" required>
                <i class="fas fa-user"></i>
            </div>

            <div class="input-box">
                <input type="text" id="lname" placeholder="Last Name" required>
                <i class="fas fa-user"></i>
            </div>

            <div class="input-box">
                <input type="email" id="email" placeholder="Email Address" required>
                <i class="fas fa-envelope"></i>
            </div>

            <div class="input-box">
                <input type="password" id="password" placeholder="Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="input-box">
                <input type="password" id="confirmpassword" placeholder="Confirm Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="input-box">
                <input type="date" id="dob" required>
            </div>

            <div class="input-box">
                <select id="gender" required>
                    <option value="" selected disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <i class="fas fa-venus-mars"></i>
            </div>

            <div class="input-box">
                <input type="tel" id="mobile" placeholder="Mobile Number" required>
                <i class="fas fa-phone"></i>
            </div>

            <button type="button" id="signup-btn" class="btn">Sign Up</button>
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="signin.php">Sign In here</a></p>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
        import { getAuth, createUserWithEmailAndPassword, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-auth.js";

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCI-jiZK--TwHYoCEPQzXz7NGzcdvhowcw",
            authDomain: "my-beirut.firebaseapp.com",
            projectId: "my-beirut",
            storageBucket: "my-beirut.firebasestorage.app",
            messagingSenderId: "911192152729",
            appId: "1:911192152729:web:0309288871a01c80e83608",
            measurementId: "G-4X5L3L11D0"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Handle form submission
        document.getElementById("signup-btn").addEventListener("click", async () => {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmpassword").value;
            const firstName = document.getElementById("fname").value;
            const lastName = document.getElementById("lname").value;
            const dob = document.getElementById("dob").value;
            const gender = document.getElementById("gender").value;
            const mobile = document.getElementById("mobile").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            try {
                // Create user in Firebase Authentication
                const userCredential = await createUserWithEmailAndPassword(auth, email, password);
                const userId = userCredential.user.uid;

                // Send data to the same PHP file for MySQL insertion
                const response = await fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        action: "register",
                        user_id: userId,
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        mobile: mobile,
                        dob: dob,
                        gender: gender
                    }),
                });

                const result = await response.json();
                if (result.status === "success") {
                    alert("User registered successfully!");
                    window.location.href = "profile.php";
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                console.error(error.message);
                alert("An error occurred during registration.");
            }
        });

        // Firebase session listener
        onAuthStateChanged(auth, async (user) => {
            if (user) {
                console.log("User is signed in:", user.uid);
                await fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "start_session", user_id: user.uid }),
                });
            } else {
                console.log("User is signed out.");
                await fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "end_session" }),
                });
            }
        });
    </script>
</body>

</html>
