<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// আজকের অ্যাপয়েন্টমেন্ট সংখ্যা
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = 'pending'");
$stmt->execute([$doctor_id]);
$pending_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: var(--primary); color: #fff; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .stat-card { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border-left: 5px solid var(--primary); width: 300px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="manage_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Doctor Dashboard</h2>
        <div class="stat-card" style="margin-top: 20px;">
            <h3><?php echo $pending_count; ?></h3>
            <p>New Appointment Requests</p>
        </div>
    </div>
</body>
</html>