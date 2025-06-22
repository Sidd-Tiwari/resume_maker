<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.html');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'resume_maker');
$user_id = $_SESSION['user_id'];

$resumes = $conn->query("SELECT * FROM resumes WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        }
        .card h3 { margin: 0 0 10px; }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            margin-right: 10px;
        }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="header">
    <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    <a href="logout.php" class="btn">Logout</a>
</div>

<hr>

<?php if ($resumes->num_rows == 0): ?>
    <h3>You have no resume yet.</h3>
    <a href="form.html" class="btn">Create Your Resume Now</a>
<?php else: ?>
    <h3>Your Saved Resumes</h3>

    <?php while ($r = $resumes->fetch_assoc()): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($r['full_name']); ?></h3>
            <p>Created at: <?php echo $r['created_at']; ?></p>
            <a href="generate.php?id=<?php echo $r['id']; ?>" class="btn">Preview</a>
            <a href="form.html?edit_id=<?php echo $r['id']; ?>" class="btn">Edit</a>
            <a href="download_pdf.php?id=<?php echo $r['id']; ?>" class="btn">Download PDF</a>
            <a href="download_docx.php?id=<?php echo $r['id']; ?>" class="btn">Download DOCX</a>
        </div>
    <?php endwhile; ?>

<?php endif; ?>

</body>
</html>
