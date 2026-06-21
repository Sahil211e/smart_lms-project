<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];
$name = isset($user['name']) ? htmlspecialchars($user['name']) : 'Student';
$initial = strtoupper(substr($name, 0, 1));

if (!isset($_GET['id'])) {
    echo "Assignment ID missing. Please open from assignments page.";
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT * FROM assignments WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$assignment = mysqli_fetch_assoc($result);

if (!$assignment) {
    echo "Assignment not found.";
    exit();
}

$message = "";
$message_type = "";

if (isset($_POST['submit_assignment'])) {
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {

        $file_name = time() . "_" . basename($_FILES['assignment_file']['name']);
        $tmp_name = $_FILES['assignment_file']['tmp_name'];
        $upload_path = "uploads/" . $file_name;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            $student_name = mysqli_real_escape_string($conn, $name);
            $safe_file_name = mysqli_real_escape_string($conn, $file_name);

            $insert = "INSERT INTO submissions (assignment_id, student_name, file_name)
                       VALUES ($id, '$student_name', '$safe_file_name')";

            if (mysqli_query($conn, $insert)) {
                $message = "Assignment submitted successfully!";
                $message_type = "success";
            } else {
                $message = "Database error: " . mysqli_error($conn);
                $message_type = "danger";
            }
        } else {
            $message = "File upload failed. Please check uploads folder.";
            $message_type = "danger";
        }
    } else {
        $message = "Please choose a file first.";
        $message_type = "warning";
    }
}

$title = isset($assignment['title']) ? htmlspecialchars($assignment['title']) : 'Assignment';
$description = isset($assignment['description']) ? htmlspecialchars($assignment['description']) : 'No description available';
$due_date = isset($assignment['due_date']) ? htmlspecialchars($assignment['due_date']) : 'Not Available';
$course = 'General Assignment';

if (isset($assignment['course'])) {
    $course = htmlspecialchars($assignment['course']);
} elseif (isset($assignment['course_name'])) {
    $course = htmlspecialchars($assignment['course_name']);
} elseif (isset($assignment['subject'])) {
    $course = htmlspecialchars($assignment['subject']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment | Smart LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI', sans-serif;
            min-height:100vh;
            background:
                radial-gradient(circle at top left, rgba(99,102,241,0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(14,165,233,0.14), transparent 28%),
                linear-gradient(135deg, #eef2ff, #f8fafc, #e2e8f0);
            color:#0f172a;
        }

        .navbar-pro{
            padding:18px 0;
            background:rgba(255,255,255,0.55);
            backdrop-filter: blur(14px);
            border-bottom:1px solid rgba(255,255,255,0.6);
            box-shadow:0 8px 30px rgba(15,23,42,0.05);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            font-weight:700;
            font-size:22px;
            color:#1e293b;
        }

        .brand-logo{
            width:46px;
            height:46px;
            border-radius:16px;
            background:linear-gradient(135deg, #6366f1, #3b82f6);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 12px 24px rgba(99,102,241,0.25);
        }

        .profile-chip{
            display:flex;
            align-items:center;
            gap:12px;
            background:rgba(255,255,255,0.75);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 10px 30px rgba(15,23,42,0.06);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #0ea5e9, #6366f1);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:40px 0;
        }

        .page-shell{
            max-width:1180px;
            margin:auto;
        }

        .top-banner{
            background:linear-gradient(135deg, rgba(99,102,241,0.92), rgba(59,130,246,0.88));
            color:white;
            border-radius:30px;
            padding:34px;
            box-shadow:0 24px 60px rgba(99,102,241,0.22);
            margin-bottom:26px;
            position:relative;
            overflow:hidden;
        }

        .top-banner::before{
            content:"";
            position:absolute;
            width:260px;
            height:260px;
            background:rgba(255,255,255,0.10);
            border-radius:50%;
            top:-100px;
            right:-60px;
        }

        .top-banner::after{
            content:"";
            position:absolute;
            width:180px;
            height:180px;
            background:rgba(255,255,255,0.08);
            border-radius:50%;
            bottom:-70px;
            left:-40px;
        }

        .banner-tag{
            display:inline-block;
            background:rgba(255,255,255,0.16);
            border:1px solid rgba(255,255,255,0.22);
            padding:8px 14px;
            border-radius:999px;
            font-size:13px;
            font-weight:600;
            margin-bottom:16px;
        }

        .top-banner h1{
            font-size:34px;
            font-weight:800;
            margin-bottom:8px;
            position:relative;
            z-index:2;
        }

        .top-banner p{
            margin:0;
            max-width:760px;
            color:rgba(255,255,255,0.92);
            line-height:1.7;
            position:relative;
            z-index:2;
        }

        .content-grid{
            display:grid;
            grid-template-columns: 1.08fr 0.92fr;
            gap:24px;
        }

        .glass-card{
            background:rgba(255,255,255,0.72);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.65);
            border-radius:28px;
            box-shadow:0 20px 50px rgba(15,23,42,0.07);
        }

        .left-panel{
            padding:28px;
        }

        .right-panel{
            padding:28px;
        }

        .section-title{
            font-size:20px;
            font-weight:700;
            margin-bottom:18px;
            color:#0f172a;
        }

        .meta-grid{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            gap:16px;
            margin-bottom:22px;
        }

        .meta-box{
            background:linear-gradient(180deg, rgba(255,255,255,0.95), rgba(248,250,252,0.9));
            border:1px solid #e2e8f0;
            border-radius:22px;
            padding:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.04);
        }

        .meta-label{
            font-size:12px;
            color:#64748b;
            text-transform:uppercase;
            letter-spacing:1px;
            margin-bottom:8px;
            font-weight:600;
        }

        .meta-value{
            font-size:18px;
            font-weight:700;
            color:#0f172a;
        }

        .desc-card{
            background:linear-gradient(180deg, rgba(241,245,249,0.95), rgba(255,255,255,0.92));
            border:1px solid #e2e8f0;
            border-radius:24px;
            padding:22px;
        }

        .desc-card p{
            margin:0;
            color:#334155;
            line-height:1.9;
            font-size:15px;
        }

        .upload-area{
            background:linear-gradient(180deg, rgba(248,250,252,0.96), rgba(255,255,255,0.95));
            border:2px dashed #cbd5e1;
            border-radius:24px;
            padding:26px;
            text-align:center;
            margin-bottom:20px;
            transition:0.3s ease;
        }

        .upload-area:hover{
            border-color:#6366f1;
            transform:translateY(-2px);
        }

        .upload-icon{
            width:72px;
            height:72px;
            border-radius:22px;
            background:linear-gradient(135deg, #6366f1, #0ea5e9);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:30px;
            margin:0 auto 16px;
            box-shadow:0 18px 34px rgba(99,102,241,0.18);
        }

        .upload-title{
            font-size:20px;
            font-weight:700;
            color:#0f172a;
            margin-bottom:8px;
        }

        .upload-sub{
            color:#64748b;
            font-size:14px;
            margin-bottom:18px;
        }

        .form-control{
            border-radius:16px;
            padding:14px 16px;
            border:1px solid #dbe2ea;
            background:#fff;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:#6366f1;
        }

        .btn-wrap{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:18px;
        }

        .btn-ultra{
            border:none;
            border-radius:16px;
            padding:13px 22px;
            font-size:15px;
            font-weight:700;
            text-decoration:none;
            transition:0.3s ease;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .btn-primary-pro{
            background:linear-gradient(135deg, #6366f1, #3b82f6);
            color:white;
            box-shadow:0 18px 34px rgba(99,102,241,0.18);
        }

        .btn-primary-pro:hover{
            transform:translateY(-2px);
            color:white;
        }

        .btn-light-pro{
            background:#ffffff;
            color:#334155;
            border:1px solid #e2e8f0;
            box-shadow:0 8px 20px rgba(15,23,42,0.04);
        }

        .btn-light-pro:hover{
            color:#0f172a;
            transform:translateY(-2px);
        }

        .side-stats{
            display:grid;
            gap:14px;
            margin-top:18px;
        }

        .mini-stat{
            background:rgba(255,255,255,0.82);
            border:1px solid #e2e8f0;
            border-radius:20px;
            padding:16px 18px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .mini-stat strong{
            color:#0f172a;
            font-size:16px;
        }

        .mini-stat span{
            color:#64748b;
            font-size:13px;
        }

        .alert{
            border:none;
            border-radius:18px;
            box-shadow:0 12px 28px rgba(15,23,42,0.06);
        }

        .footer-note{
            margin-top:14px;
            color:#64748b;
            font-size:13px;
            line-height:1.7;
        }

        @media(max-width:991px){
            .content-grid{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:576px){
            .top-banner h1{
                font-size:26px;
            }

            .meta-grid{
                grid-template-columns:1fr;
            }

            .left-panel,
            .right-panel{
                padding:20px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-pro">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="brand">
            <div class="brand-logo">L</div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:700; color:#0f172a;"><?php echo $name; ?></div>
                <small style="color:#64748b;">Student Dashboard</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="top-banner">
            <div class="banner-tag">Premium Submission Workspace</div>
            <h1><?php echo $title; ?></h1>
            <p>
                Submit your academic work in a clean, secure, and modern interface designed for a premium LMS experience.
                Review assignment details, verify deadlines, and upload your file professionally.
            </p>
        </div>

        <?php if($message != "") { ?>
            <div class="alert alert-<?php echo $message_type; ?> mb-4">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <div class="content-grid">
            <div class="glass-card left-panel">
                <div class="section-title">Assignment Overview</div>

                <div class="meta-grid">
                    <div class="meta-box">
                        <div class="meta-label">Course</div>
                        <div class="meta-value"><?php echo $course; ?></div>
                    </div>

                    <div class="meta-box">
                        <div class="meta-label">Due Date</div>
                        <div class="meta-value"><?php echo $due_date; ?></div>
                    </div>

                    <div class="meta-box">
                        <div class="meta-label">Student Name</div>
                        <div class="meta-value"><?php echo $name; ?></div>
                    </div>

                    <div class="meta-box">
                        <div class="meta-label">Status</div>
                        <div class="meta-value">Ready to Submit</div>
                    </div>
                </div>

                <div class="desc-card">
                    <div class="section-title" style="font-size:18px; margin-bottom:12px;">Description</div>
                    <p><?php echo $description; ?></p>
                </div>
            </div>

            <div class="glass-card right-panel">
                <div class="section-title">Upload Assignment</div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="upload-area">
                        <div class="upload-icon">↑</div>
                        <div class="upload-title">Choose Your File</div>
                        <div class="upload-sub">Upload your assignment file with a smooth and professional submission flow.</div>
                        <input type="file" name="assignment_file" class="form-control" required>
                    </div>

                    <div class="btn-wrap">
                        <button type="submit" name="submit_assignment" class="btn-ultra btn-primary-pro">
                            Submit Now
                        </button>

                        <a href="/lms_project/assignments.php" class="btn-ultra btn-light-pro">
                            Back
                        </a>
                    </div>
                </form>

                <div class="side-stats">
                    <div class="mini-stat">
                        <div>
                            <strong>Secure Upload</strong><br>
                            <span>Stored in uploads folder</span>
                        </div>
                        <div>🔒</div>
                    </div>

                    <div class="mini-stat">
                        <div>
                            <strong>Fast Access</strong><br>
                            <span>Simple and clean workflow</span>
                        </div>
                        <div>⚡</div>
                    </div>

                    <div class="mini-stat">
                        <div>
                            <strong>Submission Ready</strong><br>
                            <span>File accepted for demo mode</span>
                        </div>
                        <div>📄</div>
                    </div>
                </div>

                <div class="footer-note">
                    Tip: Make sure your file is the final version before submission. This page is styled for a polished, professional academic dashboard look.
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>