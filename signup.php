<!-- PHP file: signup.php -->
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password != $confirm_password) {
        echo "<p>Passwords do not match. Please try again.</p>";
    } else {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p>A user with this email already exists. Please log in or use a different email.</p>";
        } else {
            // Hash the password for security
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $name, $email, $password_hashed);

            if ($stmt->execute()) {
                echo "<p>Registration successful! You can now <a href='login_form.html'>login</a>.</p>";
            } else {
                echo "<p>There was an error during registration. Please try again later.</p>";
            }

            $stmt->close();
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Resume Maker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2>Create an Account</h2>
        
        <form action="signup.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login_form.html">Login here</a></p>
    </div>

</body>
</html>
