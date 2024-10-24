<?php
// Database connection
$host = "localhost";
$username = "emalak";      
$password = "BegHSi";      
$dbname = "emalak_db";     

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the critic's name from the URL
$name = $_GET["name"];

// Prepare the statement
$stmt = $conn->prepare("SELECT Name, ReviewedCocktails FROM Critics WHERE Name = ?");

// Bind the parameter (the critic's name)
$stmt->bind_param("s", $name);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($critic = $result->fetch_assoc()) {
    // Critic data found
} else {
    // If no critic is found, display an error message
    echo "Critic not found.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $critic['Name']; ?>'s Profile</title>
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
                <!-- Dynamically display the critic's name -->
                <h1 id="critic-name"><?php echo htmlspecialchars($critic['Name']); ?></h1>
            </div>
            <div class="profile-details">
                <!-- Display the number of cocktails reviewed -->
                <p><strong>Reviewed Cocktails:</strong> <span id="reviewed-cocktails"><?php echo htmlspecialchars($critic['ReviewedCocktails']); ?></span></p>
            </div>
        </div>
        <div class="back-link">
            <a href="critics_list.php">← Back to Critics</a>
        </div>
    </div>
</body>
</html>
