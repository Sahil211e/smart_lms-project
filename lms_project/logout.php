<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out | Smart LMS</title>

    <meta http-equiv="refresh" content="3;url=index.php">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #0f172a, #1e293b, #0f172a);
            overflow:hidden;
            position:relative;
        }

        .bg-glow{
            position:absolute;
            width:300px;
            height:300px;
            background:rgba(59,130,246,0.25);
            filter:blur(120px);
            border-radius:50%;
            top:10%;
            left:10%;
            animation: float 6s ease-in-out infinite;
        }

        .bg-glow2{
            position:absolute;
            width:250px;
            height:250px;
            background:rgba(16,185,129,0.20);
            filter:blur(110px);
            border-radius:50%;
            bottom:10%;
            right:10%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float{
            0%,100%{ transform:translateY(0px); }
            50%{ transform:translateY(-20px); }
        }

        .logout-card{
            position:relative;
            z-index:2;
            width:100%;
            max-width:520px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(18px);
            border:1px solid rgba(255,255,255,0.15);
            border-radius:24px;
            padding:45px 35px;
            text-align:center;
            box-shadow:0 20px 60px rgba(0,0,0,0.35);
            color:white;
        }

        .logout-icon{
            width:90px;
            height:90px;
            margin:0 auto 20px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:40px;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            box-shadow:0 10px 30px rgba(59,130,246,0.4);
        }

        .logout-card h1{
            font-size:30px;
            font-weight:700;
            margin-bottom:12px;
        }

        .logout-card p{
            font-size:16px;
            color:#dbeafe;
            margin-bottom:25px;
        }

        .btn-premium{
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            border:none;
            color:white;
            padding:12px 28px;
            border-radius:12px;
            font-size:16px;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
            transition:0.3s;
            box-shadow:0 8px 20px rgba(37,99,235,0.35);
        }

        .btn-premium:hover{
            transform:translateY(-2px);
            color:white;
            box-shadow:0 12px 28px rgba(37,99,235,0.45);
        }

        .small-text{
            margin-top:18px;
            font-size:14px;
            color:#cbd5e1;
        }

        .loader{
            width:50px;
            height:50px;
            border:4px solid rgba(255,255,255,0.2);
            border-top:4px solid #38bdf8;
            border-radius:50%;
            margin:20px auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin{
            100%{ transform:rotate(360deg); }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>
    <div class="bg-glow2"></div>

    <div class="logout-card">
        <div class="logout-icon">✓</div>
        <h1>Logged Out Successfully</h1>
        <p>You have been securely signed out from <b>Smart LMS</b>.</p>

        <div class="loader"></div>

        <a href="index.php" class="btn-premium">Go to Home</a>

        <div class="small-text">
            Redirecting to homepage in 3 seconds...
        </div>
    </div>

</body>
</html>