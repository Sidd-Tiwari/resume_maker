<!-- PHP file: login.php -->
<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'resume_maker');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database for the user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now check the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php'); // Redirect to dashboard
            exit;
        } else {
            // Incorrect password
            echo "<p>Invalid password. Please try again.</p>";
        }
    } else {
        // User not found
        echo "<p>No user found with this email. Please register.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resume Maker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2>Login to Your Account</h2>
        
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Register here</a></p>
    </div>

</body>
</html>
