<!-- PHP file: download_docx.php -->
<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

$conn = new mysqli('localhost', 'root', '', 'resume_maker');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Get the resume ID from the URL
if (!isset($_GET['id'])) {
    echo "No resume ID provided.";
    exit;
}
// Sanitize the input
$resume_id = $_GET['id'];

$res = $conn->query("SELECT * FROM resumes WHERE id=$resume_id");
$data = $res->fetch_assoc();

// Create new PhpWord object
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Add resume information
$section->addText($data['name'], ['bold' => true, 'size' => 20]);
$section->addText("Email: " . $data['email'] . " | Phone: " . $data['phone']);

$section->addText('Education', ['bold' => true]);
foreach (json_decode($data['education']) as $edu) {
    $section->addText($edu);
}

$section->addText('Experience', ['bold' => true]);
foreach (json_decode($data['experience']) as $exp) {
    $section->addText($exp);
}

$section->addText('Skills', ['bold' => true]);
foreach (json_decode($data['skills']) as $sk) {
    $section->addText($sk);
}

$section->addText('Projects', ['bold' => true]);
foreach (json_decode($data['projects']) as $prj) {
    $section->addText($prj);
}

// Set header to force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment;filename="resume.docx"');
header('Cache-Control: max-age=0');

// Save the file to output as a downloadable DOCX
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
?>
