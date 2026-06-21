<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];
$name = isset($user['name']) ? $user['name'] : 'Student';
$safe_name = mysqli_real_escape_string($conn, $name);
$initial = strtoupper(substr($name, 0, 1));

$query = "SELECT * FROM submissions WHERE student_name = '$safe_name' ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);
$total_submissions = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions | Smart LMS</title>
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
                radial-gradient(circle at top left, rgba(126, 34, 206, 0.16), transparent 28%),
                radial-gradient(circle at top right, rgba(6, 182, 212, 0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(244, 114, 182, 0.12), transparent 26%),
                linear-gradient(135deg, #edf4ff, #f8fafc, #eef2ff);
            color:#0f172a;
        }

        .navbar-pro{
            background:rgba(255,255,255,0.62);
            backdrop-filter:blur(16px);
            border-bottom:1px solid rgba(255,255,255,0.65);
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
            width:48px;
            height:48px;
            border-radius:16px;
            background:linear-gradient(135deg, #7c3aed, #06b6d4);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 18px 34px rgba(124,58,237,0.22);
        }

        .profile-chip{
            display:flex;
            align-items:center;
            gap:12px;
            background:rgba(255,255,255,0.75);
            border:1px solid rgba(255,255,255,0.65);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.05);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #0f766e, #06b6d4);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:38px 0 50px;
        }

        .page-shell{
            max-width:1240px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(124,58,237,0.94), rgba(14,165,233,0.88));
            border-radius:34px;
            padding:34px;
            color:white;
            box-shadow:0 24px 60px rgba(99,102,241,0.20);
            margin-bottom:26px;
        }

        .hero-panel::before{
            content:"";
            position:absolute;
            width:260px;
            height:260px;
            border-radius:50%;
            background:rgba(255,255,255,0.10);
            top:-100px;
            right:-60px;
        }

        .hero-panel::after{
            content:"";
            position:absolute;
            width:180px;
            height:180px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
            bottom:-80px;
            left:-30px;
        }

        .hero-tag{
            display:inline-block;
            background:rgba(255,255,255,0.16);
            border:1px solid rgba(255,255,255,0.25);
            border-radius:999px;
            padding:8px 14px;
            font-size:13px;
            font-weight:600;
            margin-bottom:16px;
            position:relative;
            z-index:2;
        }

        .hero-title{
            font-size:34px;
            font-weight:800;
            margin-bottom:8px;
            position:relative;
            z-index:2;
        }

        .hero-sub{
            max-width:760px;
            color:rgba(255,255,255,0.92);
            line-height:1.8;
            font-size:15px;
            position:relative;
            z-index:2;
            margin-bottom:0;
        }

        .stats-grid{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:18px;
            margin-bottom:24px;
        }

        .glass-card{
            background:rgba(255,255,255,0.72);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.7);
            border-radius:28px;
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
        }

        .stat-card{
            padding:22px;
        }

        .stat-label{
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:1px;
            color:#64748b;
            font-weight:700;
            margin-bottom:10px;
        }

        .stat-value{
            font-size:32px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:8px;
        }

        .stat-note{
            color:#475569;
            font-size:14px;
        }

        .content-card{
            padding:24px;
        }

        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            margin-bottom:20px;
            flex-wrap:wrap;
        }

        .section-head h3{
            margin:0;
            font-size:24px;
            font-weight:800;
            color:#0f172a;
        }

        .section-head p{
            margin:4px 0 0;
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
            background:linear-gradient(135deg, #7c3aed, #06b6d4);
            color:white;
            box-shadow:0 16px 30px rgba(124,58,237,0.16);
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
            border-radius:22px;
            border:1px solid #e2e8f0;
            background:rgba(255,255,255,0.75);
        }

        .table{
            margin:0;
        }

        .table thead{
            background:linear-gradient(135deg, #f5f3ff, #ecfeff);
        }

        .table thead th{
            padding:18px 16px;
            border:none;
            color:#475569;
            font-size:13px;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:0.7px;
        }

        .table tbody tr{
            transition:0.25s ease;
        }

        .table tbody tr:hover{
            background:rgba(124,58,237,0.04);
        }

        .table tbody td{
            padding:18px 16px;
            vertical-align:middle;
            border-top:1px solid #eef2f7;
            color:#1e293b;
            font-size:14px;
        }

        .file-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:linear-gradient(135deg, #f5f3ff, #ecfeff);
            color:#334155;
            border:1px solid #e2e8f0;
            border-radius:999px;
            padding:8px 14px;
            font-weight:700;
            font-size:13px;
        }

        .status-badge{
            display:inline-block;
            padding:8px 14px;
            border-radius:999px;
            font-size:12px;
            font-weight:800;
            background:linear-gradient(135deg, #dcfce7, #ecfeff);
            color:#0f766e;
            border:1px solid #bbf7d0;
        }

        .id-chip{
            display:inline-block;
            min-width:44px;
            text-align:center;
            padding:8px 12px;
            border-radius:12px;
            background:linear-gradient(135deg, #ede9fe, #dbeafe);
            color:#4338ca;
            font-weight:800;
            font-size:13px;
        }

        .empty-state{
            text-align:center;
            padding:50px 20px;
        }

        .empty-icon{
            width:84px;
            height:84px;
            border-radius:24px;
            margin:0 auto 18px;
            background:linear-gradient(135deg, #7c3aed, #06b6d4);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:34px;
            box-shadow:0 18px 34px rgba(124,58,237,0.18);
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

        .footer-note{
            margin-top:16px;
            color:#64748b;
            font-size:13px;
            line-height:1.8;
        }

        @media(max-width:991px){
            .stats-grid{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:768px){
            .hero-title{
                font-size:26px;
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
            <div class="brand-logo">L</div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo htmlspecialchars($name); ?></div>
                <small style="color:#64748b;">Submission Center</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">Student Submission History</div>
            <div class="hero-title">My Submitted Assignments</div>
            <p class="hero-sub">
                Review all your submitted assignment records in a premium academic dashboard. Track uploaded files,
                submission dates, and maintain a clean professional view of your LMS progress.
            </p>
        </div>

        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-label">Total Submissions</div>
                <div class="stat-value"><?php echo $total_submissions; ?></div>
                <div class="stat-note">Assignments uploaded by you</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Student Name</div>
                <div class="stat-value" style="font-size:24px;"><?php echo htmlspecialchars($name); ?></div>
                <div class="stat-note">Active student account</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Status</div>
                <div class="stat-value" style="font-size:24px;">Organized</div>
                <div class="stat-note">Submission records available</div>
            </div>
        </div>

        <div class="glass-card content-card">
            <div class="section-head">
                <div>
                    <h3>Submission Records</h3>
                    <p>Premium view of your uploaded assignment history.</p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/assignments.php" class="btn-pro btn-primary-pro">New Submission</a>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Back to Dashboard</a>
                </div>
            </div>

            <?php if ($total_submissions > 0) { ?>
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Assignment ID</th>
                                    <th>File Name</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td data-label="Assignment ID">
                                            <span class="id-chip">#<?php echo $row['assignment_id']; ?></span>
                                        </td>

                                        <td data-label="File Name">
                                            <span class="file-pill">📄 <?php echo htmlspecialchars($row['file_name']); ?></span>
                                        </td>

                                        <td data-label="Status">
                                            <span class="status-badge">Submitted</span>
                                        </td>

                                        <td data-label="Submitted At">
                                            <?php echo htmlspecialchars($row['submitted_at']); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="footer-note">
                    Your submitted files are displayed in a clean academic timeline. You can continue submitting new assignments from the Assignments page.
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">↑</div>
                    <h4>No submissions yet</h4>
                    <p>
                        You have not uploaded any assignment yet. Start from the assignments section and your submissions
                        will appear here in this premium history dashboard.
                    </p>
                    <a href="/lms_project/assignments.php" class="btn-pro btn-primary-pro">Go to Assignments</a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>