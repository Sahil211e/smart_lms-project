<?php
include "includes/db.php";

$msg = "";

if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $sql = "INSERT INTO users (name, email, password, role) 
            VALUES ('$name', '$email', '$password', 'student')";

    if(mysqli_query($conn, $sql)){
        $msg = "Registered Successfully 🎉";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register | LMS</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;   /* ✅ WHITE CLEAN BACKGROUND */
}

/* CENTER BOX */
.wrapper {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.form-box {
    width: 380px;
    padding: 30px;
    border-radius: 15px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

/* ICON */
.icon {
    font-size: 40px;
    color: #0d6efd;
    margin-bottom: 10px;
}

/* TITLE */
h2 {
    font-weight: 700;
    margin-bottom: 5px;
}

/* INPUT */
.form-control {
    padding: 12px;
    border-radius: 10px;
}

/* BUTTON */
.btn-custom {
    width: 100%;
    padding: 12px;
    border-radius: 50px;
    background: #0d6efd;
    color: white;
    font-weight: 600;
    border: none;
    transition: 0.3s;
}

.btn-custom:hover {
    background: #0b5ed7;
    transform: scale(1.03);
}

/* MESSAGE */
.msg {
    font-size: 14px;
    color: green;
    margin-bottom: 10px;
}

</style>
</head>

<body>

<div class="wrapper">

    <div class="form-box">

        <div class="icon">
            <i class="fas fa-user-graduate"></i>
        </div>

        <h2>Register</h2>
        <p>Create your LMS account</p>

        <?php if($msg != "") echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST">

            <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button name="register" class="btn btn-custom">Register</button>

        </form>

    </div>

</div>

</body>
</html>