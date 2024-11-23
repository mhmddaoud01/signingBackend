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
    <img src="logo.jpg" alt="logo image" class="image">
    <div class="wrapper">
        <h1>Sign Up</h1>
        <form id="signup-form" method="post" enctype="multipart/form-data">
            <div class="input-box">
                <input type="text" id="fname" name="first_name" placeholder="First Name" required>
                <i class="fas fa-user"></i>
            </div>

            <div class="input-box">
                <input type="text" id="lname" name="last_name" placeholder="Last Name" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Email Address" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-box">
                <input type="password" id="confirmpassword" name="confirm_password" placeholder="Confirm Password"
                    required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-box">
                <input type="date" id="dob" name="dob" placeholder="Date of Birth" required>
            </div>
            <div class="input-box">
                <select id="gender" name="gender" required>
                    <option value="" selected disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <i class="fas fa-venus-mars"></i>
            </div>
            <div class="input-box">
                <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" required>
                <i class="fas fa-phone"></i>
            </div>

            <button type="button" class="btn">Sign Up</button>
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="signin.php">Sign In here</a></p>
        </div>
    </div>
    <script src="signup.js"></script>
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
        import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCI-jiZK--TwHYoCEPQzXz7NGzcdvhowcw",
            authDomain: "my-beirut.firebaseapp.com",
            projectId: "my-beirut",
            storageBucket: "my-beirut.firebasestorage.app",
            messagingSenderId: "911192152729",
            appId: "1:911192152729:web:0309288871a01c80e83608",
            measurementId: "G-4X5L3L11D0"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth();

        async function submitForm() {
    const firstName = document.getElementById("fname").value;
    const lastName = document.getElementById("lname").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmpassword").value;
    const dob = document.getElementById("dob").value;
    const gender = document.getElementById("gender").value;
    const mobile = document.getElementById("mobile").value;

    if (!firstName || !lastName || !email || !password || !confirmPassword || !dob || !gender || !mobile) {
        alert("Please fill in all fields.");
        return;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
    }

    try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const firebaseUID = userCredential.user.uid;

        const userData = {
            firebaseUID,
            first_name: firstName,
            last_name: lastName,
            email,
            mobile,
            gender,
            dob,
        };

        const response = await fetch("signup_backend.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData),
        });

        const result = await response.json();
        if (result.status === "success") {
            alert("User registered successfully!");
            window.location.href = "profile.php";
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Error: " + error.message);
    }
}


        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPassword(password) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password);
        }

        document.addEventListener("DOMContentLoaded", function () {
            const signUpButton = document.querySelector(".btn");
            signUpButton.addEventListener("click", submitForm);
        });
    </script>
</body>

</html>