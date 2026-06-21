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

$query = "SELECT * FROM attendance WHERE student_name = '$safe_name' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$total_subjects = mysqli_num_rows($result);
$total_classes_all = 0;
$total_attended_all = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_classes_all += (int)$row['total_classes'];
    $total_attended_all += (int)$row['attended_classes'];
}

mysqli_data_seek($result, 0);

$overall_percentage = 0;
if ($total_classes_all > 0) {
    $overall_percentage = round(($total_attended_all / $total_classes_all) * 100);
}

$total_absent = $total_classes_all - $total_attended_all;

function getAttendanceStatus($percentage) {
    if ($percentage >= 90) return 'Exceptional';
    if ($percentage >= 80) return 'Excellent';
    if ($percentage >= 70) return 'Consistent';
    if ($percentage >= 60) return 'Moderate';
    return 'Low';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance | Smart LMS</title>
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
                radial-gradient(circle at top left, rgba(99,102,241,0.18), transparent 24%),
                radial-gradient(circle at top right, rgba(59,130,246,0.14), transparent 24%),
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

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:18px;
            margin-bottom:24px;
        }

        .glass-card{
            background:rgba(255,255,255,0.76);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.74);
            border-radius:28px;
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
        }

        .stat-card{
            padding:22px;
            position:relative;
            overflow:hidden;
        }

        .stat-card::before{
            content:"";
            position:absolute;
            inset:0 0 auto 0;
            height:5px;
            background:linear-gradient(90deg, #4f46e5, #3b82f6, #06b6d4);
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
            font-size:31px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:8px;
        }

        .stat-note{
            color:#475569;
            font-size:14px;
        }

        .section-card{
            padding:24px;
        }

        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            margin-bottom:24px;
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

        .attendance-layout{
            display:grid;
            grid-template-columns: 330px 1fr;
            gap:22px;
        }

        .overview-panel{
            padding:22px;
            border-radius:30px;
            background:linear-gradient(180deg, rgba(79,70,229,0.05), rgba(255,255,255,0.92));
            border:1px solid #e5e7eb;
        }

        .ring-wrap{
            display:flex;
            justify-content:center;
            margin:10px 0 20px;
        }

        .ring{
            width:180px;
            height:180px;
            border-radius:50%;
            background:conic-gradient(#4f46e5 0% <?php echo $overall_percentage; ?>%, #dbeafe <?php echo $overall_percentage; ?>% 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 18px 36px rgba(79,70,229,0.12);
        }

        .ring-inner{
            width:132px;
            height:132px;
            border-radius:50%;
            background:white;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            box-shadow:inset 0 0 0 1px #edf2f7;
        }

        .ring-inner h2{
            margin:0;
            font-size:34px;
            font-weight:800;
            color:#0f172a;
        }

        .ring-inner span{
            color:#64748b;
            font-size:13px;
            font-weight:700;
        }

        .overview-list{
            display:grid;
            gap:12px;
        }

        .overview-item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:14px 16px;
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:18px;
        }

        .overview-item span{
            color:#64748b;
            font-size:13px;
            font-weight:700;
        }

        .overview-item strong{
            color:#0f172a;
            font-size:16px;
        }

        .attendance-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(290px, 1fr));
            gap:20px;
        }

        .attendance-card{
            position:relative;
            overflow:hidden;
            padding:22px;
            border-radius:28px;
            background:rgba(255,255,255,0.88);
            border:1px solid rgba(255,255,255,0.82);
            box-shadow:0 18px 40px rgba(15,23,42,0.06);
            transition:0.35s ease;
        }

        .attendance-card:hover{
            transform:translateY(-7px);
            box-shadow:0 28px 56px rgba(15,23,42,0.10);
        }

        .attendance-card::before{
            content:"";
            position:absolute;
            inset:0 0 auto 0;
            height:6px;
            background:linear-gradient(90deg, #4f46e5, #3b82f6, #06b6d4);
        }

        .card-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:12px;
            margin-bottom:14px;
        }

        .subject-icon{
            width:56px;
            height:56px;
            border-radius:18px;
            background:linear-gradient(135deg, #e0e7ff, #dbeafe);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            box-shadow:0 10px 24px rgba(79,70,229,0.10);
        }

        .subject-badge{
            display:inline-block;
            background:linear-gradient(135deg, #eff6ff, #eef2ff);
            color:#3730a3;
            border:1px solid #dbeafe;
            border-radius:999px;
            padding:7px 12px;
            font-size:12px;
            font-weight:800;
        }

        .subject-title{
            font-size:22px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:14px;
            line-height:1.35;
        }

        .mini-grid{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            gap:12px;
            margin-bottom:16px;
        }

        .mini-box{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:12px;
        }

        .mini-box-label{
            font-size:12px;
            color:#64748b;
            text-transform:uppercase;
            letter-spacing:0.8px;
            margin-bottom:6px;
            font-weight:700;
        }

        .mini-box-value{
            font-size:18px;
            font-weight:800;
            color:#0f172a;
        }

        .progress-shell{
            margin-top:14px;
        }

        .progress-label{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:8px;
            font-size:13px;
            color:#475569;
            font-weight:700;
        }

        .progress{
            height:11px;
            border-radius:999px;
            background:#e2e8f0;
            overflow:hidden;
        }

        .progress-bar{
            border-radius:999px;
            background:linear-gradient(90deg, #4f46e5, #3b82f6, #06b6d4);
        }

        .status-row{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            margin-top:18px;
            flex-wrap:wrap;
        }

        .status-badge{
            display:inline-block;
            padding:8px 14px;
            border-radius:999px;
            font-size:12px;
            font-weight:800;
        }

        .status-good{
            background:linear-gradient(135deg, #dbeafe, #e0e7ff);
            color:#1d4ed8;
            border:1px solid #bfdbfe;
        }

        .status-average{
            background:linear-gradient(135deg, #ede9fe, #e0f2fe);
            color:#6d28d9;
            border:1px solid #ddd6fe;
        }

        .status-critical{
            background:linear-gradient(135deg, #fee2e2, #ffe4e6);
            color:#b91c1c;
            border:1px solid #fecaca;
        }

        .status-score{
            font-size:18px;
            font-weight:800;
            color:#0f172a;
        }

        .footer-note{
            margin-top:18px;
            color:#64748b;
            font-size:13px;
            line-height:1.8;
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

        @media(max-width:1100px){
            .attendance-layout{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:991px){
            .stats-grid{
                grid-template-columns:1fr 1fr;
            }
        }

        @media(max-width:768px){
            .hero-title{
                font-size:28px;
            }

            .section-head{
                align-items:flex-start;
                flex-direction:column;
            }

            .stats-grid{
                grid-template-columns:1fr;
            }

            .subject-title{
                font-size:20px;
            }

            .mini-grid{
                grid-template-columns:1fr;
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
                <small style="color:#64748b;">Attendance Center</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">Blue Premium Attendance Workspace</div>
            <div class="hero-title">My Attendance</div>
            <p class="hero-sub">
                Experience your attendance report in a cleaner, sharper, and more professional academic layout.
                Review your class presence, identify gaps, and keep your performance consistent through a modern dashboard design.
            </p>

            <div class="hero-summary">
                <div class="hero-chip">Subjects: <?php echo $total_subjects; ?></div>
                <div class="hero-chip">Present: <?php echo $total_attended_all; ?></div>
                <div class="hero-chip">Absent: <?php echo $total_absent; ?></div>
                <div class="hero-chip">Overall: <?php echo $overall_percentage; ?>%</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-label">Total Subjects</div>
                <div class="stat-value"><?php echo $total_subjects; ?></div>
                <div class="stat-note">Attendance records available</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Total Classes</div>
                <div class="stat-value"><?php echo $total_classes_all; ?></div>
                <div class="stat-note">Combined scheduled sessions</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Present Classes</div>
                <div class="stat-value"><?php echo $total_attended_all; ?></div>
                <div class="stat-note">Successfully attended sessions</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Overall Attendance</div>
                <div class="stat-value"><?php echo $overall_percentage; ?>%</div>
                <div class="stat-note"><?php echo getAttendanceStatus($overall_percentage); ?></div>
            </div>
        </div>

        <div class="glass-card section-card">
            <div class="section-head">
                <div>
                    <h3>Attendance Performance Overview</h3>
                    <p>Unique premium summary of your classroom consistency.</p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Back to Dashboard</a>
                    <a href="/lms_project/courses.php" class="btn-pro btn-primary-pro">View Courses</a>
                </div>
            </div>

            <?php if ($total_subjects > 0) { ?>
                <div class="attendance-layout">

                    <div class="overview-panel">
                        <div class="ring-wrap">
                            <div class="ring">
                                <div class="ring-inner">
                                    <h2><?php echo $overall_percentage; ?>%</h2>
                                    <span>Overall Score</span>
                                </div>
                            </div>
                        </div>

                        <div class="overview-list">
                            <div class="overview-item">
                                <span>Attendance Status</span>
                                <strong><?php echo getAttendanceStatus($overall_percentage); ?></strong>
                            </div>

                            <div class="overview-item">
                                <span>Total Present</span>
                                <strong><?php echo $total_attended_all; ?></strong>
                            </div>

                            <div class="overview-item">
                                <span>Total Absent</span>
                                <strong><?php echo $total_absent; ?></strong>
                            </div>

                            <div class="overview-item">
                                <span>Total Classes</span>
                                <strong><?php echo $total_classes_all; ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="attendance-grid">
                        <?php
                        $icons = ['📘','💻','🌐','🗄️','📊','🧠','⚙️','🔒'];
                        $i = 0;
                        while($row = mysqli_fetch_assoc($result)) {
                            $subject = htmlspecialchars($row['subject_name']);
                            $total = (int)$row['total_classes'];
                            $attended = (int)$row['attended_classes'];
                            $absent = $total - $attended;
                            $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;

                            $statusClass = 'status-critical';
                            if ($percentage >= 80) {
                                $statusClass = 'status-good';
                            } elseif ($percentage >= 60) {
                                $statusClass = 'status-average';
                            }

                            $icon = $icons[$i % count($icons)];
                            $i++;
                        ?>
                            <div class="attendance-card">
                                <div class="card-top">
                                    <div class="subject-icon"><?php echo $icon; ?></div>
                                    <span class="subject-badge"><?php echo getAttendanceStatus($percentage); ?></span>
                                </div>

                                <div class="subject-title"><?php echo $subject; ?></div>

                                <div class="mini-grid">
                                    <div class="mini-box">
                                        <div class="mini-box-label">Total Classes</div>
                                        <div class="mini-box-value"><?php echo $total; ?></div>
                                    </div>

                                    <div class="mini-box">
                                        <div class="mini-box-label">Present</div>
                                        <div class="mini-box-value"><?php echo $attended; ?></div>
                                    </div>

                                    <div class="mini-box">
                                        <div class="mini-box-label">Absent</div>
                                        <div class="mini-box-value"><?php echo $absent; ?></div>
                                    </div>

                                    <div class="mini-box">
                                        <div class="mini-box-label">Percent</div>
                                        <div class="mini-box-value"><?php echo $percentage; ?>%</div>
                                    </div>
                                </div>

                                <div class="progress-shell">
                                    <div class="progress-label">
                                        <span>Attendance Progress</span>
                                        <span><?php echo $percentage; ?>%</span>
                                    </div>

                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                </div>

                                <div class="status-row">
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <?php echo $percentage >= 80 ? 'Well Maintained' : ($percentage >= 60 ? 'Needs Review' : 'Low Attendance'); ?>
                                    </span>

                                    <div class="status-score"><?php echo $percentage; ?>%</div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <div class="footer-note">
                    This blue premium attendance page is designed for a cleaner and more advanced LMS presentation with better visual consistency, smoother fade colors, and a more professional academic layout.
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h4>No attendance records found</h4>
                    <p>
                        Attendance data is not available yet for your account. Once records are added to the database, they will appear here automatically in this premium dashboard.
                    </p>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-primary-pro">Go to Dashboard</a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>