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

$query = "SELECT * FROM results WHERE student_name = '$safe_name' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$total_subjects = mysqli_num_rows($result);
$total_obtained = 0;
$total_max = 0;
$top_subject = 'N/A';
$top_subject_percent = -1;

while ($row = mysqli_fetch_assoc($result)) {
    $obt = (int)$row['marks_obtained'];
    $tot = (int)$row['total_marks'];

    $total_obtained += $obt;
    $total_max += $tot;

    $subject_percent = $tot > 0 ? round(($obt / $tot) * 100) : 0;
    if ($subject_percent > $top_subject_percent) {
        $top_subject_percent = $subject_percent;
        $top_subject = $row['subject_name'];
    }
}

mysqli_data_seek($result, 0);

$percentage = 0;
if ($total_max > 0) {
    $percentage = round(($total_obtained / $total_max) * 100);
}

$grade = 'C';
if ($percentage >= 85) {
    $grade = 'A';
} elseif ($percentage >= 70) {
    $grade = 'B';
}

$status = $percentage >= 40 ? 'Pass' : 'Fail';

function getResultRemark($percentage) {
    if ($percentage >= 90) return 'Elite Performance';
    if ($percentage >= 80) return 'Outstanding';
    if ($percentage >= 70) return 'Very Good';
    if ($percentage >= 60) return 'Strong Progress';
    if ($percentage >= 40) return 'Needs Improvement';
    return 'Critical Zone';
}

function getPerformanceLine($percentage) {
    if ($percentage >= 90) return 'You are performing at an exceptional academic level.';
    if ($percentage >= 80) return 'Your consistency and marks reflect excellent progress.';
    if ($percentage >= 70) return 'You have maintained a strong and stable performance.';
    if ($percentage >= 60) return 'Your result is good, with room for even better growth.';
    if ($percentage >= 40) return 'You passed, but focused revision is recommended.';
    return 'You need immediate improvement and subject-wise attention.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results | Smart LMS</title>
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
                radial-gradient(circle at top right, rgba(96,165,250,0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(147,197,253,0.12), transparent 22%),
                linear-gradient(135deg, #eff6ff, #f8fbff, #eef2ff);
            color:#0f172a;
        }

        .navbar-pro{
            background:rgba(255,255,255,0.72);
            backdrop-filter:blur(16px);
            border-bottom:1px solid rgba(255,255,255,0.78);
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
            width:50px;
            height:50px;
            border-radius:18px;
            background:linear-gradient(135deg, #2563eb, #38bdf8);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 18px 34px rgba(37,99,235,0.22);
        }

        .profile-chip{
            display:flex;
            align-items:center;
            gap:12px;
            background:rgba(255,255,255,0.85);
            border:1px solid rgba(255,255,255,0.65);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.05);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #2563eb, #38bdf8);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:36px 0 50px;
        }

        .page-shell{
            max-width:1280px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(29,78,216,0.96), rgba(14,165,233,0.88));
            border-radius:36px;
            padding:40px;
            color:white;
            box-shadow:0 24px 70px rgba(37,99,235,0.18);
            margin-bottom:26px;
        }

        .hero-panel::before{
            content:"";
            position:absolute;
            width:300px;
            height:300px;
            border-radius:50%;
            background:rgba(255,255,255,0.10);
            top:-130px;
            right:-70px;
        }

        .hero-panel::after{
            content:"";
            position:absolute;
            width:190px;
            height:190px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
            bottom:-80px;
            left:-35px;
        }

        .hero-tag{
            display:inline-block;
            background:rgba(255,255,255,0.15);
            border:1px solid rgba(255,255,255,0.24);
            border-radius:999px;
            padding:8px 14px;
            font-size:13px;
            font-weight:600;
            margin-bottom:16px;
            position:relative;
            z-index:2;
        }

        .hero-title{
            font-size:40px;
            font-weight:800;
            margin-bottom:10px;
            position:relative;
            z-index:2;
        }

        .hero-sub{
            max-width:780px;
            color:rgba(255,255,255,0.93);
            line-height:1.8;
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
            border:1px solid rgba(255,255,255,0.2);
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
            background:rgba(255,255,255,0.78);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.72);
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
            background:linear-gradient(90deg, #2563eb, #38bdf8, #93c5fd);
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
            font-size:30px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:8px;
        }

        .stat-note{
            color:#475569;
            font-size:14px;
        }

        .highlight-grid{
            display:grid;
            grid-template-columns:1.2fr 0.8fr;
            gap:18px;
            margin-bottom:24px;
        }

        .highlight-card{
            padding:24px;
            border-radius:28px;
            background:rgba(255,255,255,0.82);
            border:1px solid rgba(255,255,255,0.75);
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
        }

        .highlight-title{
            font-size:22px;
            font-weight:800;
            margin-bottom:10px;
            color:#0f172a;
        }

        .highlight-sub{
            color:#64748b;
            line-height:1.8;
            font-size:14px;
            margin-bottom:20px;
        }

        .insight-row{
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:14px;
        }

        .insight-box{
            padding:16px;
            border-radius:20px;
            background:linear-gradient(135deg, #eff6ff, #f0f9ff);
            border:1px solid #dbeafe;
        }

        .insight-box small{
            display:block;
            color:#64748b;
            font-size:12px;
            font-weight:700;
            margin-bottom:8px;
            text-transform:uppercase;
        }

        .insight-box strong{
            font-size:18px;
            color:#0f172a;
            font-weight:800;
        }

        .ring-panel{
            text-align:center;
            padding:24px;
            border-radius:28px;
            background:rgba(255,255,255,0.82);
            border:1px solid rgba(255,255,255,0.75);
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
        }

        .ring-panel h4{
            font-size:20px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:16px;
        }

        .ring{
            width:170px;
            height:170px;
            margin:0 auto 16px;
            border-radius:50%;
            background:conic-gradient(#2563eb 0% <?php echo $percentage; ?>%, #dbeafe <?php echo $percentage; ?>% 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 20px 40px rgba(37,99,235,0.12);
        }

        .ring-inner{
            width:125px;
            height:125px;
            border-radius:50%;
            background:white;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            box-shadow:inset 0 0 20px rgba(15,23,42,0.04);
        }

        .ring-inner strong{
            font-size:34px;
            color:#0f172a;
            font-weight:800;
            line-height:1;
        }

        .ring-inner span{
            color:#64748b;
            font-size:13px;
            margin-top:6px;
            font-weight:700;
        }

        .section-card{
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
            background:linear-gradient(135deg, #2563eb, #38bdf8);
            color:white;
            box-shadow:0 16px 30px rgba(37,99,235,0.16);
        }

        .btn-primary-pro:hover{
            color:white;
            transform:translateY(-2px);
        }

        .btn-light-pro{
            background:#ffffff;
            color:#334155;
            border:1px solid #dbeafe;
        }

        .btn-light-pro:hover{
            color:#0f172a;
            transform:translateY(-2px);
        }

        .table-shell{
            overflow:hidden;
            border-radius:24px;
            border:1px solid #dbeafe;
            background:rgba(255,255,255,0.88);
        }

        .table{
            margin:0;
        }

        .table thead{
            background:linear-gradient(135deg, #eff6ff, #f0f9ff);
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
            border-top:1px solid #e0f2fe;
            font-size:14px;
            color:#1e293b;
        }

        .table tbody tr:hover{
            background:rgba(59,130,246,0.04);
        }

        .subject-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:linear-gradient(135deg, #eff6ff, #f0f9ff);
            color:#1d4ed8;
            border:1px solid #bfdbfe;
            border-radius:999px;
            padding:8px 14px;
            font-weight:800;
            font-size:13px;
        }

        .score-pill{
            display:inline-block;
            min-width:90px;
            text-align:center;
            background:#fff;
            border:1px solid #dbeafe;
            border-radius:14px;
            padding:8px 12px;
            font-weight:800;
            color:#0f172a;
        }

        .badge-pass{
            background:linear-gradient(135deg, #dcfce7, #dbeafe);
            color:#166534;
            padding:8px 14px;
            border-radius:999px;
            font-weight:800;
            border:1px solid #bbf7d0;
        }

        .badge-fail{
            background:linear-gradient(135deg, #fee2e2, #e0f2fe);
            color:#991b1b;
            padding:8px 14px;
            border-radius:999px;
            font-weight:800;
            border:1px solid #fecaca;
        }

        .final-wrap{
            margin-top:20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            flex-wrap:wrap;
            padding:20px 22px;
            border-radius:22px;
            background:linear-gradient(135deg, #eff6ff, #f0f9ff);
            border:1px solid #dbeafe;
        }

        .final-title{
            font-size:18px;
            font-weight:800;
            color:#0f172a;
        }

        .final-sub{
            color:#64748b;
            font-size:14px;
        }

        .grade-box{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .grade-pill{
            min-width:72px;
            text-align:center;
            padding:10px 14px;
            border-radius:16px;
            background:linear-gradient(135deg, #2563eb, #38bdf8);
            color:white;
            font-weight:800;
            box-shadow:0 14px 28px rgba(37,99,235,0.16);
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
            background:linear-gradient(135deg, #2563eb, #38bdf8);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:36px;
            box-shadow:0 18px 34px rgba(37,99,235,0.18);
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

        @media(max-width:991px){
            .stats-grid{
                grid-template-columns:1fr 1fr;
            }

            .highlight-grid{
                grid-template-columns:1fr;
            }

            .insight-row{
                grid-template-columns:1fr;
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
                border-bottom:1px solid #e0f2fe;
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

        @media print{
            .navbar-pro,
            .print-hide{
                display:none !important;
            }

            body{
                background:#ffffff;
            }

            .glass-card,
            .highlight-card,
            .ring-panel{
                box-shadow:none;
            }
        }
    </style>
</head>
<body>

<nav class="navbar-pro">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="brand">
            <div class="brand-logo">R</div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo htmlspecialchars($name); ?></div>
                <small style="color:#64748b;">Results Center</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">Advanced Academic Performance Center</div>
            <div class="hero-title">My Result Dashboard</div>
            <p class="hero-sub">
                Review your complete academic performance in a premium fade-blue result center. Track total marks,
                subject-wise scores, final percentage, grade, and overall progress in one modern interface.
            </p>

            <div class="hero-summary">
                <div class="hero-chip">Subjects: <?php echo $total_subjects; ?></div>
                <div class="hero-chip">Score: <?php echo $total_obtained; ?>/<?php echo $total_max; ?></div>
                <div class="hero-chip">Percentage: <?php echo $percentage; ?>%</div>
                <div class="hero-chip">Grade: <?php echo $grade; ?></div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-label">Total Subjects</div>
                <div class="stat-value"><?php echo $total_subjects; ?></div>
                <div class="stat-note">Available result records</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Obtained Marks</div>
                <div class="stat-value"><?php echo $total_obtained; ?></div>
                <div class="stat-note">Combined score achieved</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Percentage</div>
                <div class="stat-value"><?php echo $percentage; ?>%</div>
                <div class="stat-note"><?php echo getResultRemark($percentage); ?></div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Final Grade</div>
                <div class="stat-value"><?php echo $grade; ?></div>
                <div class="stat-note"><?php echo $status; ?></div>
            </div>
        </div>

        <div class="highlight-grid">
            <div class="highlight-card">
                <div class="highlight-title">Performance Insights</div>
                <div class="highlight-sub">
                    <?php echo getPerformanceLine($percentage); ?>
                </div>

                <div class="insight-row">
                    <div class="insight-box">
                        <small>Top Subject</small>
                        <strong><?php echo htmlspecialchars($top_subject); ?></strong>
                    </div>

                    <div class="insight-box">
                        <small>Best Score</small>
                        <strong><?php echo $top_subject_percent >= 0 ? $top_subject_percent . '%' : 'N/A'; ?></strong>
                    </div>

                    <div class="insight-box">
                        <small>Result Status</small>
                        <strong><?php echo $status; ?></strong>
                    </div>
                </div>
            </div>

            <div class="ring-panel">
                <h4>Overall Score</h4>
                <div class="ring">
                    <div class="ring-inner">
                        <strong><?php echo $percentage; ?>%</strong>
                        <span>Performance</span>
                    </div>
                </div>
                <div style="font-size:14px; color:#64748b; font-weight:600;">
                    Academic progress indicator
                </div>
            </div>
        </div>

        <div class="glass-card section-card">
            <div class="section-head">
                <div>
                    <h3>Subject-wise Result Overview</h3>
                    <p>Detailed summary of your marks with modern premium styling.</p>
                </div>

                <div class="print-hide" style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Back to Dashboard</a>
                    <a href="/lms_project/courses.php" class="btn-pro btn-light-pro">View Courses</a>
                    <button onclick="window.print()" class="btn-pro btn-primary-pro">Print Result</button>
                </div>
            </div>

            <?php if ($total_subjects > 0) { ?>
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Obtained</th>
                                    <th>Total</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) {
                                    $subject_percent = $row['total_marks'] > 0 ? round(($row['marks_obtained'] / $row['total_marks']) * 100) : 0;
                                ?>
                                    <tr>
                                        <td data-label="Subject">
                                            <span class="subject-pill">📘 <?php echo htmlspecialchars($row['subject_name']); ?></span>
                                        </td>
                                        <td data-label="Obtained">
                                            <span class="score-pill"><?php echo $row['marks_obtained']; ?></span>
                                        </td>
                                        <td data-label="Total">
                                            <span class="score-pill"><?php echo $row['total_marks']; ?></span>
                                        </td>
                                        <td data-label="Percentage">
                                            <span class="score-pill"><?php echo $subject_percent; ?>%</span>
                                        </td>
                                        <td data-label="Status">
                                            <?php if ($subject_percent >= 40) { ?>
                                                <span class="badge-pass">Pass</span>
                                            <?php } else { ?>
                                                <span class="badge-fail">Fail</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="final-wrap">
                    <div>
                        <div class="final-title">Final Academic Status</div>
                        <div class="final-sub">Overall remark: <?php echo getResultRemark($percentage); ?></div>
                    </div>

                    <div class="grade-box">
                        <?php if ($status == 'Pass') { ?>
                            <span class="badge-pass"><?php echo $status; ?></span>
                        <?php } else { ?>
                            <span class="badge-fail"><?php echo $status; ?></span>
                        <?php } ?>

                        <span class="grade-pill">Grade <?php echo $grade; ?></span>
                    </div>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">📊</div>
                    <h4>No result records found</h4>
                    <p>
                        Result data is not available yet for your account. Once records are added to the database, they will appear here automatically in this advanced result dashboard.
                    </p>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-primary-pro">Go to Dashboard</a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>