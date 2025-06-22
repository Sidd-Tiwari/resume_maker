<?php
// Include the Composer autoloader
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Start the session
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get resume data from the database (as per your previous code)
$conn = new mysqli('localhost', 'root', '', 'resume_maker');
$user_id = $_SESSION['user_id'];
$resume_id = $_GET['id'];
$resume = $conn->query("SELECT * FROM resumes WHERE user_id=$user_id AND id=$resume_id")->fetch_assoc();

// Check if the resume exists
if (!$resume) {
    die("Resume not found.");
}

// Create PDF using Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

// Instantiate Dompdf
$dompdf = new Dompdf($options);

// Example HTML structure for PDF (you can customize this based on your needs)
$html = "
    <h1>" . $resume['full_name'] . "</h1>
    <p>Email: " . $resume['email'] . "</p>
    <p>Phone: " . $resume['phone'] . "</p>
    <h3>Education:</h3>
    <p>" . nl2br($resume['education']) . "</p>
    <h3>Experience:</h3>
    <p>" . nl2br($resume['experience']) . "</p>
    <h3>Skills:</h3>
    <p>" . nl2br($resume['skills']) . "</p>
";

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size (optional, adjust as needed)
$dompdf->setPaper('A4', 'portrait');

// Render PDF (first pass)
$dompdf->render();

// Output the generated PDF to the browser
$dompdf->stream('resume.pdf', array('Attachment' => 0)); // 0 means no download, just view in the browser
?>
