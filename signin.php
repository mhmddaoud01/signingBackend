<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Login successful! (Password handling is separate.)";
        } else {
            $message = "Invalid email.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="signing.css">
</head>
<body>
    <img src="logo.jpg" alt="logo image" class="sign-in-image">
    <div class="wrapper">
        <h2>Sign In</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="" method="post">
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
        <div class="signin-link">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>
