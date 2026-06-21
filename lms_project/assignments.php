<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];
$name = isset($user['name']) ? htmlspecialchars($user['name']) : 'Student';

$query = "SELECT * FROM assignments ORDER BY due_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments - Smart LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            margin:0;
            font-family:'Segoe UI', sans-serif;
            background:#f4f7fe;
            color:#1e293b;
        }

        .layout{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:260px;
            background:#ffffff;
            border-right:1px solid #e9eef7;
            padding:20px 16px;
            position:fixed;
            left:0;
            top:0;
            height:100vh;
            overflow-y:auto;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:24px;
        }

        .brand-icon{
            width:46px;
            height:46px;
            border-radius:14px;
            background:linear-gradient(135deg,#5b5ce2,#7b7cff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .brand h4{
            margin:0;
            font-size:18px;
            font-weight:700;
        }

        .brand small{
            color:#94a3b8;
        }

        .profile-panel{
            background:#f8faff;
            border:1px solid #edf2f7;
            border-radius:18px;
            padding:14px;
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:22px;
        }

        .avatar{
            width:46px;
            height:46px;
            border-radius:14px;
            background:linear-gradient(135deg,#5b5ce2,#7b7cff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            font-size:18px;
            flex-shrink:0;
        }

        .menu-title{
            font-size:12px;
            color:#94a3b8;
            text-transform:uppercase;
            margin:16px 0 10px 8px;
            letter-spacing:0.8px;
        }

        .sidebar a{
            display:flex;
            align-items:center;
            gap:10px;
            text-decoration:none;
            color:#475569;
            padding:11px 12px;
            border-radius:14px;
            margin-bottom:8px;
            font-size:14px;
            font-weight:600;
            transition:0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active{
            background:#eef2ff;
            color:#5b5ce2;
        }

        .menu-dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#cbd5e1;
        }

        .sidebar a.active .menu-dot{
            background:#5b5ce2;
        }

        .main{
            margin-left:260px;
            width:calc(100% - 260px);
            padding:20px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            margin-bottom:18px;
        }

        .topbar-left h3{
            margin:0;
            font-size:24px;
            font-weight:700;
        }

        .topbar-left p{
            margin:2px 0 0;
            color:#64748b;
            font-size:13px;
        }

        .assign-card{
            background:white;
            border-radius:20px;
            padding:18px;
            box-shadow:0 10px 30px rgba(15, 23, 42, 0.05);
            margin-bottom:16px;
        }

        .assign-card h5{
            margin-bottom:8px;
            font-weight:700;
        }

        .assign-card p{
            color:#64748b;
            margin-bottom:10px;
        }

        .due{
            font-size:13px;
            font-weight:600;
            color:#ea580c;
            margin-bottom:12px;
        }

        .btn-submit{
            background:linear-gradient(135deg,#5b5ce2,#7b7cff);
            color:white;
            border:none;
            padding:10px 14px;
            border-radius:12px;
            font-size:13px;
            font-weight:700;
        }

        .btn-submit:hover{
            color:white;
        }

        @media(max-width:991px){
            .layout{
                display:block;
            }

            .sidebar{
                position:relative;
                width:100%;
                height:auto;
            }

            .main{
                margin-left:0;
                width:100%;
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
        <a href="dashboard.php"><span class="menu-dot"></span> Dashboard</a>
        <a href="courses.php"><span class="menu-dot"></span> My Courses</a>
        <a href="assignments.php" class="active"><span class="menu-dot"></span> Assignments</a>
        <a href="#"><span class="menu-dot"></span> Attendance</a>
        <a href="#"><span class="menu-dot"></span> Results</a>
        <a href="#"><span class="menu-dot"></span> Certificates</a>

        <div class="menu-title">Account</div>
        <a href="#"><span class="menu-dot"></span> Profile</a>
        <a href="#"><span class="menu-dot"></span> Settings</a>
        <a href="logout.php"><span class="menu-dot"></span> Logout</a>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h3>Assignments</h3>
                <p>Check your pending work and due dates here.</p>
            </div>
        </div>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <div class="assign-card">
                <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <div class="due">Due Date: <?php echo htmlspecialchars($row['due_date']); ?></div>
                <a href="/lms_project/submit_assignment.php?id=<?php echo $row['id']; ?>" class="btn-submit">Submit Assignment</a>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>