<?php
session_start();
include 'includes/db.php';

$success = "";
$error = "";

// Login ho to session user, warna default id=1
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 1;
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("User not found.");
}

if (isset($_POST['update_settings'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND id!='$user_id'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "This email is already in use.";
    } else {
        if (!empty($new_password) || !empty($confirm_password)) {
            if ($new_password != $confirm_password) {
                $error = "Passwords do not match.";
            } else {
                $hashed_password = md5($new_password);
                $update = mysqli_query($conn, "UPDATE users SET name='$name', email='$email', password='$hashed_password' WHERE id='$user_id'");

                if ($update) {
                    $success = "Settings updated successfully.";
                    $_SESSION['user_name'] = $name;
                } else {
                    $error = "Something went wrong while updating.";
                }
            }
        } else {
            $update = mysqli_query($conn, "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'");

            if ($update) {
                $success = "Settings updated successfully.";
                $_SESSION['user_name'] = $name;
            } else {
                $error = "Something went wrong while updating.";
            }
        }
    }

    $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Smart LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            min-height:100vh;
            font-family:'Segoe UI',sans-serif;
            background:
                radial-gradient(circle at top left, rgba(59,130,246,0.25), transparent 30%),
                radial-gradient(circle at top right, rgba(168,85,247,0.20), transparent 30%),
                radial-gradient(circle at bottom center, rgba(6,182,212,0.18), transparent 35%),
                linear-gradient(135deg, #020617, #0f172a, #111827, #1e293b);
            color:#fff;
            overflow-x:hidden;
        }

        .floating-blur{
            position:fixed;
            border-radius:50%;
            filter:blur(80px);
            z-index:0;
            opacity:0.35;
        }

        .blur1{
            width:260px;
            height:260px;
            background:#3b82f6;
            top:40px;
            left:20px;
        }

        .blur2{
            width:280px;
            height:280px;
            background:#8b5cf6;
            bottom:40px;
            right:30px;
        }

        .blur3{
            width:220px;
            height:220px;
            background:#06b6d4;
            top:45%;
            left:45%;
            transform:translate(-50%, -50%);
        }

        .settings-section{
            position:relative;
            z-index:2;
            padding:50px 12px;
        }

        .settings-card{
            max-width:980px;
            margin:auto;
            background:rgba(255,255,255,0.07);
            border:1px solid rgba(255,255,255,0.10);
            backdrop-filter:blur(22px);
            -webkit-backdrop-filter:blur(22px);
            border-radius:30px;
            overflow:hidden;
            box-shadow:
                0 25px 70px rgba(0,0,0,0.45),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .settings-header{
            position:relative;
            padding:38px 35px;
            background:linear-gradient(135deg, rgba(14,165,233,0.95), rgba(59,130,246,0.92), rgba(139,92,246,0.92));
            overflow:hidden;
        }

        .settings-header::before{
            content:"";
            position:absolute;
            width:220px;
            height:220px;
            background:rgba(255,255,255,0.08);
            border-radius:50%;
            top:-90px;
            right:-80px;
        }

        .settings-header::after{
            content:"";
            position:absolute;
            width:140px;
            height:140px;
            background:rgba(255,255,255,0.07);
            border-radius:50%;
            bottom:-50px;
            left:-40px;
        }

        .header-content{
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            flex-wrap:wrap;
        }

        .header-left h2{
            font-size:34px;
            font-weight:800;
            margin-bottom:8px;
            letter-spacing:0.3px;
        }

        .header-left p{
            margin:0;
            font-size:15px;
            color:rgba(255,255,255,0.9);
        }

        .header-icon{
            width:78px;
            height:78px;
            border-radius:22px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:30px;
            background:rgba(255,255,255,0.12);
            border:1px solid rgba(255,255,255,0.16);
            box-shadow:0 10px 30px rgba(0,0,0,0.18);
        }

        .settings-body{
            padding:35px;
        }

        .profile-mini{
            display:flex;
            align-items:center;
            gap:18px;
            margin-bottom:30px;
            padding:22px;
            border-radius:22px;
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.08);
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.04);
        }

        .avatar{
            width:74px;
            height:74px;
            border-radius:20px;
            background:linear-gradient(135deg,#06b6d4,#3b82f6,#8b5cf6);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
            font-weight:700;
            color:#fff;
            box-shadow:0 12px 30px rgba(59,130,246,0.28);
            flex-shrink:0;
        }

        .profile-mini h4{
            margin:0 0 6px;
            font-size:23px;
            font-weight:700;
            color:#fff;
        }

        .profile-mini p{
            margin:0;
            color:#cbd5e1;
            font-size:14px;
        }

        .form-panel{
            padding:28px;
            border-radius:24px;
            background:rgba(255,255,255,0.035);
            border:1px solid rgba(255,255,255,0.08);
        }

        .section-title{
            font-size:20px;
            font-weight:700;
            margin-bottom:22px;
            color:#fff;
        }

        .form-label{
            color:#e2e8f0;
            font-weight:600;
            margin-bottom:8px;
        }

        .form-control{
            height:54px;
            border-radius:16px;
            border:1px solid rgba(255,255,255,0.11);
            background:rgba(255,255,255,0.08);
            color:#fff;
            padding:12px 16px;
            transition:0.25s ease;
        }

        .form-control:focus{
            color:#fff;
            background:rgba(255,255,255,0.12);
            border-color:#60a5fa;
            box-shadow:0 0 0 4px rgba(96,165,250,0.12);
        }

        .form-control::placeholder{
            color:#94a3b8;
        }

        .custom-alert{
            border:none;
            border-radius:16px;
            padding:14px 16px;
            font-weight:600;
            margin-bottom:20px;
        }

        .alert-success.custom-alert{
            background:rgba(34,197,94,0.18);
            color:#bbf7d0;
            border-left:4px solid #22c55e;
        }

        .alert-danger.custom-alert{
            background:rgba(239,68,68,0.18);
            color:#fecaca;
            border-left:4px solid #ef4444;
        }

        .action-bar{
            display:flex;
            gap:14px;
            flex-wrap:wrap;
            margin-top:10px;
        }

        .btn-save{
            border:none;
            color:#fff;
            font-weight:700;
            padding:14px 26px;
            border-radius:16px;
            background:linear-gradient(90deg,#06b6d4,#2563eb,#7c3aed);
            box-shadow:0 14px 30px rgba(37,99,235,0.30);
            transition:0.3s ease;
        }

        .btn-save:hover{
            transform:translateY(-2px);
            color:#fff;
            box-shadow:0 18px 34px rgba(37,99,235,0.38);
        }

        .btn-back{
            padding:14px 24px;
            border-radius:16px;
            text-decoration:none;
            font-weight:700;
            color:#fff;
            border:1px solid rgba(255,255,255,0.13);
            background:rgba(255,255,255,0.05);
            transition:0.25s ease;
        }

        .btn-back:hover{
            color:#fff;
            background:rgba(255,255,255,0.09);
            transform:translateY(-2px);
        }

        .small-note{
            margin-top:20px;
            color:#94a3b8;
            font-size:13px;
        }

        @media(max-width:768px){
            .settings-header{
                padding:30px 22px;
            }

            .settings-body{
                padding:22px;
            }

            .form-panel{
                padding:20px;
            }

            .header-left h2{
                font-size:27px;
            }

            .profile-mini{
                padding:18px;
            }

            .avatar{
                width:62px;
                height:62px;
                border-radius:16px;
                font-size:22px;
            }

            .profile-mini h4{
                font-size:20px;
            }
        }
    </style>
</head>
<body>

<div class="floating-blur blur1"></div>
<div class="floating-blur blur2"></div>
<div class="floating-blur blur3"></div>

<div class="container settings-section">
    <div class="settings-card">

        <div class="settings-header">
            <div class="header-content">
                <div class="header-left">
                    <h2><i class="fa-solid fa-sliders"></i> Account Settings</h2>
                    <p>Control and manage your Smart LMS account with a premium dashboard experience.</p>
                </div>
                <div class="header-icon">
                    <i class="fa-solid fa-gear"></i>
                </div>
            </div>
        </div>

        <div class="settings-body">

            <div class="profile-mini">
                <div class="avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div>
                    <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

            <?php if($success != "") { ?>
                <div class="alert alert-success custom-alert"><?php echo $success; ?></div>
            <?php } ?>

            <?php if($error != "") { ?>
                <div class="alert alert-danger custom-alert"><?php echo $error; ?></div>
            <?php } ?>

            <div class="form-panel">
                <div class="section-title">Update Your Information</div>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="action-bar">
                        <button type="submit" name="update_settings" class="btn-save">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                        </button>

                        <a href="dashboard.php" class="btn-back">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>

                    <div class="small-note">
                        Leave password fields empty if you only want to update your name or email.
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>