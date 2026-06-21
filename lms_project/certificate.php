<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$student_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Demo Student";
$student_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "student@example.com";

$course_name = "Advanced Smart LMS Professional Program";
$issue_date = date("d F Y");
$certificate_no = "CERT-" . date("Y") . "-" . strtoupper(substr(md5($student_name), 0, 8));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Certificate | Smart LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                radial-gradient(circle at top left, rgba(251,191,36,0.12), transparent 30%),
                radial-gradient(circle at top right, rgba(59,130,246,0.10), transparent 30%),
                linear-gradient(135deg, #020617, #0f172a, #111827);
            padding:30px 15px;
        }

        .top-bar{
            max-width:1300px;
            margin:0 auto 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .btn-pro{
            text-decoration:none;
            color:#fff;
            font-weight:700;
            padding:13px 24px;
            border-radius:14px;
            border:none;
            background:linear-gradient(90deg,#f59e0b,#fbbf24,#d97706);
            box-shadow:0 12px 28px rgba(245,158,11,0.25);
            transition:0.3s ease;
        }

        .btn-pro:hover{
            color:#fff;
            transform:translateY(-2px);
        }

        .btn-glass{
            text-decoration:none;
            color:#fff;
            font-weight:700;
            padding:13px 24px;
            border-radius:14px;
            background:rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.12);
            transition:0.3s ease;
        }

        .btn-glass:hover{
            color:#fff;
            background:rgba(255,255,255,0.12);
        }

        .certificate-shell{
            max-width:1300px;
            margin:auto;
            background:linear-gradient(145deg, #fffdf7, #fffaf0);
            border-radius:34px;
            padding:24px;
            box-shadow:0 35px 90px rgba(0,0,0,0.38);
            position:relative;
            overflow:hidden;
        }

        .certificate-shell::before{
            content:"";
            position:absolute;
            inset:0;
            background:
                radial-gradient(circle at top right, rgba(251,191,36,0.10), transparent 25%),
                radial-gradient(circle at bottom left, rgba(217,119,6,0.08), transparent 25%);
            pointer-events:none;
        }

        .certificate-border{
            position:relative;
            z-index:2;
            border:3px solid #d4a017;
            border-radius:28px;
            padding:16px;
            background:linear-gradient(180deg, rgba(255,255,255,0.94), rgba(255,251,235,0.96));
        }

        .certificate-inner{
            border:2px solid rgba(212,160,23,0.45);
            border-radius:22px;
            padding:55px 45px;
            position:relative;
            overflow:hidden;
        }

        .certificate-inner::before{
            content:"";
            position:absolute;
            width:220px;
            height:220px;
            border:20px solid rgba(212,160,23,0.06);
            border-radius:50%;
            top:-70px;
            left:-70px;
        }

        .certificate-inner::after{
            content:"";
            position:absolute;
            width:220px;
            height:220px;
            border:20px solid rgba(212,160,23,0.06);
            border-radius:50%;
            bottom:-70px;
            right:-70px;
        }

        .header-row{
            position:relative;
            z-index:2;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:20px;
            flex-wrap:wrap;
            margin-bottom:30px;
        }

        .brand h1{
            font-size:20px;
            letter-spacing:4px;
            font-weight:900;
            color:#b45309;
            margin-bottom:8px;
        }

        .brand p{
            margin:0;
            color:#6b7280;
            font-size:14px;
            font-weight:600;
        }

        .badge-box{
            width:95px;
            height:95px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:radial-gradient(circle, #fbbf24, #d97706);
            color:#fff;
            font-size:32px;
            font-weight:900;
            box-shadow:0 14px 30px rgba(217,119,6,0.30);
            border:5px solid rgba(255,255,255,0.6);
        }

        .center-area{
            position:relative;
            z-index:2;
            text-align:center;
        }

        .mini-title{
            font-size:16px;
            text-transform:uppercase;
            letter-spacing:6px;
            color:#9a3412;
            font-weight:800;
            margin-bottom:10px;
        }

        .main-title{
            font-size:58px;
            line-height:1.1;
            font-weight:900;
            color:#111827;
            margin-bottom:18px;
            font-family:Georgia, 'Times New Roman', serif;
        }

        .desc{
            font-size:20px;
            color:#4b5563;
            margin-bottom:18px;
        }

        .student-name{
            font-size:52px;
            line-height:1.2;
            color:#b45309;
            font-weight:900;
            margin:22px 0 18px;
            text-transform:capitalize;
            font-family:Georgia, 'Times New Roman', serif;
        }

        .achievement-text{
            max-width:900px;
            margin:0 auto;
            font-size:21px;
            line-height:1.8;
            color:#374151;
        }

        .course-box{
            display:inline-block;
            margin-top:24px;
            padding:16px 26px;
            border-radius:18px;
            background:linear-gradient(90deg, rgba(251,191,36,0.14), rgba(245,158,11,0.10));
            border:1px solid rgba(180,83,9,0.15);
            font-size:25px;
            font-weight:900;
            color:#111827;
        }

        .info-grid{
            position:relative;
            z-index:2;
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
            margin-top:45px;
        }

        .info-card{
            background:#fff;
            border:1px solid rgba(212,160,23,0.25);
            border-radius:20px;
            padding:22px;
            text-align:center;
            box-shadow:0 10px 24px rgba(0,0,0,0.05);
        }

        .info-card h4{
            font-size:13px;
            color:#92400e;
            margin-bottom:10px;
            text-transform:uppercase;
            letter-spacing:2px;
            font-weight:800;
        }

        .info-card p{
            margin:0;
            font-size:18px;
            font-weight:800;
            color:#111827;
            word-break:break-word;
        }

        .footer-row{
            position:relative;
            z-index:2;
            display:grid;
            grid-template-columns:1fr auto 1fr;
            gap:20px;
            align-items:end;
            margin-top:60px;
        }

        .sign-box{
            text-align:center;
        }

        .sign-line{
            height:2px;
            background:linear-gradient(90deg,#d1d5db,#6b7280,#d1d5db);
            margin-bottom:10px;
        }

        .sign-box h5{
            margin:0;
            font-size:18px;
            font-weight:800;
            color:#111827;
        }

        .sign-box p{
            margin:6px 0 0;
            color:#6b7280;
            font-size:14px;
            font-weight:600;
        }

        .seal{
            width:150px;
            height:150px;
            border-radius:50%;
            border:7px solid #d97706;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            font-size:15px;
            line-height:1.5;
            font-weight:900;
            color:#b45309;
            background:
                radial-gradient(circle, rgba(251,191,36,0.18), rgba(255,255,255,0.95));
            box-shadow:
                inset 0 0 0 6px rgba(217,119,6,0.12),
                0 12px 24px rgba(0,0,0,0.08);
        }

        .bottom-note{
            text-align:center;
            margin-top:28px;
            color:#6b7280;
            font-size:14px;
            font-weight:600;
            letter-spacing:0.5px;
        }

        @media(max-width:992px){
            .main-title{
                font-size:42px;
            }

            .student-name{
                font-size:38px;
            }

            .info-grid{
                grid-template-columns:1fr;
            }

            .footer-row{
                grid-template-columns:1fr;
                justify-items:center;
            }
        }

        @media(max-width:768px){
            .certificate-inner{
                padding:35px 20px;
            }

            .main-title{
                font-size:30px;
            }

            .mini-title{
                font-size:13px;
                letter-spacing:3px;
            }

            .student-name{
                font-size:28px;
            }

            .achievement-text{
                font-size:17px;
            }

            .course-box{
                font-size:18px;
                padding:12px 16px;
            }
        }

        @media print{
            body{
                background:#fff !important;
                padding:0;
            }

            .top-bar{
                display:none !important;
            }

            .certificate-shell{
                box-shadow:none;
                border-radius:0;
                max-width:100%;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="dashboard.php" class="btn-glass">← Back to Dashboard</a>
    <button onclick="window.print()" class="btn btn-pro">Download / Print</button>
</div>

<div class="certificate-shell">
    <div class="certificate-border">
        <div class="certificate-inner">

            <div class="header-row">
                <div class="brand">
                    <h1>SMART LMS</h1>
                    <p>Excellence in Digital Learning & Academic Performance</p>
                </div>

                <div class="badge-box">★</div>
            </div>

            <div class="center-area">
                <div class="mini-title">Certificate of Excellence</div>
                <div class="main-title">Academic Achievement Award</div>

                <div class="desc">This prestigious certificate is proudly awarded to</div>

                <div class="student-name">
                    <?php echo htmlspecialchars($student_name); ?>
                </div>

                <div class="achievement-text">
                    for outstanding participation, successful completion, and commendable dedication in academic learning activities under the professional training environment of Smart LMS. This recognition reflects sincere effort, discipline, and a strong commitment to educational growth and excellence.
                </div>

                <div class="course-box">
                    <?php echo htmlspecialchars($course_name); ?>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <h4>Issue Date</h4>
                    <p><?php echo $issue_date; ?></p>
                </div>

                <div class="info-card">
                    <h4>Certificate No.</h4>
                    <p><?php echo $certificate_no; ?></p>
                </div>

                <div class="info-card">
                    <h4>Student Email</h4>
                    <p><?php echo htmlspecialchars($student_email); ?></p>
                </div>
            </div>

            <div class="footer-row">
                <div class="sign-box">
                    <div class="sign-line"></div>
                    <h5>Program Coordinator</h5>
                    <p>Smart LMS Academic Department</p>
                </div>

                <div class="seal">
                    OFFICIAL<br>
                    VERIFIED<br>
                    SMART LMS
                </div>

                <div class="sign-box">
                    <div class="sign-line"></div>
                    <h5>Head Instructor</h5>
                    <p>Authorized Certification Panel</p>
                </div>
            </div>

            <div class="bottom-note">
                This certificate is digitally generated and valid as an academic recognition document.
            </div>

        </div>
    </div>
</div>

</body>
</html>