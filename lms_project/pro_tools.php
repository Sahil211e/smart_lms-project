<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Pro Tools</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    font-family:'Segoe UI',sans-serif;
}

.container-pro{
    max-width:1100px;
    margin:60px auto;
}

.card-pro{
    border-radius:20px;
    padding:25px;
    background:white;
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card-pro:hover{
    transform:translateY(-5px);
}

h2{
    font-weight:800;
    margin-bottom:20px;
}

.btn-pro{
    background:linear-gradient(135deg,#4f46e5,#3b82f6);
    color:white;
    border:none;
}

</style>

</head>

<body>

<div class="container-pro">

    <h2>🚀 Student Pro Tools</h2>
    <p class="mb-4">Access smart notes, AI quiz support and premium performance analytics.</p>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card-pro">
                <h5>📘 Smart Notes</h5>
                <p>Create and manage your notes easily.</p>
                <a href="/lms_project/quiz.php" class="btn btn-pro w-100">Start Quiz</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-pro">
                <h5>🧠 AI Quiz</h5>
                <p>Practice quizzes and improve your knowledge.</p>
                <button class="btn btn-pro w-100">Start Quiz</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-pro">
                <h5>📊 Analytics</h5>
                <p>Track your performance and progress.</p>
                <button class="btn btn-pro w-100">View Analytics</button>
            </div>
        </div>

    </div>

</div>

</body>
</html>