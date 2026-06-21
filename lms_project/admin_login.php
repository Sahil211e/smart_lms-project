<?php
session_start();
include 'includes/db.php';

$error = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='admin'";
    $res = mysqli_query($conn,$sql);

    if(mysqli_num_rows($res)==1){
        $_SESSION['admin'] = mysqli_fetch_assoc($res);
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Login failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Smart LMS</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    linear-gradient(rgba(15,23,42,0.55), rgba(15,23,42,0.55)),
    url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80')
    no-repeat center/cover;
}

/* 🔥 BACK BUTTON */
.back-btn{
    position:absolute;
    top:25px;
    left:25px;
    padding:10px 22px;
    border-radius:30px;
    background:linear-gradient(135deg,#0ea5e9,#2563eb);
    color:white;
    text-decoration:none;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:6px;
    box-shadow:0 6px 20px rgba(37,99,235,0.5);
    transition:0.3s;
}

.back-btn:hover{
    transform:translateY(-3px) scale(1.05);
    box-shadow:0 10px 30px rgba(37,99,235,0.7);
}

/* Glass Card */
.login-box{
    width:380px;
    padding:35px 30px;
    border-radius:25px;
    backdrop-filter:blur(18px);
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.2);
    box-shadow:0 20px 50px rgba(0,0,0,0.35);
    color:white;
    text-align:center;
    transition:0.3s;
}

.login-box:hover{
    transform:translateY(-5px);
}

.login-box h2{
    font-size:30px;
    margin-bottom:8px;
}

.login-box p{
    font-size:14px;
    margin-bottom:25px;
    color:rgba(255,255,255,0.85);
}

/* Input */
.input-box{
    position:relative;
    margin-bottom:18px;
}

.input-box input{
    width:100%;
    height:50px;
    border:none;
    outline:none;
    border-radius:30px;
    padding:0 45px 0 15px;
    background:rgba(255,255,255,0.15);
    color:white;
}

.input-box i{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
}

/* Button */
.btn-login{
    width:100%;
    height:50px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#3b82f6,#06b6d4);
    color:white;
    font-weight:bold;
    letter-spacing:1px;
    cursor:pointer;
    transition:0.3s;
}

.btn-login:hover{
    transform:scale(1.03);
    box-shadow:0 10px 30px rgba(59,130,246,0.6);
}

/* Error */
.error{
    background:rgba(255,0,0,0.2);
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:14px;
}

/* Title glow */
.logo{
    font-size:40px;
    margin-bottom:10px;
    color:#60a5fa;
}
</style>
</head>

<body>

<!-- 🔙 BACK BUTTON ADDED -->
<a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Home
</a>

<div class="login-box">

    <div class="logo">
        <i class="fa-solid fa-user-shield"></i>
    </div>

    <h2>Admin Panel</h2>
    <p>Secure access to control system</p>

    <?php if(!empty($error)){ ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <div class="input-box">
            <input type="email" name="email" placeholder="Admin Email" required>
            <i class="fa-solid fa-envelope"></i>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class="fa-solid fa-lock"></i>
        </div>

        <button class="btn-login" name="login">LOGIN</button>
    </form>

</div>

</body>
</html>