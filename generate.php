<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'resume_maker');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form fields exist in the POST data
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$summary = isset($_POST['summary']) ? $_POST['summary'] : '';
$education = isset($_POST['education']) ? json_encode(explode("\n", $_POST['education'])) : '';
$experience = isset($_POST['experience']) ? json_encode(explode("\n", $_POST['experience'])) : '';
$skills = isset($_POST['skills']) ? json_encode(explode("\n", $_POST['skills'])) : '';
$selected_template = isset($_POST['selected_template']) ? $_POST['selected_template'] : '';

// Validate required fields
if (empty($full_name) || empty($email)) {
    echo "<p>Error: Full Name and Email are required.</p>";
    exit;
}

// Handle photo upload (optional)
$photo = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $photo = 'uploads/' . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
}

// Insert resume data into the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO resumes (user_id, full_name, email, phone, address, summary, education, experience, skills, selected_template, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('issssssssss', $user_id, $full_name, $email, $phone, $address, $summary, $education, $experience, $skills, $selected_template, $photo);

if ($stmt->execute()) {
    echo "<p>Resume has been generated successfully!</p>";
    echo "<p><a href='dashboard.php'>Go to Dashboard</a></p>";
} else {
    echo "<p>There was an error generating your resume. Please try again.</p>";
}

$stmt->close();
$conn->close();
?>
