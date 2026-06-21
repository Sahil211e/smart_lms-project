<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM courses WHERE id='$id'");
    header("Location: manage_courses.php");
}

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $courses = mysqli_query($conn,"SELECT * FROM courses WHERE course_name LIKE '%$search%' ORDER BY id DESC");
}else{
    $courses = mysqli_query($conn,"SELECT * FROM courses ORDER BY id ASC");
}

/* COUNT */
$total_courses = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM courses"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Courses | Smart LMS</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* BODY */
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#eef5ff,#f8fbff);
}

/* SIDEBAR */
.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    background:rgba(255,255,255,0.8);
    backdrop-filter:blur(15px);
    border-right:1px solid #e5e7eb;
    padding:25px;
}

.brand{
    font-size:24px;
    font-weight:900;
    margin-bottom:40px;
}

.brand span{color:#2563eb;}

/* MENU */
.menu a{
    display:flex;
    gap:10px;
    padding:12px;
    margin-bottom:10px;
    color:#334155;
    text-decoration:none;
    border-radius:12px;
    transition:.3s;
}

.menu a:hover,
.menu a.active{
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    transform:translateX(5px);
}

/* MAIN */
.main{
    margin-left:260px;
    padding:30px;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:white;
    padding:18px 25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

/* CARD */
.stat-card{
    margin-top:20px;
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    padding:20px;
    border-radius:20px;
}

/* PANEL */
.panel{
    margin-top:25px;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,.05);
}

/* TABLE */
.table thead{
    background:#f1f5f9;
}

.table tr{
    transition:.2s;
}

.table tr:hover{
    background:#f8fbff;
}

/* BUTTONS */
.btn-main{
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:10px;
}

.btn-delete{
    background:#ef4444;
    color:white;
    padding:6px 10px;
    border-radius:8px;
    border:none;
}

.btn-delete:hover{
    background:#dc2626;
}

/* SEARCH */
.search-box input{
    border-radius:10px;
    padding:10px;
    border:1px solid #e5e7eb;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">Smart <span>LMS</span></div>

    <div class="menu">
        <a href="admin_dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="manage_students.php"><i class="fa fa-users"></i> Students</a>
        <a href="manage_courses.php" class="active"><i class="fa fa-book"></i> Courses</a>
        <a href="add_course.php"><i class="fa fa-plus"></i> Add Course</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h4>📚 Manage Courses</h4>

        <a href="add_course.php" class="btn-main">
            <i class="fa fa-plus"></i> Add Course
        </a>
    </div>

    <!-- STATS -->
    <div class="stat-card">
        <h3><?php echo $total_courses; ?> Courses</h3>
        <small>Total courses available in LMS</small>
    </div>

    <!-- PANEL -->
    <div class="panel">

        <!-- SEARCH -->
        <form method="GET" class="search-box mb-3">
            <input type="text" name="search" placeholder="Search course..." value="<?php echo $search; ?>">
            <button class="btn-main">Search</button>
        </form>

        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($courses)){ ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>

                    <td>
                        <i class="fa fa-book text-primary"></i>
                        <?php echo $row['course_name']; ?>
                    </td>

                    <td><?php echo $row['description']; ?></td>

                    <td>
                        <button onclick="return confirm('Delete this course?')" 
                        onclick="window.location='?delete=<?php echo $row['id']; ?>'" 
                        class="btn-delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    </div>

</div>

</body>
</html>