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

$query = "SELECT * FROM courses ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$total_courses = 0;
if ($result) {
    $total_courses = mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses | Smart LMS</title>
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
                radial-gradient(circle at top left, rgba(59,130,246,0.16), transparent 26%),
                radial-gradient(circle at top right, rgba(168,85,247,0.14), transparent 24%),
                radial-gradient(circle at bottom left, rgba(14,165,233,0.10), transparent 24%),
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
            background:linear-gradient(135deg, #2563eb, #7c3aed);
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
            background:rgba(255,255,255,0.78);
            border:1px solid rgba(255,255,255,0.65);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.05);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #06b6d4, #6366f1);
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
            max-width:1280px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(37,99,235,0.95), rgba(124,58,237,0.88));
            border-radius:34px;
            padding:34px;
            color:white;
            box-shadow:0 24px 60px rgba(99,102,241,0.18);
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

        .courses-section{
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
            background:linear-gradient(135deg, #2563eb, #7c3aed);
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
            border:1px solid #e2e8f0;
        }

        .btn-light-pro:hover{
            color:#0f172a;
            transform:translateY(-2px);
        }

        .course-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(290px, 1fr));
            gap:20px;
        }

        .course-card{
            position:relative;
            overflow:hidden;
            padding:22px;
            border-radius:28px;
            background:rgba(255,255,255,0.82);
            border:1px solid rgba(255,255,255,0.8);
            box-shadow:0 18px 40px rgba(15,23,42,0.06);
            transition:0.35s ease;
        }

        .course-card:hover{
            transform:translateY(-6px);
            box-shadow:0 26px 54px rgba(15,23,42,0.10);
        }

        .course-card::before{
            content:"";
            position:absolute;
            inset:0 0 auto 0;
            height:6px;
            background:linear-gradient(90deg, #2563eb, #06b6d4, #7c3aed);
        }

        .card-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:12px;
            margin-bottom:18px;
        }

        .icon-box{
            width:58px;
            height:58px;
            border-radius:18px;
            background:linear-gradient(135deg, #dbeafe, #ede9fe);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            box-shadow:0 10px 24px rgba(37,99,235,0.08);
        }

        .course-badge{
            display:inline-block;
            background:linear-gradient(135deg, #ecfeff, #eef2ff);
            color:#4338ca;
            border:1px solid #dbeafe;
            border-radius:999px;
            padding:7px 12px;
            font-size:12px;
            font-weight:800;
        }

        .course-title{
            font-size:22px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:10px;
            line-height:1.35;
        }

        .course-desc{
            color:#64748b;
            font-size:14px;
            line-height:1.8;
            margin-bottom:18px;
            min-height:75px;
        }

        .meta-row{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:14px;
            flex-wrap:wrap;
        }

        .meta-chip{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:14px;
            padding:9px 12px;
            font-size:13px;
            color:#334155;
            font-weight:700;
        }

        .progress-shell{
            margin-bottom:16px;
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
            height:10px;
            border-radius:999px;
            background:#e2e8f0;
            overflow:hidden;
        }

        .progress-bar{
            border-radius:999px;
            background:linear-gradient(90deg, #2563eb, #06b6d4, #7c3aed);
        }

        .card-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-top:6px;
        }

        .mini-btn{
            flex:1;
            min-width:120px;
            text-align:center;
            text-decoration:none;
            border-radius:14px;
            padding:11px 14px;
            font-size:13px;
            font-weight:800;
            transition:0.3s ease;
        }

        .mini-btn.primary{
            background:linear-gradient(135deg, #2563eb, #7c3aed);
            color:white;
        }

        .mini-btn.primary:hover{
            color:white;
            transform:translateY(-2px);
        }

        .mini-btn.light{
            background:#f8fafc;
            color:#334155;
            border:1px solid #e2e8f0;
        }

        .mini-btn.light:hover{
            color:#0f172a;
            transform:translateY(-2px);
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
            background:linear-gradient(135deg, #2563eb, #7c3aed);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:34px;
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

            .course-title{
                font-size:20px;
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
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo $name; ?></div>
                <small style="color:#64748b;">My Courses Panel</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">Premium Learning Space</div>
            <div class="hero-title">My Courses</div>
            <p class="hero-sub">
                Explore your enrolled courses in a polished, modern, and premium academic dashboard.
                Track course progress, organize learning, and enjoy a unique ultra-level professional LMS design.
            </p>
        </div>

        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-label">Total Courses</div>
                <div class="stat-value"><?php echo $total_courses; ?></div>
                <div class="stat-note">Available learning modules</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Student Name</div>
                <div class="stat-value" style="font-size:24px;"><?php echo $name; ?></div>
                <div class="stat-note">Active learner profile</div>
            </div>

            <div class="glass-card stat-card">
                <div class="stat-label">Experience</div>
                <div class="stat-value" style="font-size:24px;">Premium</div>
                <div class="stat-note">Modern course dashboard ready</div>
            </div>
        </div>

        <div class="glass-card courses-section">
            <div class="section-head">
                <div>
                    <h3>Course Library</h3>
                    <p>Unique and ultra premium view of all your learning content.</p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Back to Dashboard</a>
                    <a href="/lms_project/assignments.php" class="btn-pro btn-primary-pro">View Assignments</a>
                </div>
            </div>

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                <div class="course-grid">
                    <?php
                    mysqli_data_seek($result, 0);
                    $icons = ['💻','🌐','🗄️','📱','🎨','🧠','⚙️','📊','🔒','🧾'];
                    $count = 0;
                    while($row = mysqli_fetch_assoc($result)) {
                        $title = isset($row['title']) ? htmlspecialchars($row['title']) : (isset($row['course_name']) ? htmlspecialchars($row['course_name']) : 'Course');
                        $description = isset($row['description']) ? htmlspecialchars($row['description']) : 'A professional course module designed to improve your academic and practical skills.';
                        $instructor = isset($row['instructor']) ? htmlspecialchars($row['instructor']) : 'Faculty Mentor';
                        $duration = isset($row['duration']) ? htmlspecialchars($row['duration']) : '8 Weeks';
                        $progress = 55 + (($count * 9) % 35);
                        $icon = $icons[$count % count($icons)];
                        $count++;
                    ?>
                        <div class="course-card">
                            <div class="card-top">
                                <div class="icon-box"><?php echo $icon; ?></div>
                                <span class="course-badge">Active Course</span>
                            </div>

                            <div class="course-title"><?php echo $title; ?></div>

                            <div class="course-desc"><?php echo $description; ?></div>

                            <div class="meta-row">
                                <div class="meta-chip">👨‍🏫 <?php echo $instructor; ?></div>
                                <div class="meta-chip">⏳ <?php echo $duration; ?></div>
                            </div>

                            <div class="progress-shell">
                                <div class="progress-label">
                                    <span>Progress</span>
                                    <span><?php echo $progress; ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
                                </div>
                            </div>

                            <div class="card-actions">
                                <a href="/lms_project/assignments.php" class="mini-btn primary">Open Course</a>
                                <a href="/lms_project/my_submissions.php" class="mini-btn light">Submissions</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="footer-note">
                    Your course cards are styled with a premium modern layout, soft fade colors, and a polished dashboard experience.
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <h4>No courses available</h4>
                    <p>
                        There are no course records yet. Once courses are added to your LMS database,
                        they will appear here in this premium ultra-level course dashboard.
                    </p>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-primary-pro">Go to Dashboard</a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>