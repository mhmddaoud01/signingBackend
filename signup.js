function submitForm1() {
    // Retrieve values from form elements
    var firstName = document.getElementById("fname").value;
    var lastName = document.getElementById("lname").value;
    var email = document.getElementById("email").value;
    var phonenumber = document.getElementById("mobile").value;
    var password = document.getElementById("password").value;
    var confirmpassword = document.getElementById("confirmpassword").value;
    var dateofbirth = document.getElementById("dob").value;
    var gender = document.getElementById("gender").value;

    // Check if required fields are filled
    if (!firstName || !lastName || !email || !phonenumber || !password || !confirmpassword || !dateofbirth) {
        alert("Please fill in all required fields.");
        return;
    }

    // Check if passwords match
    if (password !== confirmpassword) {
        alert("Passwords do not match.");
        return;
    }

    // Check password strength
    if (!isValidPassword(password)) {
        alert("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        return;
    }

    // Check phone number length
    if (phonenumber.length !== 13) {
        alert("Phone number must be exactly 13 digits (use 00961 for +961).");
        return;
    }

    // Check if phone number contains only numeric digits
    if (!/^\d+$/.test(phonenumber)) {
        alert("Phone number can only contain numeric digits.");
        return;
    }

    // Check if email is valid
    if (!isValidEmail(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    alert("Form submitted successfully!");
    window.location.href = "profile.php";

}

// Function to validate email format
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to validate password strength
function isValidPassword(password) {
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
}