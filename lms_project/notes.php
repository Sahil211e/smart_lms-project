<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $sql = "SELECT * FROM notes 
            WHERE title LIKE '%$search%' 
            OR subject LIKE '%$search%' 
            OR description LIKE '%$search%' 
            ORDER BY uploaded_at DESC";
} else {
    $sql = "SELECT * FROM notes ORDER BY uploaded_at DESC";
}

$result = mysqli_query($conn, $sql);
$user = $_SESSION['user'];
$name = htmlspecialchars($user['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes | Smart LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI', sans-serif;
        }

        body{
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
            min-height:100vh;
            color:white;
        }

        .navbar{
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        }

        .navbar-brand{
            font-weight:700;
            font-size:24px;
            color:#fff !important;
        }

        .hero{
            padding:50px 0 25px;
        }

        .hero-box{
            background: rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.12);
            border-radius:24px;
            padding:35px;
            backdrop-filter: blur(14px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }

        .hero-box h1{
            font-size:36px;
            font-weight:700;
            margin-bottom:10px;
        }

        .hero-box p{
            color:rgba(255,255,255,0.82);
            margin-bottom:0;
        }

        .search-box{
            margin-top:25px;
        }

        .search-box .form-control{
            height:54px;
            border-radius:14px 0 0 14px;
            border:none;
            box-shadow:none;
            background: rgba(255,255,255,0.92);
        }

        .search-box .btn{
            border-radius:0 14px 14px 0;
            padding:0 24px;
            background: linear-gradient(135deg, #f59e0b, #fb923c);
            border:none;
            font-weight:600;
        }

        .stats-card{
            background: rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.10);
            border-radius:20px;
            padding:20px;
            text-align:center;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            height:100%;
        }

        .stats-card i{
            font-size:30px;
            margin-bottom:12px;
            color:#fbbf24;
        }

        .stats-card h4{
            font-size:26px;
            font-weight:700;
            margin-bottom:4px;
        }

        .stats-card p{
            margin:0;
            color:rgba(255,255,255,0.78);
        }

        .section-title{
            font-size:28px;
            font-weight:700;
            margin:35px 0 20px;
        }

        .note-card{
            background: rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.10);
            border-radius:22px;
            padding:24px;
            backdrop-filter: blur(12px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.18);
            transition:0.3s ease;
            height:100%;
        }

        .note-card:hover{
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.24);
        }

        .note-badge{
            display:inline-block;
            padding:7px 14px;
            border-radius:50px;
            background: rgba(251, 146, 60, 0.18);
            color:#fdba74;
            font-size:13px;
            font-weight:600;
            margin-bottom:14px;
        }

        .note-card h4{
            font-size:22px;
            font-weight:700;
            margin-bottom:12px;
        }

        .note-card p{
            color:rgba(255,255,255,0.82);
            min-height:72px;
        }

        .note-meta{
            font-size:13px;
            color:rgba(255,255,255,0.65);
            margin-bottom:15px;
        }

        .btn-view{
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color:white;
            border:none;
            border-radius:12px;
            padding:10px 18px;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
        }

        .btn-view:hover{
            color:white;
            opacity:0.95;
        }

        .btn-download{
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color:white;
            border:none;
            border-radius:12px;
            padding:10px 18px;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
            margin-left:8px;
        }

        .btn-download:hover{
            color:white;
            opacity:0.95;
        }

        .empty-box{
            background: rgba(255,255,255,0.08);
            border-radius:20px;
            padding:40px;
            text-align:center;
            color:rgba(255,255,255,0.85);
            border:1px solid rgba(255,255,255,0.10);
        }

        @media(max-width:768px){
            .hero-box h1{
                font-size:28px;
            }
            .btn-download{
                margin-left:0;
                margin-top:10px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Smart LMS</a>
        <span class="text-white">Welcome, <?php echo $name; ?></span>
    </div>
</nav>

<div class="container hero">
    <div class="hero-box">
        <h1><i class="fa-solid fa-book-open-reader"></i> Smart Notes Library</h1>
        <p>Access your premium study notes, resources and quick revision materials in one place.</p>

        <form method="GET" class="search-box">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search notes by title, subject or keyword..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn text-white" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </div>
        </form>
    </div>

    <?php
    $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM notes");
    $count_data = mysqli_fetch_assoc($count_result);
    $total_notes = $count_data['total'] ?? 0;
    ?>

    <div class="row mt-4 g-4">
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fa-solid fa-file-lines"></i>
                <h4><?php echo $total_notes; ?></h4>
                <p>Total Notes</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fa-solid fa-layer-group"></i>
                <h4>4+</h4>
                <p>Subjects Covered</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fa-solid fa-bolt"></i>
                <h4>24/7</h4>
                <p>Quick Access</p>
            </div>
        </div>
    </div>

    <h2 class="section-title">Available Notes</h2>

    <div class="row g-4">
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="note-card">
                        <span class="note-badge"><?php echo htmlspecialchars($row['subject']); ?></span>
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="note-meta">
                            <i class="fa-regular fa-clock"></i>
                            Uploaded: <?php echo date("d M Y", strtotime($row['uploaded_at'])); ?>
                        </div>

                        <a href="uploads/notes/<?php echo urlencode($row['file_name']); ?>" target="_blank" class="btn-view">
                            <i class="fa-solid fa-eye"></i> View
                        </a>

                        <a href="uploads/notes/<?php echo urlencode($row['file_name']); ?>" download class="btn-download">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <div class="empty-box">
                    <h4>No notes found</h4>
                    <p>Try searching with another keyword or add some notes in the database.</p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>