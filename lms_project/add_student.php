<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$admin = $_SESSION['admin'];

if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    mysqli_query($conn,"INSERT INTO users(name,email,password,role,status)
    VALUES('$name','$email','$password','student','active')");

    $msg = "Student Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Student | Smart LMS</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* BODY */
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#f8fbff,#eef5ff);
    color:#0f172a;
}

/* SIDEBAR */
.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(15px);
    border-right:1px solid rgba(0,0,0,0.05);
    padding:25px;
}

.brand{
    font-size:24px;
    font-weight:900;
    margin-bottom:45px;
}

.brand span{
    color:#2563eb;
}

/* MENU */
.menu a{
    display:flex;
    gap:12px;
    padding:12px;
    margin-bottom:12px;
    color:#334155;
    text-decoration:none;
    border-radius:12px;
    font-weight:600;
    transition:0.3s;
}

.menu a:hover{
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
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(10px);
    border:1px solid rgba(0,0,0,0.05);
    padding:18px 25px;
    border-radius:20px;
    margin-bottom:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.admin-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
}

/* CARD (FORM BOX) */
.card-box{
    max-width:550px;
    margin:auto;
    background:rgba(255,255,255,0.8);
    backdrop-filter:blur(12px);
    border-radius:25px;
    padding:35px;
    border:1px solid rgba(0,0,0,0.05);
    box-shadow:0 20px 60px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card-box:hover{
    transform:translateY(-5px);
}

/* HEADING */
.card-box h3{
    font-weight:900;
    margin-bottom:25px;
}

/* INPUT */
.form-control{
    border-radius:12px;
    padding:12px;
    border:1px solid #e2e8f0;
    transition:0.2s;
}

.form-control:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,0.15);
}

/* BUTTON */
.btn-main{
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    border:none;
    padding:12px;
    border-radius:12px;
    font-weight:700;
    transition:0.3s;
}

.btn-main:hover{
    transform:scale(1.03);
    box-shadow:0 10px 25px rgba(37,99,235,0.3);
}

/* ALERT */
.alert{
    border-radius:12px;
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
        <a href="add_student.php"><i class="fa fa-user-plus"></i> Add Student</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <h4>Add Student</h4>

        <div class="d-flex align-items-center gap-2">
            <div class="admin-avatar">
                <?php echo strtoupper(substr($admin['name'],0,1)); ?>
            </div>
            <strong><?php echo $admin['name']; ?></strong>
        </div>
    </div>

    <!-- FORM -->
    <div class="card-box">

        <h3>Add New Student</h3>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button name="add" class="btn-main w-100">
                <i class="fa fa-user-plus"></i> Add Student
            </button>

        </form>

    </div>

</div>

</body>
</html>