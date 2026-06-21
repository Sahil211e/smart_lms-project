<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];
$name = isset($user['name']) ? $user['name'] : 'Teacher';
$initial = strtoupper(substr($name, 0, 1));

$query = "SELECT * FROM submissions ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$total_submissions = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Panel | Smart LMS</title>
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
                radial-gradient(circle at top left, rgba(59,130,246,0.16), transparent 24%),
                radial-gradient(circle at top right, rgba(124,58,237,0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(14,165,233,0.10), transparent 22%),
                linear-gradient(135deg, #eef4ff, #f8fafc, #eef2ff);
            color:#0f172a;
        }

        .navbar-pro{
            background:rgba(255,255,255,0.68);
            backdrop-filter:blur(16px);
            border-bottom:1px solid rgba(255,255,255,0.72);
            box-shadow:0 10px 30px rgba(15,23,42,0.05);
            padding:16px 0;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            font-size:22px;
            font-weight:800;
            color:#111827;
        }

        .brand-logo{
            width:52px;
            height:52px;
            border-radius:18px;
            background:linear-gradient(135deg, #4f46e5, #3b82f6);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 18px 34px rgba(79,70,229,0.22);
        }

        .profile-chip{
            display:flex;
            align-items:center;
            gap:12px;
            background:rgba(255,255,255,0.82);
            border:1px solid rgba(255,255,255,0.68);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.05);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #4f46e5, #6366f1);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:36px 0 52px;
        }

        .page-shell{
            max-width:1280px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(79,70,229,0.95), rgba(59,130,246,0.88));
            border-radius:38px;
            padding:40px;
            color:white;
            box-shadow:0 28px 70px rgba(79,70,229,0.18);
            margin-bottom:28px;
        }

        .hero-panel::before{
            content:"";
            position:absolute;
            width:290px;
            height:290px;
            border-radius:50%;
            background:rgba(255,255,255,0.11);
            top:-120px;
            right:-70px;
        }

        .hero-panel::after{
            content:"";
            position:absolute;
            width:200px;
            height:200px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
            bottom:-85px;
            left:-35px;
        }

        .hero-tag{
            display:inline-block;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.22);
            border-radius:999px;
            padding:8px 14px;
            font-size:13px;
            font-weight:700;
            margin-bottom:16px;
            position:relative;
            z-index:2;
        }

        .hero-title{
            font-size:38px;
            font-weight:800;
            margin-bottom:10px;
            position:relative;
            z-index:2;
        }

        .hero-sub{
            max-width:780px;
            color:rgba(255,255,255,0.93);
            line-height:1.85;
            font-size:15px;
            position:relative;
            z-index:2;
            margin-bottom:22px;
        }

        .hero-summary{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            position:relative;
            z-index:2;
        }

        .hero-chip{
            padding:10px 14px;
            border-radius:999px;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.18);
            font-size:13px;
            font-weight:700;
        }

        .glass-card{
            background:rgba(255,255,255,0.76);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.74);
            border-radius:28px;
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
            padding:24px;
        }

        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            margin-bottom:22px;
            flex-wrap:wrap;
        }

        .section-head h3{
            margin:0;
            font-size:25px;
            font-weight:800;
            color:#0f172a;
        }

        .section-head p{
            margin:5px 0 0;
            color:#64748b;
            font-size:14px;
        }

        .btn-pro{
            border:none;
            border-radius:16px;
            padding:12px 20px;
            font-size:14px;
            font-weight:700;
            text-decoration:none;
            transition:0.3s ease;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .btn-primary-pro{
            background:linear-gradient(135deg, #4f46e5, #3b82f6);
            color:white;
            box-shadow:0 16px 30px rgba(79,70,229,0.16);
        }

        .btn-primary-pro:hover{
            color:white;
            transform:translateY(-2px);
        }

        .btn-light-pro{
            background:#ffffff;
            color:#334155;
            border:1px solid #e2e8f0;
        }

        .btn-light-pro:hover{
            color:#0f172a;
            transform:translateY(-2px);
        }

        .table-shell{
            overflow:hidden;
            border-radius:24px;
            border:1px solid #e5e7eb;
            background:rgba(255,255,255,0.88);
        }

        .table{
            margin:0;
        }

        .table thead{
            background:linear-gradient(135deg, #eff6ff, #eef2ff);
        }

        .table thead th{
            padding:18px 16px;
            border:none;
            color:#475569;
            font-size:13px;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:0.8px;
        }

        .table tbody td{
            padding:18px 16px;
            vertical-align:middle;
            border-top:1px solid #eef2f7;
            font-size:14px;
            color:#1e293b;
        }

        .table tbody tr:hover{
            background:rgba(79,70,229,0.04);
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:linear-gradient(135deg, #eff6ff, #eef2ff);
            color:#3730a3;
            border:1px solid #dbeafe;
            border-radius:999px;
            padding:8px 14px;
            font-weight:800;
            font-size:13px;
        }

        .file-pill{
            display:inline-block;
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:14px;
            padding:8px 12px;
            font-weight:700;
            color:#0f172a;
        }

        .edit-btn{
            border:none;
            border-radius:12px;
            padding:8px 14px;
            font-size:13px;
            font-weight:700;
            background:linear-gradient(135deg, #4f46e5, #3b82f6);
            color:white;
            text-decoration:none;
            display:inline-block;
        }

        .edit-btn:hover{
            color:white;
            opacity:0.95;
        }

        .empty-state{
            text-align:center;
            padding:54px 20px;
        }

        .empty-icon{
            width:88px;
            height:88px;
            border-radius:26px;
            margin:0 auto 18px;
            background:linear-gradient(135deg, #4f46e5, #3b82f6);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:36px;
            box-shadow:0 18px 34px rgba(79,70,229,0.18);
        }

        .empty-state h4{
            font-weight:800;
            color:#0f172a;
            margin-bottom:10px;
        }

        .empty-state p{
            color:#64748b;
            max-width:520px;
            margin:0 auto 20px;
            line-height:1.8;
        }

        @media(max-width:768px){
            .hero-title{
                font-size:28px;
            }

            .section-head{
                align-items:flex-start;
                flex-direction:column;
            }

            .table thead{
                display:none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td{
                display:block;
                width:100%;
            }

            .table tbody tr{
                padding:12px;
                border-bottom:1px solid #eef2f7;
            }

            .table tbody td{
                border:none;
                padding:10px 8px;
            }

            .table tbody td::before{
                content:attr(data-label);
                display:block;
                font-size:12px;
                font-weight:800;
                color:#64748b;
                text-transform:uppercase;
                margin-bottom:4px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-pro">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="brand">
            <div class="brand-logo">T</div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo htmlspecialchars($name); ?></div>
                <small style="color:#64748b;">Teacher Panel</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">Submission Review Workspace</div>
            <div class="hero-title">Teacher Panel</div>
            <p class="hero-sub">
                Manage and review all student submissions in one professional dashboard.
                Track uploaded files, submission time, and organize academic workflow with a clean interface.
            </p>

            <div class="hero-summary">
                <div class="hero-chip">Total Submissions: <?php echo $total_submissions; ?></div>
                <div class="hero-chip">Panel Access: Active</div>
                <div class="hero-chip">Role: Teacher</div>
            </div>
        </div>

        <div class="glass-card">
            <div class="section-head">
                <div>
                    <h3>All Student Submissions</h3>
                    <p>Live list of uploaded assignments by students.</p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Back to Dashboard</a>
                    <a href="/lms_project/results.php" class="btn-pro btn-primary-pro">View Results</a>
                </div>
            </div>

            <?php if ($total_submissions > 0) { ?>
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Assignment ID</th>
                                    <th>File Name</th>
                                    <th>Submitted At</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td data-label="Student">
                                            <span class="pill">👤 <?php echo htmlspecialchars($row['student_name']); ?></span>
                                        </td>

                                        <td data-label="Assignment ID">
                                            <span class="file-pill">#<?php echo htmlspecialchars($row['assignment_id']); ?></span>
                                        </td>

                                        <td data-label="File Name">
                                            <span class="file-pill"><?php echo htmlspecialchars($row['file_name']); ?></span>
                                        </td>

                                        <td data-label="Submitted At">
                                            <?php echo htmlspecialchars($row['submitted_at']); ?>
                                        </td>

                                        <td data-label="Marks">
                                            <span class="file-pill"><?php echo htmlspecialchars($row['marks']); ?></span>
                                        </td>

                                        <td data-label="Status">
                                            <span class="pill"><?php echo htmlspecialchars($row['status']); ?></span>
                                        </td>

                                        <td data-label="Action">
                                            <a href="/lms_project/edit_submission.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">📂</div>
                    <h4>No submissions found</h4>
                    <p>
                        Student submissions will appear here once assignments are uploaded into the system.
                    </p>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-primary-pro">Go to Dashboard</a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>