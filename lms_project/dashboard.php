<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];

$name = isset($user['name']) && $user['name'] != ''
        ? htmlspecialchars($user['name'])
        : 'Student';

$initial = strtoupper(substr($name, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Smart LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7fe;
            color: #1e293b;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e9eef7;
            padding: 20px 16px;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #5b5ce2, #7b7cff);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .brand h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .brand small {
            color: #94a3b8;
        }

        .profile-panel {
            background: #f8faff;
            border: 1px solid #edf2f7;
            border-radius: 18px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #5b5ce2, #7b7cff);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-title {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            margin: 16px 0 10px 8px;
            letter-spacing: 0.8px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #475569;
            padding: 11px 12px;
            border-radius: 14px;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #eef2ff;
            color: #5b5ce2;
        }

        .menu-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .sidebar a.active .menu-dot {
            background: #5b5ce2;
        }

        .promo-box {
            margin-top: 20px;
            background: linear-gradient(135deg, #5b5ce2, #7b7cff);
            color: white;
            border-radius: 18px;
            padding: 16px;
        }

        .promo-box h6 {
            margin-bottom: 6px;
            font-weight: 700;
        }

        .promo-box p {
            font-size: 12px;
            margin-bottom: 12px;
            opacity: 0.95;
        }

        .promo-box button {
            border: none;
            background: white;
            color: #5b5ce2;
            border-radius: 12px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 13px;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .topbar-left h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .topbar-left p {
            margin: 2px 0 0;
            color: #64748b;
            font-size: 13px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-box {
            width: 260px;
            border: 1px solid #e2e8f0;
            background: white;
            padding: 12px 14px;
            border-radius: 14px;
            outline: none;
        }

        .icon-chip {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: white;
            border: 1px solid #e9eef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card-ui {
            background: white;
            border-radius: 22px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            height: 100%;
        }

        .hero-card {
            background: linear-gradient(135deg, #5b5ce2, #7b7cff);
            color: white;
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 18px;
            box-shadow: 0 16px 40px rgba(91, 92, 226, 0.22);
        }

        .hero-card h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 700;
        }

        .hero-card p {
            margin: 0 0 16px;
            max-width: 700px;
            opacity: 0.95;
            font-size: 14px;
        }

        .hero-btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            margin-right: 10px;
        }

        .hero-btn.primary {
            background: white;
            color: #5b5ce2;
        }

        .hero-btn.secondary {
            background: rgba(255,255,255,0.18);
            color: white;
        }

        .stat-card h6 {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 13px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .stat-note {
            margin-top: 6px;
            font-size: 12px;
            color: #16a34a;
            font-weight: 600;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .section-head h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .section-head span {
            color: #94a3b8;
            font-size: 12px;
        }

        .fake-chart {
            height: 180px;
            border-radius: 16px;
            background:
                linear-gradient(to top, rgba(91, 92, 226, 0.06), rgba(91, 92, 226, 0.01)),
                repeating-linear-gradient(to right, #eef2f7 0, #eef2f7 1px, transparent 1px, transparent 60px),
                repeating-linear-gradient(to top, #eef2f7 0, #eef2f7 1px, transparent 1px, transparent 36px);
            position: relative;
            overflow: hidden;
        }

        .fake-chart::after {
            content: "";
            position: absolute;
            left: 16px;
            right: 16px;
            top: 90px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg,
                transparent 0%,
                #8c8dff 10%,
                #5b5ce2 25%,
                #7b7cff 45%,
                #5b5ce2 65%,
                #7b7cff 82%,
                transparent 100%);
            transform: rotate(-5deg);
            box-shadow:
                40px -18px 0 0 #7b7cff,
                90px 10px 0 0 #5b5ce2,
                150px -24px 0 0 #8c8dff,
                220px 16px 0 0 #5b5ce2,
                300px -8px 0 0 #7b7cff;
        }

        .bar-row {
            display: flex;
            align-items: end;
            gap: 8px;
            height: 90px;
            margin-top: 14px;
        }

        .bar-row span {
            flex: 1;
            border-radius: 10px 10px 4px 4px;
            background: linear-gradient(180deg, #8c8dff, #5b5ce2);
        }

        .course-item,
        .activity-item,
        .schedule-item,
        .task-item {
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .course-item:last-child,
        .activity-item:last-child,
        .schedule-item:last-child,
        .task-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .course-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: start;
            margin-bottom: 8px;
        }

        .course-top h6,
        .activity-item h6,
        .schedule-item h6 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
        }

        .muted {
            color: #94a3b8;
            font-size: 12px;
        }

        .pill {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pill.green {
            background: #ecfdf3;
            color: #16a34a;
        }

        .pill.purple {
            background: #eef2ff;
            color: #5b5ce2;
        }

        .pill.orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .progress {
            height: 8px;
            border-radius: 20px;
            background: #e2e8f0;
            margin-top: 8px;
        }

        .progress-bar {
            border-radius: 20px;
            background: linear-gradient(90deg, #5b5ce2, #8c8dff);
        }

        .activity-item {
            display: flex;
            gap: 12px;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #eef2ff;
            color: #5b5ce2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .ring-wrap {
            text-align: center;
        }

        .ring {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 14px auto 10px;
            background: conic-gradient(#5b5ce2 0% 76%, #e2e8f0 76% 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ring-inner {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
        }

        .mini-metric {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .mini-metric:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .mini-metric span {
            color: #64748b;
            font-size: 13px;
        }

        .table-clean th {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 1px solid #eef2f7;
        }

        .table-clean td {
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        @media (max-width: 991px) {
            .layout {
                display: block;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-right {
                flex-wrap: wrap;
            }

            .search-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="layout">
    <div class="sidebar">
        <div class="brand">
            <div class="brand-icon"><?php echo $initial; ?></div>
            <div>
                <h4>Smart LMS</h4>
                <small>College Student Panel</small>
            </div>
        </div>

        <div class="profile-panel">
            <div class="avatar"><?php echo $initial; ?></div>
            <div>
                <div style="font-weight:700; font-size:14px;"><?php echo $name; ?></div>
                <small style="color:#94a3b8;">Active Student</small>
            </div>
        </div>

        <div class="menu-title">Main</div>
        <a href="/lms_project/dashboard.php" class="active"><span class="menu-dot"></span> Dashboard</a>
        <a href="/lms_project/courses.php"><span class="menu-dot"></span> My Courses</a>
        <a href="/lms_project/assignments.php"><span class="menu-dot"></span> Assignments</a>
        <a href="/lms_project/my_submissions.php"><span class="menu-dot"></span> My Submissions</a>
        <a href="/lms_project/attendance.php"><span class="menu-dot"></span> Attendance</a>
        <a href="/lms_project/results.php"><span class="menu-dot"></span> Results</a>
        <a href="/lms_project/teacher_panel.php"><span class="menu-dot"></span> Teacher Panel</a>
        <a href="/lms_project/quiz.php"><span class="menu-dot"></span> Quiz</a>
        <a href="/lms_project/certificate.php"><span class="menu-dot"></span> Certificates</a>

        <div class="menu-title">Account</div>
        <a href="/lms_project/profile.php"><span class="menu-dot"></span> Profile</a>
        <a href="/lms_project/settings.php"><span class="menu-dot"></span> Settings</a>
        <a href="/lms_project/logout.php"><span class="menu-dot"></span> Logout</a>

        <div class="promo-box">
            <h6>Student Pro Tools</h6>
            <p>Access smart notes, AI quiz support and premium performance analytics.</p>
            <button>Explore</button>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h3>Dashboard</h3>
                <p>Welcome back, <?php echo $name; ?>. Here is your academic performance overview.</p>
            </div>

            <div class="topbar-right">
                <input type="text" class="search-box" placeholder="Search courses, notes, tasks...">
                <div class="icon-chip">🔔</div>
                <div class="icon-chip">📅</div>
                <div class="icon-chip">⚙️</div>
            </div>
        </div>

        <div class="hero-card">
            <h2>Hello, <?php echo $name; ?> 👋</h2>
            <p>
                Stay ahead with your college learning journey. Track enrolled courses, check attendance, manage assignments,
                review upcoming classes and monitor your academic growth from one premium dashboard.
            </p>
            <a href="/lms_project/courses.php" class="hero-btn primary">Browse Courses</a>
            <a href="/lms_project/results.php" class="hero-btn secondary">Check Progress</a>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-6">
                <div class="card-ui stat-card">
                    <h6>Total Courses</h6>
                    <h3>12</h3>
                    <div class="stat-note">+2 added this semester</div>
                    <div class="bar-row">
                        <span style="height:34%;"></span>
                        <span style="height:54%;"></span>
                        <span style="height:48%;"></span>
                        <span style="height:78%;"></span>
                        <span style="height:60%;"></span>
                        <span style="height:85%;"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card-ui stat-card">
                    <h6>Assignments Pending</h6>
                    <h3>08</h3>
                    <div class="stat-note">Due this week</div>
                    <div class="bar-row">
                        <span style="height:22%;"></span>
                        <span style="height:42%;"></span>
                        <span style="height:35%;"></span>
                        <span style="height:68%;"></span>
                        <span style="height:52%;"></span>
                        <span style="height:74%;"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card-ui stat-card">
                    <h6>Attendance</h6>
                    <h3>92%</h3>
                    <div class="stat-note">Excellent consistency</div>
                    <div class="bar-row">
                        <span style="height:48%;"></span>
                        <span style="height:72%;"></span>
                        <span style="height:58%;"></span>
                        <span style="height:80%;"></span>
                        <span style="height:62%;"></span>
                        <span style="height:90%;"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card-ui stat-card">
                    <h6>Average Score</h6>
                    <h3>88%</h3>
                    <div class="stat-note">Strong performance</div>
                    <div class="bar-row">
                        <span style="height:38%;"></span>
                        <span style="height:58%;"></span>
                        <span style="height:40%;"></span>
                        <span style="height:76%;"></span>
                        <span style="height:64%;"></span>
                        <span style="height:82%;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-8">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>Academic Analytics</h5>
                        <span>Semester Performance</span>
                    </div>
                    <div class="fake-chart"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-ui ring-wrap">
                    <div class="section-head">
                        <h5>Weekly Goal</h5>
                        <span>Target Status</span>
                    </div>
                    <div class="ring">
                        <div class="ring-inner">76%</div>
                    </div>
                    <div class="mini-metric">
                        <span>Study Completion</span>
                        <strong>76%</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Quiz Accuracy</span>
                        <strong>84%</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Submitted Tasks</span>
                        <strong>11/14</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>Continue Learning</h5>
                        <span>Current Courses</span>
                    </div>

                    <div class="course-item">
                        <div class="course-top">
                            <div>
                                <h6>Web Development Fundamentals</h6>
                                <div class="muted">HTML, CSS, Bootstrap basics</div>
                            </div>
                            <span class="pill green">78%</span>
                        </div>
                        <div class="progress"><div class="progress-bar" style="width:78%"></div></div>
                    </div>

                    <div class="course-item">
                        <div class="course-top">
                            <div>
                                <h6>PHP & MySQL Essentials</h6>
                                <div class="muted">Backend logic and DB connectivity</div>
                            </div>
                            <span class="pill purple">56%</span>
                        </div>
                        <div class="progress"><div class="progress-bar" style="width:56%"></div></div>
                    </div>

                    <div class="course-item">
                        <div class="course-top">
                            <div>
                                <h6>Computer Networks</h6>
                                <div class="muted">Protocols and architecture</div>
                            </div>
                            <span class="pill orange">34%</span>
                        </div>
                        <div class="progress"><div class="progress-bar" style="width:34%"></div></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>Recent Activity</h5>
                        <span>Live Updates</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">✓</div>
                        <div>
                            <h6>Lesson 5 completed</h6>
                            <small class="muted">Web Development Fundamentals</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">📝</div>
                        <div>
                            <h6>Assignment submitted</h6>
                            <small class="muted">PHP mini project uploaded</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">📘</div>
                        <div>
                            <h6>New course joined</h6>
                            <small class="muted">UI / UX Design Basics</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">⬇</div>
                        <div>
                            <h6>Notes downloaded</h6>
                            <small class="muted">Database Management Module 2</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>Upcoming Classes</h5>
                        <span>This Week</span>
                    </div>

                    <div class="schedule-item">
                        <h6>Database Management</h6>
                        <div class="muted">Today • 3:00 PM - 4:00 PM</div>
                    </div>

                    <div class="schedule-item">
                        <h6>Advanced PHP Session</h6>
                        <div class="muted">Tomorrow • 11:00 AM - 12:30 PM</div>
                    </div>

                    <div class="schedule-item">
                        <h6>UI Review Workshop</h6>
                        <div class="muted">Friday • 1:30 PM - 2:30 PM</div>
                    </div>

                    <div class="schedule-item">
                        <h6>Networking Lab</h6>
                        <div class="muted">Saturday • 10:00 AM - 12:00 PM</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>My Courses</h5>
                        <span>Academic Overview</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-clean align-middle">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Web Development</td>
                                    <td>Prof. Sharma</td>
                                    <td><span class="pill green">Active</span></td>
                                    <td>78%</td>
                                </tr>
                                <tr>
                                    <td>PHP & MySQL</td>
                                    <td>Prof. Khan</td>
                                    <td><span class="pill purple">Ongoing</span></td>
                                    <td>56%</td>
                                </tr>
                                <tr>
                                    <td>Computer Networks</td>
                                    <td>Prof. Verma</td>
                                    <td><span class="pill orange">Pending</span></td>
                                    <td>34%</td>
                                </tr>
                                <tr>
                                    <td>UI / UX Design</td>
                                    <td>Prof. Roy</td>
                                    <td><span class="pill green">Active</span></td>
                                    <td>41%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-ui">
                    <div class="section-head">
                        <h5>Task Manager</h5>
                        <span>Today</span>
                    </div>

                    <div class="task-item">
                        <strong>Complete PHP quiz</strong>
                        <div class="muted">Deadline: Friday</div>
                    </div>

                    <div class="task-item">
                        <strong>Submit UI mini project</strong>
                        <div class="muted">Deadline: Tomorrow</div>
                    </div>

                    <div class="task-item">
                        <strong>Download CN lab file</strong>
                        <div class="muted">Pending resource</div>
                    </div>

                    <div class="task-item">
                        <strong>Prepare DBMS viva notes</strong>
                        <div class="muted">Exam preparation</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>