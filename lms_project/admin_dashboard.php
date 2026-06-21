<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$admin = $_SESSION['admin'];

$total_students = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM users WHERE role='student'"))['total'];

$active_students = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM users WHERE role='student' AND status='active'"))['total'];

$total_courses = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM courses"))['total'];

$total_admins = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM users WHERE role='admin'"))['total'];

$students = mysqli_query($conn,
"SELECT * FROM users WHERE role='student' ORDER BY id DESC LIMIT 6");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Smart LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI', sans-serif;
            background:linear-gradient(135deg,#07111f,#0f172a,#111827);
            color:white;
            min-height:100vh;
        }

        .sidebar{
            width:270px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:rgba(15,23,42,0.95);
            backdrop-filter:blur(18px);
            border-right:1px solid rgba(255,255,255,0.08);
            padding:25px 18px;
        }

        .brand{
            font-size:24px;
            font-weight:800;
            margin-bottom:35px;
            text-align:center;
            color:#fff;
        }

        .brand span{
            color:#38bdf8;
        }

        .menu a{
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 16px;
            margin-bottom:12px;
            color:#cbd5e1;
            text-decoration:none;
            border-radius:16px;
            transition:.3s;
            font-weight:600;
        }

        .menu a:hover,
        .menu .active{
            background:linear-gradient(135deg,#2563eb,#7c3aed);
            color:white;
            transform:translateX(5px);
            box-shadow:0 12px 30px rgba(37,99,235,0.35);
        }

        .main{
            margin-left:270px;
            padding:28px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:rgba(255,255,255,0.06);
            border:1px solid rgba(255,255,255,0.08);
            backdrop-filter:blur(18px);
            padding:20px 24px;
            border-radius:24px;
            margin-bottom:28px;
        }

        .topbar h2{
            font-weight:800;
            margin:0;
        }

        .admin-box{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .admin-avatar{
            width:48px;
            height:48px;
            border-radius:50%;
            background:linear-gradient(135deg,#38bdf8,#8b5cf6);
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            font-size:20px;
        }

        .hero{
            background:
            linear-gradient(135deg,rgba(37,99,235,.85),rgba(124,58,237,.85)),
            url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80');
            background-size:cover;
            background-position:center;
            border-radius:30px;
            padding:35px;
            margin-bottom:30px;
            box-shadow:0 25px 60px rgba(0,0,0,.35);
        }

        .hero h1{
            font-size:38px;
            font-weight:900;
        }

        .hero p{
            max-width:650px;
            color:#e0f2fe;
            font-size:16px;
        }

        .hero .btn{
            border-radius:14px;
            padding:12px 22px;
            font-weight:700;
        }

        .stat-card{
            position:relative;
            overflow:hidden;
            border-radius:26px;
            padding:25px;
            min-height:165px;
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.09);
            backdrop-filter:blur(18px);
            transition:.3s;
            box-shadow:0 18px 45px rgba(0,0,0,.25);
        }

        .stat-card:hover{
            transform:translateY(-8px);
        }

        .stat-card i{
            font-size:34px;
            margin-bottom:18px;
        }

        .stat-card h3{
            font-size:34px;
            font-weight:900;
        }

        .stat-card p{
            color:#cbd5e1;
            margin:0;
            font-weight:600;
        }

        .s1{background:linear-gradient(135deg,#2563eb,#1d4ed8);}
        .s2{background:linear-gradient(135deg,#16a34a,#059669);}
        .s3{background:linear-gradient(135deg,#f59e0b,#ef4444);}
        .s4{background:linear-gradient(135deg,#7c3aed,#db2777);}

        .panel{
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.09);
            backdrop-filter:blur(18px);
            border-radius:26px;
            padding:24px;
            box-shadow:0 18px 45px rgba(0,0,0,.25);
        }

        .panel h4{
            font-weight:800;
            margin-bottom:20px;
        }

        .action-card{
            padding:20px;
            border-radius:22px;
            text-decoration:none;
            color:white;
            display:block;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.08);
            transition:.3s;
            height:100%;
        }

        .action-card:hover{
            transform:translateY(-6px);
            color:white;
            background:linear-gradient(135deg,#2563eb,#7c3aed);
        }

        .action-card i{
            font-size:30px;
            margin-bottom:15px;
            color:#38bdf8;
        }

        .action-card:hover i{
            color:white;
        }

        table{
            color:white !important;
        }

        .table thead{
            background:rgba(255,255,255,.08);
        }

        .table td,
        .table th{
            border-color:rgba(255,255,255,.09);
            vertical-align:middle;
        }

        .badge-active{
            background:#16a34a;
            padding:8px 12px;
            border-radius:20px;
        }

        .badge-inactive{
            background:#ef4444;
            padding:8px 12px;
            border-radius:20px;
        }

        @media(max-width:900px){
            .sidebar{
                position:relative;
                width:100%;
                height:auto;
            }

            .main{
                margin-left:0;
            }

            .topbar{
                flex-direction:column;
                gap:15px;
                align-items:flex-start;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="brand">
        Smart <span>LMS</span> Admin
    </div>

    <div class="menu">
        <a href="admin_dashboard.php" class="active">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>

        <a href="manage_students.php">
            <i class="fa-solid fa-users"></i> Manage Students
        </a>

        <a href="add_student.php">
            <i class="fa-solid fa-user-plus"></i> Add Student
        </a>

        <a href="manage_courses.php">
            <i class="fa-solid fa-book-open"></i> Manage Courses
        </a>

        <a href="add_course.php">
            <i class="fa-solid fa-plus"></i> Add Course
        </a>

        <a href="reports.php">
            <i class="fa-solid fa-chart-pie"></i> Reports
        </a>

        <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>

<div class="main">

    <div class="topbar">
        <div>
            <h2>Admin Control Center 👑</h2>
            <small>Manage students, courses, reports and LMS performance.</small>
        </div>

        <div class="admin-box">
            <div class="admin-avatar">
                <?php echo strtoupper(substr($admin['name'],0,1)); ?>
            </div>
            <div>
                <strong><?php echo htmlspecialchars($admin['name']); ?></strong><br>
                <small>Super Admin</small>
            </div>
        </div>
    </div>

    <div class="hero">
        <h1>Welcome Back, Admin 🚀</h1>
        <p>
            This premium dashboard gives you complete control over students, courses,
            activity status and LMS management.
        </p>

        <a href="add_student.php" class="btn btn-light mt-2">
            <i class="fa-solid fa-user-plus"></i> Add Student
        </a>

        <a href="manage_courses.php" class="btn btn-outline-light mt-2 ms-2">
            <i class="fa-solid fa-book"></i> Manage Courses
        </a>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="stat-card s1">
                <i class="fa-solid fa-users"></i>
                <h3><?php echo $total_students; ?></h3>
                <p>Total Students</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card s2">
                <i class="fa-solid fa-user-check"></i>
                <h3><?php echo $active_students; ?></h3>
                <p>Active Students</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card s3">
                <i class="fa-solid fa-book-open-reader"></i>
                <h3><?php echo $total_courses; ?></h3>
                <p>Total Courses</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card s4">
                <i class="fa-solid fa-user-shield"></i>
                <h3><?php echo $total_admins; ?></h3>
                <p>Total Admins</p>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-5">
            <div class="panel">
                <h4>Quick Admin Powers ⚡</h4>

                <div class="row g-3">

                    <div class="col-md-6">
                        <a href="add_student.php" class="action-card">
                            <i class="fa-solid fa-user-plus"></i>
                            <h6>Add Student</h6>
                            <small>Create new student account</small>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="manage_students.php" class="action-card">
                            <i class="fa-solid fa-users-gear"></i>
                            <h6>Manage Students</h6>
                            <small>Edit, delete and check status</small>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="add_course.php" class="action-card">
                            <i class="fa-solid fa-square-plus"></i>
                            <h6>Add Course</h6>
                            <small>Create new LMS course</small>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="manage_courses.php" class="action-card">
                            <i class="fa-solid fa-book"></i>
                            <h6>Manage Courses</h6>
                            <small>Update course details</small>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel">
                <h4>Latest Students</h4>

                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Role</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($students)){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <?php if($row['status'] == 'active'){ ?>
                                        <span class="badge-active">Active</span>
                                    <?php } else { ?>
                                        <span class="badge-inactive">Inactive</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

                <a href="manage_students.php" class="btn btn-primary mt-2">
                    View All Students
                </a>
            </div>
        </div>

    </div>

    <div class="panel">
        <h4>System Overview</h4>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="action-card">
                    <i class="fa-solid fa-server"></i>
                    <h6>Server Status</h6>
                    <small>System running smoothly</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="action-card">
                    <i class="fa-solid fa-database"></i>
                    <h6>Database Connected</h6>
                    <small>MySQL connection active</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="action-card">
                    <i class="fa-solid fa-lock"></i>
                    <h6>Admin Security</h6>
                    <small>Protected admin session</small>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>