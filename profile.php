<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="hasan.png" alt="Logo" class="logo-img">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="signin.php">Sign In</a></li>
            <li><a href="forms.php">Forms</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
        <div class="profile-icon">
            <i class='bx bx-user'></i>
        </div>
    </nav>

    <div class="wrapper">
        <div class="profile-header">
            <img src="<?php echo $Picture; ?>" alt="Profile Picture">

            <img id="preview" alt="Profile Picture Preview">

            <button id="upload-btn">Upload Profile Picture</button>

            <form action="upload.php" method="POST" enctype="multipart/form-data" id="upload-form">
                <input type="file" name="profile_pic" id="file-upload" accept="image/*">
                <button type="submit" style="display: none;" id="submit-btn">Submit</button>
            </form>

            <h1 id="user-name">[Full Name]</h1>
            <p id="user-role">[Role]</p>
        </div>
        <div class="forms-section">
            <h3>View Forms</h3>
            <ul id="form-list" class="form-list">
                <li>Loading forms...</li>
            </ul>
        </div>
        <div class="others-section">
            <h3>Others</h3>
            <p>Additional profile-related information can go here.</p>
        </div>
    </div>

    <script>
        const uploadBtn = document.getElementById('upload-btn');
        const fileInput = document.getElementById('file-upload');
        const preview = document.getElementById('preview');
        const submitBtn = document.getElementById('submit-btn');

        // Show the file input when the upload button is clicked
        uploadBtn.addEventListener('click', () => {
            fileInput.click();
        });

        // Preview the selected image
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the preview
                    submitBtn.style.display = 'inline-block'; // Show the submit button
                };
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
                fetch('fetch_user.php') // Replace with your server endpoint
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Populate user data
                        document.getElementById('user-name').textContent = data.Name;
                        document.getElementById('user-role').textContent = data.role;
                        document.querySelector('.profile-header img').src = data.profile_picture || 'default.png';
                    })
                    .catch(error => console.error('Error fetching user data:', error));
            });

    </script>
</body>

</html>