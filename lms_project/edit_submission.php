<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Submission ID missing");
}

$id = (int) $_GET['id'];

$query = "SELECT * FROM submissions WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Submission not found");
}

$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $marks = (int) $_POST['marks'];
    $status = mysqli_real_escape_string($conn, trim($_POST['status']));

    $update = "UPDATE submissions 
               SET marks = $marks, status = '$status' 
               WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: /lms_project/teacher_panel.php");
        exit();
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:linear-gradient(135deg,#eef2ff,#f8fafc);
            font-family:'Segoe UI',sans-serif;
        }

        .card-pro{
            max-width:500px;
            margin:80px auto;
            padding:30px;
            border-radius:22px;
            background:white;
            box-shadow:0 20px 40px rgba(0,0,0,0.08);
        }

        h3{
            font-weight:800;
            margin-bottom:20px;
        }

        .btn-pro{
            background:linear-gradient(135deg,#4f46e5,#3b82f6);
            color:white;
            border:none;
            border-radius:12px;
            padding:12px;
            font-weight:700;
        }

        .btn-pro:hover{
            color:white;
        }
    </style>
</head>
<body>

<div class="card-pro">
    <h3>Edit Submission</h3>

    <form method="POST">
        <label class="mb-1">Marks</label>
        <input type="number" name="marks" class="form-control mb-3" value="<?php echo htmlspecialchars($data['marks']); ?>" required>

        <label class="mb-1">Status</label>
        <select name="status" class="form-select mb-3" required>
            <option value="Pending" <?php if($data['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Reviewed" <?php if($data['status'] == 'Reviewed') echo 'selected'; ?>>Reviewed</option>
            <option value="Checked" <?php if($data['status'] == 'Checked') echo 'selected'; ?>>Checked</option>
        </select>

        <button type="submit" name="update" class="btn btn-pro w-100">Update Submission</button>
    </form>
</div>

</body>
</html>