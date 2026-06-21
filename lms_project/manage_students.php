<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$students = mysqli_query($conn,"SELECT * FROM users WHERE role='student' ORDER BY id ASC");

$total = mysqli_num_rows($students);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Students</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    font-family:'Segoe UI';
    background:#f3f4f6;
}

/* HEADER */
.hero{
    background:linear-gradient(135deg,#7c3aed,#06b6d4);
    padding:40px;
    border-radius:20px;
    color:white;
}

/* CARDS */
.card-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* TABLE */
.table-box{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-top:20px;
}

.status-active{
    background:#d1fae5;
    color:#065f46;
    padding:5px 10px;
    border-radius:20px;
}

.status-inactive{
    background:#fee2e2;
    color:#991b1b;
    padding:5px 10px;
    border-radius:20px;
}

.btn-sm{
    border-radius:10px;
}

</style>
</head>

<body>

<div class="container mt-4">

<!-- HERO -->
<div class="hero">
    <h2>Manage Students 👨‍🎓</h2>
    <p>Control and manage all students in a premium dashboard view.</p>
</div>

<!-- STATS -->
<div class="row mt-4">

<div class="col-md-4">
<div class="card-box">
<h5>Total Students</h5>
<h2><?php echo $total; ?></h2>
</div>
</div>

<div class="col-md-4">
<div class="card-box">
<h5>Active Students</h5>
<h2>
<?php
$a = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as t FROM users WHERE role='student' AND status='active'"));
echo $a['t'];
?>
</h2>
</div>
</div>

<div class="col-md-4">
<div class="card-box">
<h5>Inactive Students</h5>
<h2>
<?php
$i = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as t FROM users WHERE role='student' AND status='inactive'"));
echo $i['t'];
?>
</h2>
</div>
</div>

</div>

<!-- TABLE -->
<div class="table-box">

<div class="d-flex justify-content-between mb-3">
    <h4>Student Records</h4>
    <a href="add_student.php" class="btn btn-primary">+ Add Student</a>
</div>

<input type="text" id="search" class="form-control mb-3" placeholder="🔍 Search student...">

<table class="table table-hover" id="studentTable">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($students)){ ?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td>
<?php if($row['status']=='active'){ ?>
<span class="status-active">Active</span>
<?php } else { ?>
<span class="status-inactive">Inactive</span>
<?php } ?>
</td>

<td>

<a href="?id=<?php echo $row['id']; ?>&status=active" class="btn btn-success btn-sm">Activate</a>

<a href="?id=<?php echo $row['id']; ?>&status=inactive" class="btn btn-secondary btn-sm">Deactivate</a>

<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>

</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>

</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
let value = this.value.toLowerCase();
let rows = document.querySelectorAll("#studentTable tbody tr");

rows.forEach(row=>{
row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
});
});
</script>

</body>
</html>