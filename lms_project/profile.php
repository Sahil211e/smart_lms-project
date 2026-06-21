<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];

$name = isset($user['name']) && $user['name'] != '' ? htmlspecialchars($user['name']) : 'Student';
$email = isset($user['email']) && $user['email'] != '' ? htmlspecialchars($user['email']) : 'Not Available';
$role = isset($user['role']) && $user['role'] != '' ? htmlspecialchars($user['role']) : 'Student';
$initial = strtoupper(substr($name, 0, 1));

$joined_text = "Active Member";
$completion = 88;
$attendance = 92;
$tasks_done = 11;
$total_tasks = 14;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Smart LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:'Segoe UI',sans-serif;
            min-height:100vh;
            background:
                radial-gradient(circle at top left, rgba(91,92,226,0.18), transparent 24%),
                radial-gradient(circle at top right, rgba(123,124,255,0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(59,130,246,0.10), transparent 22%),
                linear-gradient(135deg, #eef4ff, #f8fafc, #eef2ff);
            color:#0f172a;
        }

        .navbar-pro{
            background:rgba(255,255,255,0.70);
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
            background:linear-gradient(135deg, #5b5ce2, #7b7cff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 18px 34px rgba(91,92,226,0.22);
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

        .chip-avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #5b5ce2, #7b7cff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:36px 0 54px;
        }

        .page-shell{
            max-width:1280px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(91,92,226,0.96), rgba(123,124,255,0.90));
            border-radius:38px;
            padding:40px;
            color:white;
            box-shadow:0 28px 70px rgba(91,92,226,0.18);
            margin-bottom:28px;
        }

        .hero-panel::before{
            content:"";
            position:absolute;
            width:300px;
            height:300px;
            border-radius:50%;
            background:rgba(255,255,255,0.10);
            top:-120px;
            right:-70px;
        }

        .hero-panel::after{
            content:"";
            position:absolute;
            width:210px;
            height:210px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
            bottom:-90px;
            left:-35px;
        }

        .hero-content{
            position:relative;
            z-index:2;
        }

        .hero-tag{
            display:inline-block;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.20);
            border-radius:999px;
            padding:8px 14px;
            font-size:13px;
            font-weight:700;
            margin-bottom:16px;
        }

        .hero-grid{
            display:grid;
            grid-template-columns: 170px 1fr;
            gap:28px;
            align-items:center;
        }

        .hero-avatar-wrap{
            text-align:center;
        }

        .hero-avatar{
            width:140px;
            height:140px;
            border-radius:34px;
            margin:0 auto 14px;
            background:linear-gradient(135deg, rgba(255,255,255,0.24), rgba(255,255,255,0.14));
            border:1px solid rgba(255,255,255,0.24);
            box-shadow:0 18px 34px rgba(0,0,0,0.08);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:48px;
            font-weight:800;
            color:white;
        }

        .hero-role{
            display:inline-block;
            padding:8px 14px;
            border-radius:999px;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.2);
            font-size:12px;
            font-weight:800;
        }

        .hero-title{
            font-size:38px;
            font-weight:800;
            margin:0 0 8px;
        }

        .hero-sub{
            max-width:720px;
            color:rgba(255,255,255,0.93);
            line-height:1.85;
            font-size:15px;
            margin-bottom:20px;
        }

        .hero-chips{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .hero-chip{
            padding:10px 14px;
            border-radius:999px;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.18);
            font-size:13px;
            font-weight:700;
        }

        .grid-layout{
            display:grid;
            grid-template-columns: 360px 1fr;
            gap:22px;
        }

        .glass-card{
            background:rgba(255,255,255,0.78);
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
            margin-bottom:20px;
            flex-wrap:wrap;
        }

        .section-head h4,
        .section-head h5{
            margin:0;
            font-weight:800;
            color:#0f172a;
        }

        .section-head span{
            color:#94a3b8;
            font-size:12px;
            font-weight:700;
        }

        .profile-card{
            text-align:center;
        }

        .profile-avatar{
            width:112px;
            height:112px;
            border-radius:30px;
            margin:0 auto 16px;
            background:linear-gradient(135deg, #5b5ce2, #7b7cff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:36px;
            font-weight:800;
            box-shadow:0 18px 34px rgba(91,92,226,0.20);
        }

        .profile-card h3{
            margin:0 0 6px;
            font-size:28px;
            font-weight:800;
        }

        .profile-card p{
            color:#64748b;
            margin-bottom:18px;
            font-size:14px;
        }

        .mini-badge{
            display:inline-block;
            padding:8px 14px;
            border-radius:999px;
            background:#eef2ff;
            color:#5b5ce2;
            font-weight:800;
            font-size:12px;
            border:1px solid #dbeafe;
            margin-bottom:20px;
        }

        .info-list{
            display:grid;
            gap:12px;
            text-align:left;
        }

        .info-item{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:18px;
            padding:14px 16px;
        }

        .info-item label{
            display:block;
            font-size:12px;
            color:#64748b;
            text-transform:uppercase;
            letter-spacing:0.8px;
            margin-bottom:4px;
            font-weight:700;
        }

        .info-item strong{
            font-size:15px;
            color:#0f172a;
            word-break:break-word;
        }

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:16px;
            margin-bottom:20px;
        }

        .stat-box{
            padding:18px;
            border-radius:22px;
            background:linear-gradient(180deg, rgba(255,255,255,0.94), rgba(248,250,252,0.95));
            border:1px solid #e2e8f0;
            box-shadow:0 10px 28px rgba(15,23,42,0.04);
        }

        .stat-box small{
            color:#64748b;
            font-weight:700;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:0.8px;
        }

        .stat-box h3{
            margin:10px 0 4px;
            font-size:30px;
            font-weight:800;
            color:#0f172a;
        }

        .stat-box p{
            margin:0;
            font-size:13px;
            color:#64748b;
        }

        .progress-card{
            margin-bottom:20px;
        }

        .progress-row{
            margin-bottom:18px;
        }

        .progress-row:last-child{
            margin-bottom:0;
        }

        .progress-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:8px;
            gap:10px;
        }

        .progress-top strong{
            font-size:14px;
            color:#0f172a;
        }

        .progress-top span{
            font-size:13px;
            color:#5b5ce2;
            font-weight:800;
        }

        .custom-progress{
            height:10px;
            border-radius:999px;
            background:#e2e8f0;
            overflow:hidden;
        }

        .custom-progress-bar{
            height:100%;
            border-radius:999px;
            background:linear-gradient(90deg, #5b5ce2, #7b7cff);
        }

        .activity-list{
            display:grid;
            gap:14px;
        }

        .activity-item{
            display:flex;
            gap:12px;
            align-items:flex-start;
            padding:14px;
            border-radius:18px;
            background:#f8fafc;
            border:1px solid #e2e8f0;
        }

        .activity-icon{
            width:42px;
            height:42px;
            border-radius:14px;
            background:#eef2ff;
            color:#5b5ce2;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
            flex-shrink:0;
        }

        .activity-item h6{
            margin:0 0 4px;
            font-size:14px;
            font-weight:800;
            color:#0f172a;
        }

        .activity-item p{
            margin:0;
            color:#64748b;
            font-size:13px;
        }

        .action-row{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:22px;
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
            background:linear-gradient(135deg, #5b5ce2, #7b7cff);
            color:white;
            box-shadow:0 16px 30px rgba(91,92,226,0.16);
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

        @media(max-width:1100px){
            .grid-layout{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:900px){
            .hero-grid{
                grid-template-columns:1fr;
                text-align:center;
            }

            .hero-sub{
                margin-left:auto;
                margin-right:auto;
            }

            .hero-chips{
                justify-content:center;
            }
        }

        @media(max-width:768px){
            .hero-title{
                font-size:30px;
            }

            .stats-grid{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-pro">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="brand">
            <div class="brand-logo"><?php echo $initial; ?></div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo $name; ?></div>
                <small style="color:#64748b;">Profile Center</small>
            </div>
            <div class="chip-avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-content">
                <div class="hero-tag">Student Identity Workspace</div>

                <div class="hero-grid">
                    <div class="hero-avatar-wrap">
                        <div class="hero-avatar"><?php echo $initial; ?></div>
                        <div class="hero-role"><?php echo ucfirst($role); ?></div>
                    </div>

                    <div>
                        <h1 class="hero-title"><?php echo $name; ?></h1>
                        <p class="hero-sub">
                            Welcome to your premium student profile. Review your personal details, academic performance,
                            activity highlights, and progress insights in one modern professional dashboard.
                        </p>

                        <div class="hero-chips">
                            <div class="hero-chip">Email Verified</div>
                            <div class="hero-chip"><?php echo ucfirst($role); ?> Account</div>
                            <div class="hero-chip"><?php echo $joined_text; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid-layout">
            <div>
                <div class="glass-card profile-card">
                    <div class="section-head">
                        <h4>Profile Card</h4>
                        <span>Student Identity</span>
                    </div>

                    <div class="profile-avatar"><?php echo $initial; ?></div>
                    <h3><?php echo $name; ?></h3>
                    <p>Smart LMS academic member with active progress tracking and premium access.</p>
                    <div class="mini-badge"><?php echo ucfirst($role); ?> Account</div>

                    <div class="info-list">
                        <div class="info-item">
                            <label>Full Name</label>
                            <strong><?php echo $name; ?></strong>
                        </div>

                        <div class="info-item">
                            <label>Email Address</label>
                            <strong><?php echo $email; ?></strong>
                        </div>

                        <div class="info-item">
                            <label>Role</label>
                            <strong><?php echo ucfirst($role); ?></strong>
                        </div>
                    </div>

                    <div class="action-row justify-content-center">
                        <a href="/lms_project/dashboard.php" class="btn-pro btn-primary-pro">Back to Dashboard</a>
                    </div>
                </div>
            </div>

            <div>
                <div class="stats-grid">
                    <div class="stat-box">
                        <small>Profile Completion</small>
                        <h3><?php echo $completion; ?>%</h3>
                        <p>Strong academic account setup</p>
                    </div>

                    <div class="stat-box">
                        <small>Attendance</small>
                        <h3><?php echo $attendance; ?>%</h3>
                        <p>Excellent consistency maintained</p>
                    </div>

                    <div class="stat-box">
                        <small>Tasks Finished</small>
                        <h3><?php echo $tasks_done; ?>/<?php echo $total_tasks; ?></h3>
                        <p>Daily workflow progress</p>
                    </div>
                </div>

                <div class="glass-card progress-card">
                    <div class="section-head">
                        <h5>Progress Snapshot</h5>
                        <span>Academic Overview</span>
                    </div>

                    <div class="progress-row">
                        <div class="progress-top">
                            <strong>Profile Completion</strong>
                            <span><?php echo $completion; ?>%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar" style="width: <?php echo $completion; ?>%;"></div>
                        </div>
                    </div>

                    <div class="progress-row">
                        <div class="progress-top">
                            <strong>Attendance Strength</strong>
                            <span><?php echo $attendance; ?>%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar" style="width: <?php echo $attendance; ?>%;"></div>
                        </div>
                    </div>

                    <div class="progress-row">
                        <div class="progress-top">
                            <strong>Task Completion</strong>
                            <span><?php echo round(($tasks_done / $total_tasks) * 100); ?>%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar" style="width: <?php echo round(($tasks_done / $total_tasks) * 100); ?>%;"></div>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <div class="section-head">
                        <h5>Recent Highlights</h5>
                        <span>Student Activity</span>
                    </div>

                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">📘</div>
                            <div>
                                <h6>Profile Activated</h6>
                                <p>Your student account is active and ready for academic tracking.</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">📊</div>
                            <div>
                                <h6>Performance Stable</h6>
                                <p>Your overall academic metrics show a strong and consistent record.</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">✅</div>
                            <div>
                                <h6>Course Progress Updated</h6>
                                <p>Your latest submissions, attendance, and result performance are aligned well.</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">🚀</div>
                            <div>
                                <h6>Premium Access Enabled</h6>
                                <p>Student Pro Tools, quiz features, and advanced LMS modules are accessible.</p>
                            </div>
                        </div>
                    </div>

                    <div class="action-row">
                        <a href="/lms_project/settings.php" class="btn-pro btn-light-pro">Open Settings</a>
                        <a href="/lms_project/certificate.php" class="btn-pro btn-light-pro">View Certificate</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>