<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /lms_project/login.php");
    exit();
}

$user = $_SESSION['user'];
$name = isset($user['name']) ? htmlspecialchars($user['name']) : 'Student';
$initial = strtoupper(substr($name, 0, 1));

/*
|--------------------------------------------------------------------------
| QUIZ SETTINGS
|--------------------------------------------------------------------------
*/
$questionsPerPage = 5;

$questionBank = [
    [
        'id' => 1,
        'question' => 'What does HTML stand for?',
        'options' => [
            'a' => 'Hyper Trainer Marking Language',
            'b' => 'Hyper Text Markup Language',
            'c' => 'High Text Machine Language',
            'd' => 'Hyper Tool Multi Language'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 2,
        'question' => 'Which language is mainly used for styling web pages?',
        'options' => [
            'a' => 'PHP',
            'b' => 'Java',
            'c' => 'CSS',
            'd' => 'SQL'
        ],
        'answer' => 'c'
    ],
    [
        'id' => 3,
        'question' => 'Which of these is a database language?',
        'options' => [
            'a' => 'SQL',
            'b' => 'CSS',
            'c' => 'Bootstrap',
            'd' => 'Photoshop'
        ],
        'answer' => 'a'
    ],
    [
        'id' => 4,
        'question' => 'Which one is used to connect PHP with MySQL?',
        'options' => [
            'a' => 'Java API',
            'b' => 'Python Link',
            'c' => 'HTML Tag',
            'd' => 'mysqli'
        ],
        'answer' => 'd'
    ],
    [
        'id' => 5,
        'question' => 'Which protocol is used for web browsing?',
        'options' => [
            'a' => 'FTP',
            'b' => 'HTTP',
            'c' => 'SMTP',
            'd' => 'POP3'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 6,
        'question' => 'Which symbol is used to start a variable in PHP?',
        'options' => [
            'a' => '#',
            'b' => '@',
            'c' => '$',
            'd' => '&'
        ],
        'answer' => 'c'
    ],
    [
        'id' => 7,
        'question' => 'Which tag is used to insert an image in HTML?',
        'options' => [
            'a' => '<img>',
            'b' => '<image>',
            'c' => '<pic>',
            'd' => '<src>'
        ],
        'answer' => 'a'
    ],
    [
        'id' => 8,
        'question' => 'Which CSS property is used to change text color?',
        'options' => [
            'a' => 'font-color',
            'b' => 'text-color',
            'c' => 'color',
            'd' => 'background-color'
        ],
        'answer' => 'c'
    ],
    [
        'id' => 9,
        'question' => 'Which method is used to send form data securely?',
        'options' => [
            'a' => 'GET',
            'b' => 'POST',
            'c' => 'PUT',
            'd' => 'LINK'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 10,
        'question' => 'Which of these is a JavaScript framework/library?',
        'options' => [
            'a' => 'Laravel',
            'b' => 'React',
            'c' => 'MySQL',
            'd' => 'Figma'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 11,
        'question' => 'Which SQL command is used to fetch data?',
        'options' => [
            'a' => 'SELECT',
            'b' => 'INSERT',
            'c' => 'DELETE',
            'd' => 'UPDATE'
        ],
        'answer' => 'a'
    ],
    [
        'id' => 12,
        'question' => 'Bootstrap is mainly used for?',
        'options' => [
            'a' => 'Database connection',
            'b' => 'UI design and responsive layout',
            'c' => 'Hosting',
            'd' => 'Server security'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 13,
        'question' => 'Which function is used to start a session in PHP?',
        'options' => [
            'a' => 'start_session()',
            'b' => 'session_begin()',
            'c' => 'session_start()',
            'd' => 'open_session()'
        ],
        'answer' => 'c'
    ],
    [
        'id' => 14,
        'question' => 'Which HTML element is used for the largest heading?',
        'options' => [
            'a' => '<h6>',
            'b' => '<head>',
            'c' => '<heading>',
            'd' => '<h1>'
        ],
        'answer' => 'd'
    ],
    [
        'id' => 15,
        'question' => 'Which attribute is used in HTML links?',
        'options' => [
            'a' => 'src',
            'b' => 'href',
            'c' => 'link',
            'd' => 'action'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 16,
        'question' => 'Which operator is used for concatenation in PHP?',
        'options' => [
            'a' => '+',
            'b' => '.',
            'c' => '&',
            'd' => '*'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 17,
        'question' => 'Which HTML tag is used to create a form?',
        'options' => [
            'a' => '<input>',
            'b' => '<label>',
            'c' => '<form>',
            'd' => '<table>'
        ],
        'answer' => 'c'
    ],
    [
        'id' => 18,
        'question' => 'Which of these is used to make a website responsive?',
        'options' => [
            'a' => 'Media Queries',
            'b' => 'SQL Queries',
            'c' => 'PHP Sessions',
            'd' => 'DNS'
        ],
        'answer' => 'a'
    ],
    [
        'id' => 19,
        'question' => 'Which command is used to add new data into a MySQL table?',
        'options' => [
            'a' => 'ADD',
            'b' => 'INSERT',
            'c' => 'CREATE',
            'd' => 'UPDATE'
        ],
        'answer' => 'b'
    ],
    [
        'id' => 20,
        'question' => 'Which one is a server-side scripting language?',
        'options' => [
            'a' => 'HTML',
            'b' => 'CSS',
            'c' => 'PHP',
            'd' => 'Bootstrap'
        ],
        'answer' => 'c'
    ]
];

$totalQuestions = count($questionBank);

/*
|--------------------------------------------------------------------------
| SESSION INITIALIZATION
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['quiz_answered_ids'])) {
    $_SESSION['quiz_answered_ids'] = [];
}

if (!isset($_SESSION['quiz_total_score'])) {
    $_SESSION['quiz_total_score'] = 0;
}

if (!isset($_SESSION['quiz_attempted_count'])) {
    $_SESSION['quiz_attempted_count'] = 0;
}

if (!isset($_SESSION['quiz_current_batch'])) {
    $_SESSION['quiz_current_batch'] = [];
}

/*
|--------------------------------------------------------------------------
| RESTART QUIZ
|--------------------------------------------------------------------------
*/
if (isset($_GET['restart']) && $_GET['restart'] == 1) {
    $_SESSION['quiz_answered_ids'] = [];
    $_SESSION['quiz_total_score'] = 0;
    $_SESSION['quiz_attempted_count'] = 0;
    $_SESSION['quiz_current_batch'] = [];

    header("Location: quiz.php");
    exit();
}

$score = null;
$percentage = null;
$remark = '';
$quizCompleted = false;

/*
|--------------------------------------------------------------------------
| SUBMIT CURRENT BATCH
|--------------------------------------------------------------------------
*/
if (isset($_POST['submit_quiz']) && !empty($_SESSION['quiz_current_batch'])) {
    $score = 0;
    $currentBatchIds = $_SESSION['quiz_current_batch'];

    foreach ($currentBatchIds as $qid) {
        foreach ($questionBank as $question) {
            if ($question['id'] == $qid) {
                $fieldName = 'q' . $qid;
                if (isset($_POST[$fieldName]) && $_POST[$fieldName] === $question['answer']) {
                    $score++;
                }
                break;
            }
        }
    }

    $_SESSION['quiz_total_score'] += $score;
    $_SESSION['quiz_attempted_count'] += count($currentBatchIds);
    $_SESSION['quiz_answered_ids'] = array_unique(array_merge($_SESSION['quiz_answered_ids'], $currentBatchIds));
    $_SESSION['quiz_current_batch'] = [];

    $attempted = $_SESSION['quiz_attempted_count'];
    $overallScore = $_SESSION['quiz_total_score'];

    if ($attempted > 0) {
        $percentage = round(($overallScore / $attempted) * 100);
    }

    if ($percentage >= 80) {
        $remark = "Excellent work! Your quiz performance is strong and highly impressive.";
    } elseif ($percentage >= 60) {
        $remark = "Good job! You have a solid understanding, but a little more revision will improve your score.";
    } else {
        $remark = "Keep practicing. Review your concepts and attempt the quiz again to improve your performance.";
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE NEXT BATCH
|--------------------------------------------------------------------------
*/
$answeredIds = $_SESSION['quiz_answered_ids'];
$remainingQuestions = [];

foreach ($questionBank as $question) {
    if (!in_array($question['id'], $answeredIds)) {
        $remainingQuestions[] = $question;
    }
}

if (count($remainingQuestions) > 0) {
    if (empty($_SESSION['quiz_current_batch'])) {
        $nextBatch = array_slice($remainingQuestions, 0, $questionsPerPage);
        $_SESSION['quiz_current_batch'] = array_column($nextBatch, 'id');
    }

    $displayQuestions = [];
    foreach ($questionBank as $question) {
        if (in_array($question['id'], $_SESSION['quiz_current_batch'])) {
            $displayQuestions[] = $question;
        }
    }
} else {
    $displayQuestions = [];
    $quizCompleted = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Quiz Support | Smart LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{box-sizing:border-box;}
        body{
            margin:0;
            font-family:'Segoe UI',sans-serif;
            min-height:100vh;
            background:
                radial-gradient(circle at top left, rgba(79,70,229,0.18), transparent 24%),
                radial-gradient(circle at top right, rgba(59,130,246,0.15), transparent 24%),
                radial-gradient(circle at bottom left, rgba(168,85,247,0.10), transparent 22%),
                linear-gradient(135deg, #eef4ff, #f8fafc, #f3f0ff);
            color:#0f172a;
        }

        .navbar-pro{
            background:rgba(255,255,255,0.68);
            backdrop-filter:blur(16px);
            border-bottom:1px solid rgba(255,255,255,0.72);
            box-shadow:0 10px 30px rgba(15,23,42,0.05);
            padding:16px 0;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            font-size:22px;
            font-weight:800;
            color:#111827;
        }

        .brand-logo{
            width:52px;
            height:52px;
            border-radius:18px;
            background:linear-gradient(135deg, #4f46e5, #7c3aed);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            box-shadow:0 18px 34px rgba(79,70,229,0.22);
        }

        .profile-chip{
            display:flex;
            align-items:center;
            gap:12px;
            background:rgba(255,255,255,0.82);
            border:1px solid rgba(255,255,255,0.68);
            padding:8px 12px;
            border-radius:18px;
            box-shadow:0 8px 24px rgba(15,23,42,0.05);
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:linear-gradient(135deg, #4f46e5, #6366f1);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .main-wrap{
            padding:36px 0 52px;
        }

        .page-shell{
            max-width:1150px;
            margin:auto;
        }

        .hero-panel{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, rgba(79,70,229,0.96), rgba(124,58,237,0.90));
            border-radius:36px;
            padding:38px;
            color:white;
            box-shadow:0 28px 70px rgba(79,70,229,0.18);
            margin-bottom:26px;
        }

        .hero-tag{
            display:inline-block;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.2);
            border-radius:999px;
            padding:8px 14px;
            font-size:13px;
            font-weight:700;
            margin-bottom:16px;
        }

        .hero-title{
            font-size:38px;
            font-weight:800;
            margin-bottom:10px;
        }

        .hero-sub{
            max-width:760px;
            color:rgba(255,255,255,0.93);
            line-height:1.85;
            font-size:15px;
            margin-bottom:20px;
        }

        .hero-chips{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .hero-chip{
            padding:10px 14px;
            border-radius:999px;
            background:rgba(255,255,255,0.14);
            border:1px solid rgba(255,255,255,0.18);
            font-size:13px;
            font-weight:700;
        }

        .glass-card{
            background:rgba(255,255,255,0.78);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.74);
            border-radius:28px;
            box-shadow:0 20px 50px rgba(15,23,42,0.06);
            padding:24px;
        }

        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            margin-bottom:22px;
            flex-wrap:wrap;
        }

        .section-head h3{
            margin:0;
            font-size:26px;
            font-weight:800;
            color:#0f172a;
        }

        .section-head p{
            margin:5px 0 0;
            color:#64748b;
            font-size:14px;
        }

        .quiz-card{
            background:linear-gradient(180deg, rgba(255,255,255,0.96), rgba(248,250,252,0.96));
            border:1px solid #e2e8f0;
            border-radius:24px;
            padding:22px;
            margin-bottom:18px;
            box-shadow:0 12px 28px rgba(15,23,42,0.04);
        }

        .quiz-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:14px;
            flex-wrap:wrap;
        }

        .quiz-number{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:42px;
            height:42px;
            border-radius:14px;
            background:linear-gradient(135deg, #4f46e5, #7c3aed);
            color:white;
            font-weight:800;
        }

        .question-title{
            font-size:18px;
            font-weight:800;
            color:#0f172a;
            margin:0;
            flex:1;
        }

        .option-box{
            display:block;
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:14px 16px;
            margin-bottom:12px;
            cursor:pointer;
            transition:0.25s ease;
        }

        .option-box:hover{
            border-color:#a5b4fc;
            background:#f8faff;
        }

        .option-box input{
            margin-right:10px;
        }

        .btn-pro{
            border:none;
            border-radius:16px;
            padding:13px 22px;
            font-size:15px;
            font-weight:700;
            text-decoration:none;
            transition:0.3s ease;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .btn-primary-pro{
            background:linear-gradient(135deg, #4f46e5, #3b82f6);
            color:white;
            box-shadow:0 16px 30px rgba(79,70,229,0.16);
        }

        .btn-primary-pro:hover{
            color:white;
            transform:translateY(-2px);
        }

        .btn-light-pro{
            background:#ffffff;
            color:#334155;
            border:1px solid #e2e8f0;
        }

        .btn-light-pro:hover{
            color:#0f172a;
            transform:translateY(-2px);
        }

        .result-box{
            margin-top:24px;
            border-radius:26px;
            padding:24px;
            background:linear-gradient(135deg, #eff6ff, #f5f3ff);
            border:1px solid #dbeafe;
        }

        .result-title{
            font-size:26px;
            font-weight:800;
            margin-bottom:10px;
            color:#0f172a;
        }

        .score-badge{
            display:inline-block;
            padding:10px 16px;
            border-radius:999px;
            background:linear-gradient(135deg, #4f46e5, #7c3aed);
            color:white;
            font-weight:800;
            margin-bottom:12px;
        }

        .remark{
            font-size:15px;
            color:#475569;
            line-height:1.8;
            margin:0;
        }

        .complete-box{
            background:linear-gradient(135deg, #ecfeff, #eef2ff);
            border:1px solid #c7d2fe;
            border-radius:24px;
            padding:26px;
            margin-top:20px;
        }

        @media(max-width:768px){
            .hero-title{font-size:28px;}
            .question-title{font-size:16px;}
        }
    </style>
</head>
<body>

<nav class="navbar-pro">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="brand">
            <div class="brand-logo">Q</div>
            <div>Smart LMS</div>
        </div>

        <div class="profile-chip">
            <div>
                <div style="font-size:14px; font-weight:800; color:#0f172a;"><?php echo $name; ?></div>
                <small style="color:#64748b;">Quiz Center</small>
            </div>
            <div class="avatar"><?php echo $initial; ?></div>
        </div>
    </div>
</nav>

<div class="main-wrap">
    <div class="container page-shell">

        <div class="hero-panel">
            <div class="hero-tag">AI Quiz Support</div>
            <div class="hero-title">Smart Quiz Challenge</div>
            <p class="hero-sub">
                Attempt multiple quiz rounds with new questions every time. Once a question is submitted,
                it will not repeat in the next round.
            </p>

            <div class="hero-chips">
                <div class="hero-chip">Total Questions: <?php echo $totalQuestions; ?></div>
                <div class="hero-chip">Questions Per Round: <?php echo $questionsPerPage; ?></div>
                <div class="hero-chip">No Repeat Questions</div>
            </div>
        </div>

        <div class="glass-card">
            <div class="section-head">
                <div>
                    <h3>Technical Quiz</h3>
                    <p>
                        Attempted: <?php echo $_SESSION['quiz_attempted_count']; ?> /
                        <?php echo $totalQuestions; ?> |
                        Total Score: <?php echo $_SESSION['quiz_total_score']; ?>
                    </p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="/lms_project/pro_tools.php" class="btn-pro btn-light-pro">Back to Pro Tools</a>
                    <a href="/lms_project/dashboard.php" class="btn-pro btn-light-pro">Dashboard</a>
                </div>
            </div>

            <?php if ($score !== null) { ?>
                <div class="result-box">
                    <div class="result-title">Last Submission Result</div>
                    <div class="score-badge">
                        Round Score: <?php echo $score; ?>/<?php echo min($questionsPerPage, $totalQuestions - ($_SESSION['quiz_attempted_count'] - $questionsPerPage)); ?>
                    </div>
                    <p class="remark"><?php echo $remark; ?></p>
                </div>
            <?php } ?>

            <?php if (!$quizCompleted) { ?>
                <form method="POST">
                    <?php $num = 1; ?>
                    <?php foreach ($displayQuestions as $q) { ?>
                        <div class="quiz-card">
                            <div class="quiz-top">
                                <div class="quiz-number"><?php echo $num; ?></div>
                                <h4 class="question-title"><?php echo htmlspecialchars($q['question']); ?></h4>
                            </div>

                            <?php foreach ($q['options'] as $key => $value) { ?>
                                <label class="option-box">
                                    <input type="radio" name="q<?php echo $q['id']; ?>" value="<?php echo $key; ?>" required>
                                    <?php echo htmlspecialchars($value); ?>
                                </label>
                            <?php } ?>
                        </div>
                        <?php $num++; ?>
                    <?php } ?>

                    <button type="submit" name="submit_quiz" class="btn-pro btn-primary-pro">Submit Quiz</button>
                </form>
            <?php } else { ?>
                <div class="complete-box">
                    <div class="result-title">Quiz Completed</div>
                    <div class="score-badge">
                        Final Score: <?php echo $_SESSION['quiz_total_score']; ?>/<?php echo $totalQuestions; ?>
                        (<?php echo round(($_SESSION['quiz_total_score'] / $totalQuestions) * 100); ?>%)
                    </div>
                    <p class="remark" style="margin-bottom:16px;">
                        Great! You have completed all quiz questions. You can restart the quiz to attempt again from the beginning.
                    </p>
                    <a href="quiz.php?restart=1" class="btn-pro btn-primary-pro">Restart Quiz</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>