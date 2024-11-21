<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <div class="profile-header">
            <img id="profile-pic" class="sign-in-image" src="default-pic.jpg" alt="Profile Picture">
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
        const API_URL = 'https://example.com/api/user-profile'; // Replace with your API URL

        async function fetchProfileData() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();

                // Set the user profile data
                document.getElementById('profile-pic').src = data.picture || 'default-pic.jpg';
                document.getElementById('user-name').textContent = data.fullName || 'Unknown User';
                document.getElementById('user-role').textContent = data.role || 'User';

                // Update the forms list
                const formList = document.getElementById('form-list');
                formList.innerHTML = '';
                (data.forms || []).forEach(form => {
                    const li = document.createElement('li');
                    li.textContent = form;
                    formList.appendChild(li);
                });

                // Restrict items for non-staff members
                if (data.role !== 'Staff Member') {
                    document.querySelectorAll('.restricted').forEach(item => item.remove());
                }
            } catch (error) {
                console.error('Error fetching profile data:', error);
            }
        }

        fetchProfileData();
    </script>
</body>
</html>