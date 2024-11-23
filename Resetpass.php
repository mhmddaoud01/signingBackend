<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="signupStyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #fff;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .alert.success {
            background-color: #4caf50; /* Green for success */
        }

        .alert.error {
            background-color: #f44336; /* Red for error */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form id="reset-form">
            <h1> Reset Password </h1>
            <div class="input-box">
                <input type="email" id="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <button type="button" class="btn" onclick="sendResetLink()">Send Reset Link</button>
        </form>
    </div>

    <!-- JavaScript code -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
        import { getAuth, sendPasswordResetEmail } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-auth.js";

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyCI-jiZK--TwHYoCEPQzXz7NGzcdvhowcw",
        authDomain: "my-beirut.firebaseapp.com",
        projectId: "my-beirut",
        storageBucket: "my-beirut.firebaseapp.com",
        messagingSenderId: "911192152729",
        appId: "1:911192152729:web:0309288871a01c80e83608",
        measurementId: "G-4X5L3L11D0",
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    console.log("Firebase initialized:", app);
    const auth = getAuth(app);

    // Function to send password reset link
    function sendResetLink() {
        console.log("Send Reset Link button clicked!");
        const emailInput = document.querySelector("#email");
        const email = emailInput.value.trim();

    if (!validateEmail(email)) {
        showAlert("Please enter a valid email address.", "error");
        return;
    }

    sendPasswordResetEmail(auth, email)
        .then(() => {
            showAlert("A password reset link has been sent to your email.", "success");
        })
        .catch((error) => {
            console.error("Error during password reset:", error);
            handleFirebaseError(error);
        });
}

// Expose sendResetLink to global scope
window.sendResetLink = sendResetLink;

// Function to validate email format
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to handle Firebase error codes
function handleFirebaseError(error) {
    switch (error.code) {
        case "auth/user-not-found":
            showAlert("No user found with this email address.", "error");
            break;
        case "auth/invalid-email":
            showAlert("Invalid email address format.", "error");
            break;
        default:
            showAlert("Failed to send reset link. Please try again.", "error");
            break;
    }
}

// Function to display alerts
function showAlert(message, type) {
    const alertBox = document.createElement("div");
    alertBox.textContent = message;
    alertBox.className = `alert ${type}`; // Add a class for styling (e.g., success or error)

    // Append alert to the body or a specific container
    document.body.appendChild(alertBox);

    // Remove the alert after 5 seconds
    setTimeout(() => {
        alertBox.remove();
    }, 5000);
}

    </script>
</body>

</html>
