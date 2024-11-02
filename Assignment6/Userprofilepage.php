<?php
// Database connection
$host = "localhost";      // Adjust these variables to your setup
$username = "emalak";      // Your database username
$password = "BegHSi";      // Your database password
$dbname = "emalak_db";     // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the URL
$name = $_GET["name"];

// Prepare the statement
$stmt = $conn->prepare("SELECT p.Name, p.Age, u.Bio 
                        FROM User u
                        INNER JOIN Person p ON u.UserID = p.PersonID
                        WHERE p.Name = ?");

// Bind the parameter (the name)
$stmt->bind_param("s", $name);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // User data found
} else {
    // If no user is found, display an error message
    echo "<p>User not found.</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['name']; ?>'s Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-card {
            padding: 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-header h1 {
            font-size: 24px;
            color: #333;
        }

        .profile-details p {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        .profile-details p strong {
            color: #333;
        }

        .back-link {
            text-align: center;
            padding: 10px;
            background-color: #3498db;
        }

        .back-link a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .back-link a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <!-- Dynamically display the user's name -->
                <h1 id="user-name"><?php echo htmlspecialchars($user['Name']); ?></h1>
            </div>
            <div class="profile-details">
                <!-- Display the user's age and bio -->
                <p><strong>Age:</strong> <span id="user-age"><?php echo htmlspecialchars($user['Age']); ?></span></p>
                <p><strong>Bio:</strong> <span id="user-bio"><?php echo htmlspecialchars($user['Bio']); ?></span></p>
            </div>
        </div>
        <div class="back-link">
            <!-- Back to Users link -->
            <a href="index.html">← Back to Users</a>
        </div>
    </div>
</body>
</html>
