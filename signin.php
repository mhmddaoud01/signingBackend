<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="signupStyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <img src="hasan.png" alt="logo image" class="sign-in-image">
    <br>
    <div class="wrapper">
        <form id="signin-form">
            <h2>Sign In</h2>
            <br>
            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <a href="Resetpass.php">Forgot password?</a>
            </div>
            <div class="signin-link">
                <p>Don't have an account? <a href="signup2.html"> Sign up here </a></p>
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
            measurementId: "G-4X5L3L11D0",
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Handle Sign-In Form Submission
        const form = document.getElementById("signin-form");
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            try {
                // Firebase Authentication: Sign in with email and password
                const userCredential = await signInWithEmailAndPassword(auth, email, password);

                // Retrieve the Firebase UID
                const firebaseUID = userCredential.user.uid;

                // Send UID to the backend to retrieve additional user info
                const response = await fetch("signin_backend.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ firebaseUID }),
                });

                const result = await response.json();

                if (result.status === "success") {
                    // Display user info or redirect to a profile page
                    alert("Welcome, " + result.data.First_Name + "!");
                    console.log("User details:", result.data);
                    window.location.href = "profile.php";
                } else {
                    alert("Error: Invalid Credentials");
                }
            } catch (error) {
                // Handle sign-in errors from Firebase
                console.error("Error signing in:", error.message);

                // Show "Invalid Credentials" for wrong password or user not found
                if (error.code === "auth/wrong-password" || error.code === "auth/user-not-found") {
                    alert("Error: Invalid Credentials");
                } else {
                    alert("Error: " + error.message);
                }
            }
        });
    </script>
</body>

</html>
