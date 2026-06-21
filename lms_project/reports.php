<?php
session_start();
include 'includes/db.php';

// SAMPLE DATA (tu DB se bhi le sakta hai)
?>

<!DOCTYPE html>
<html>
<head>
<title>Project Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    background:#eef2f7;
    font-family:'Segoe UI';
}

/* MAIN BOX */
.report-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

/* HEADER */
.title{
    font-size:22px;
    font-weight:bold;
}

/* TABLE */
.table th{
    background:#dee2e6;
    font-size:13px;
}

.table td{
    font-size:13px;
}

/* SIDE PANEL */
.side-box{
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="report-box">

<div class="title mb-3">📊 PROJECT REPORT FORMAT</div>

<div class="row">

<!-- LEFT SIDE TABLE -->
<div class="col-md-8">

<table class="table table-bordered">

<tr>
<th>Task</th>
<th>Status</th>
<th>Start</th>
<th>End</th>
<th>Duration</th>
<th>Comments</th>
</tr>

<tr>
<td>Login System</td>
<td>Complete</td>
<td>01 May</td>
<td>02 May</td>
<td>2 Days</td>
<td>User authentication done</td>
</tr>

<tr>
<td>Dashboard UI</td>
<td>Complete</td>
<td>03 May</td>
<td>05 May</td>
<td>3 Days</td>
<td>Premium UI created</td>
</tr>

<tr>
<td>Courses Module</td>
<td>In Progress</td>
<td>06 May</td>
<td>08 May</td>
<td>3 Days</td>
<td>Course CRUD working</td>
</tr>

<tr>
<td>Assignments</td>
<td>In Progress</td>
<td>09 May</td>
<td>11 May</td>
<td>3 Days</td>
<td>Submission system added</td>
</tr>

<tr>
<td>Reports</td>
<td>Complete</td>
<td>12 May</td>
<td>13 May</td>
<td>2 Days</td>
<td>Analytics page created</td>
</tr>

</table>

</div>

<!-- RIGHT SIDE ANALYTICS -->
<div class="col-md-4">

<div class="side-box">
<h6>Status Overview</h6>
<canvas id="donut"></canvas>
</div>

<div class="side-box">
<h6>Project Analysis</h6>
<canvas id="bar"></canvas>
</div>

</div>

</div>

</div>

</div>

<script>

// DONUT
new Chart(document.getElementById('donut'), {
type: 'doughnut',
data: {
labels: ['Complete','In Progress'],
datasets: [{
data: [3,2],
backgroundColor: ['#22c55e','#f59e0b']
}]
}
});

// BAR
new Chart(document.getElementById('bar'), {
type: 'bar',
data: {
labels: ['Planning','UI','Backend','Testing'],
datasets: [{
data: [5,8,6,4],
backgroundColor:'#3b82f6'
}]
}
});

</script>

</body>
</html>