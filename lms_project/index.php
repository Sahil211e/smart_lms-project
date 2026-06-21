<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart LMS | Learn Anytime</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0b1220;
            color: white;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(10px);
        }

        /* HERO */
        .hero {
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644') center/cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(0,0,0,0.8), rgba(99,102,241,0.5));
        }

        .hero-content {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            text-align: center;
        }

        .hero h1 {
            font-size: 60px;
            font-weight: 800;
        }

        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .section {
            padding: 80px 0;
        }

        /* COURSE CARD */
        .course-card {
            background: white;
            color: black;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
        }

        .course-card:hover {
            transform: translateY(-8px);
        }

        .content {
            padding: 15px;
        }

        .course-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-top: 10px;
        }

        /* STATS */
        .stat-box {
            background: rgba(255,255,255,0.05);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .stat-box h2 {
            color: #38bdf8;
            font-size: 35px;
        }

        /* FEEDBACK */
        .feedback {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* SOCIAL BAR */
        .social-bar {
            position: fixed;
            left: 15px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .social-bar a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .insta { background: #e4405f; }
        .fb { background: #1877f2; }
        .wa { background: #25D366; }
		/* 🔥 ULTRA PRO ADMIN BUTTON */
.admin-btn {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: white !important;
    padding: 10px 28px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 20px rgba(37,99,235,0.5);
    transition: 0.3s ease;
}

.admin-btn:hover {
    transform: translateY(-3px) scale(1.05);
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    box-shadow: 0 10px 30px rgba(37,99,235,0.7);
}

.admin-btn::after {
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 80%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255,255,255,0.5), transparent);
    transition: 0.5s;
}

.admin-btn:hover::after {
    left: 120%;
}

    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold">🎓 Smart LMS</a>

        <div class="ms-auto">
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="register.php" class="btn btn-warning">Register</a>
			<a href="/lms_project/admin_login.php" class="admin-btn">
    <i class="fas fa-crown"></i> Admin
</a> 
  

        </div>
    </div>
</nav>

<!-- HERO -->
<div class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
        <h1>BUILD YOUR FUTURE</h1>
        <p>Learn Skills | Get Certificates | Get Job Ready 🚀</p>
        <a href="register.php" class="btn btn-primary btn-custom mt-3">Start Learning</a>
    </div>
</div>

<!-- STATS -->
<div class="section text-center">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="stat-box">
                    <h2>10K+</h2>
                    <p>Students</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <h2>100+</h2>
                    <p>Teachers</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <h2>100+</h2>
                    <p>Courses</p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- COURSES -->
<div class="section text-center bg-dark">
    <h2>🔥 Popular Courses</h2>

    <div class="container mt-5">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="course-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/732/732212.png" class="course-logo">
                    <div class="content">
                        <h5>Web Development</h5>
                        <p>HTML, CSS, JS, PHP</p>
                        <button class="btn btn-primary w-100">Enroll</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="course-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2103/2103633.png" class="course-logo">
                    <div class="content">
                        <h5>Data Science</h5>
                        <p>Python, AI, ML</p>
                        <button class="btn btn-primary w-100">Enroll</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="course-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2092/2092063.png" class="course-logo">
                    <div class="content">
                        <h5>Networking</h5>
                        <p>CCNA, Security</p>
                        <button class="btn btn-primary w-100">Enroll</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- FEEDBACK -->
<div class="section text-center">
    <h2>💬 Student Feedback</h2>

    <div class="container mt-5">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="feedback">
                    <p>"Best LMS platform, very helpful."</p>
                    <h6>- Rahul Kumar</h6>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feedback">
                    <p>"Easy learning experience."</p>
                    <h6>- Aman Singh</h6>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feedback">
                    <p>"Got internship because of this LMS."</p>
                    <h6>- Rohan Verma</h6>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SOCIAL MEDIA -->
<div class="social-bar">
    
</div>

</body>
</html>