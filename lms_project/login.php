<?php
session_start();
include 'includes/db.php';

$error = "";
$email = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = md5(trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user'] = [
            'id' => $user['id'] ?? '',
            'name' => $user['name'] ?? 'Student',
            'email' => $user['email'] ?? '',
            'role' => $user['role'] ?? 'Student'
        ];

        header("Location: dashboard.php");
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
    <title>Login | Smart LMS</title>
	

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
                linear-gradient(rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.45)),
                url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80')
                no-repeat center center/cover;
            position:relative;
            overflow:hidden;
        }

        body::before{
            content:"";
            position:absolute;
            inset:0;
            background:rgba(255,255,255,0.03);
            backdrop-filter:blur(4px);
            z-index:0;
        }

        .login-container{
            position:relative;
            z-index:2;
            width:100%;
            max-width:460px;
            padding:20px;
        }

        .login-box{
            background:rgba(255,255,255,0.10);
            border:1px solid rgba(255,255,255,0.20);
            backdrop-filter:blur(18px);
            -webkit-backdrop-filter:blur(18px);
            border-radius:24px;
            padding:35px 30px;
            box-shadow:0 20px 50px rgba(0,0,0,0.35);
            text-align:center;
            color:#fff;
            transition:0.35s ease;
        }

        .login-box:hover{
            transform:translateY(-4px);
            box-shadow:0 25px 60px rgba(0,0,0,0.40);
        }

        .login-box h2{
            font-size:34px;
            font-weight:700;
            margin-bottom:8px;
            text-shadow:0 2px 10px rgba(0,0,0,0.25);
        }

        .login-box p{
            font-size:15px;
            color:rgba(255,255,255,0.88);
            margin-bottom:28px;
        }

        .input-box{
            position:relative;
            margin-bottom:18px;
        }

        .input-box input{
            width:100%;
            height:54px;
            border:none;
            outline:none;
            border-radius:35px;
            padding:0 52px 0 18px;
            font-size:15px;
            color:#fff;
            background:rgba(255,255,255,0.14);
            box-shadow:
                inset 0 1px 2px rgba(255,255,255,0.12),
                0 8px 24px rgba(0,0,0,0.12);
            transition:0.3s ease;
        }

        .input-box input::placeholder{
            color:rgba(255,255,255,0.82);
        }

        .input-box input:focus{
            background:rgba(255,255,255,0.18);
            box-shadow:0 0 0 2px rgba(246, 200, 168, 0.55);
        }

        .input-icon{
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            color:rgba(255,255,255,0.88);
            font-size:15px;
            pointer-events:none;
        }

        .toggle-password{
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            color:rgba(255,255,255,0.88);
            cursor:pointer;
            font-size:15px;
        }

        .btn-login{
            width:100%;
            height:54px;
            border:none;
            outline:none;
            border-radius:35px;
            background:linear-gradient(135deg, #ffd3b6, #f6b48f);
            color:#1f2937;
            font-size:16px;
            font-weight:800;
            letter-spacing:0.8px;
            cursor:pointer;
            margin-top:4px;
            margin-bottom:18px;
            box-shadow:0 10px 25px rgba(246, 180, 143, 0.35);
            transition:0.3s ease;
        }

        .btn-login:hover{
            transform:translateY(-2px) scale(1.01);
            background:linear-gradient(135deg, #ffdcca, #f4b08a);
        }

        .options{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            font-size:13px;
            margin-bottom:22px;
            color:#fff;
            flex-wrap:wrap;
        }

        .options label{
            display:flex;
            align-items:center;
            gap:7px;
            cursor:pointer;
        }

        .options input[type="checkbox"]{
            accent-color:#f6c8a8;
        }

        .options a{
            color:#fff;
            text-decoration:none;
        }

        .options a:hover{
            text-decoration:underline;
        }

        .divider{
            position:relative;
            color:#fff;
            font-size:14px;
            margin:8px 0 18px;
        }

        .divider::before,
        .divider::after{
            content:"";
            position:absolute;
            top:50%;
            width:28%;
            height:1px;
            background:rgba(255,255,255,0.50);
        }

        .divider::before{
            left:0;
        }

        .divider::after{
            right:0;
        }

        .social-login{
            display:flex;
            gap:12px;
        }

        .social-login a{
            flex:1;
            text-decoration:none;
            padding:12px 10px;
            border-radius:10px;
            font-size:15px;
            font-weight:600;
            text-align:center;
            transition:0.3s ease;
        }

        .social-login a:first-child{
            background:#1877f2;
            color:#fff;
        }

        .social-login a:last-child{
            background:#1da1f2;
            color:#fff;
        }

        .social-login a:hover{
            transform:translateY(-2px);
            opacity:0.92;
        }

        .error-msg{
            background:rgba(255, 77, 77, 0.18);
            color:#fff;
            border:1px solid rgba(255,255,255,0.20);
            padding:12px 15px;
            border-radius:12px;
            margin-bottom:18px;
            font-size:14px;
            text-align:left;
        }

        @media (max-width: 480px){
            .login-box{
                padding:28px 20px;
                border-radius:20px;
            }

            .login-box h2{
                font-size:28px;
            }

            .social-login{
                flex-direction:column;
            }

            .divider::before,
            .divider::after{
                width:22%;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <p>Login to your Smart LMS account</p>

            <?php if (!empty($error)) { ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST" action="">
                <div class="input-box">
                    <input type="email" name="email" placeholder="Enter your Email" required value="<?php echo htmlspecialchars($email); ?>">
                    <i class="fa-solid fa-envelope input-icon"></i>
                </div>

                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Enter Password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <button type="submit" name="login" class="btn-login">SIGN IN</button>


                <div class="options">
                    <label><input type="checkbox"> Remember Me</label>
                    <a href="#">Forgot Password?</a>
                </div>

                <div class="divider">Or Sign In With</div>

                <div class="social-login">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
                    <a href="#"><i class="fa-brands fa-twitter"></i> Twitter</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>