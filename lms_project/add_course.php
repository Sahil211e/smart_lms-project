<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

/* ADD COURSE */
if(isset($_POST['add'])){
    $name = $_POST['course_name'];
    $desc = $_POST['description'];

    mysqli_query($conn,"INSERT INTO courses(course_name, description)
    VALUES('$name','$desc')");

    $msg = "Course Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Course | Smart LMS</title>

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
    background:white;
    padding:25px;
    border-right:1px solid #e5e7eb;
}

.brand{
    font-size:22px;
    font-weight:900;
    margin-bottom:40px;
}

.brand span{color:#2563eb;}

.menu a{
    display:flex;
    gap:10px;
    padding:12px;
    margin-bottom:10px;
    color:#334155;
    text-decoration:none;
    border-radius:10px;
}

.menu a:hover{
    background:#eaf1ff;
    color:#2563eb;
}

/* MAIN */
.main{
    margin-left:260px;
    padding:25px;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    background:white;
    padding:18px;
    border-radius:15px;
    border:1px solid #e5e7eb;
}

/* CARD */
.card-box{
    max-width:550px;
    margin:30px auto;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,.05);
}

/* INPUT */
.form-control{
    border-radius:10px;
}

/* BUTTON */
.btn-main{
    background:#2563eb;
    color:white;
    border:none;
    padding:10px;
    border-radius:10px;
    width:100%;
}

.btn-main:hover{
    background:#1d4ed8;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">Smart <span>LMS</span></div>

    <div class="menu">
        <a href="admin_dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="manage_courses.php"><i class="fa fa-book"></i> Courses</a>
        <a href="add_course.php"><i class="fa fa-plus"></i> Add Course</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h4>➕ Add Course</h4>
        <a href="manage_courses.php" class="btn btn-sm btn-secondary">Back</a>
    </div>

    <!-- FORM -->
    <div class="card-box">

        <h3>Add New Course</h3>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success mt-2"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label>Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <button name="add" class="btn-main">
                <i class="fa fa-plus"></i> Add Course
            </button>

        </form>

    </div>

</div>

</body>
</html>