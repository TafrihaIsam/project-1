<?php
session_start();
require '../includes/db.php';

// Patient Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// পেশেন্টের কিছু ডাটা ফেচ করা
$total_app = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ?");
$total_app->execute([$patient_id]);
$appointments_count = $total_app->fetchColumn();

// লেটেস্ট অ্যাপয়েন্টমেন্ট চেক করা
$stmt = $pdo->prepare("SELECT a.*, d.name as doc_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.id DESC LIMIT 1");
$stmt->execute([$patient_id]);
$last_app = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; }

        /* Sidebar - Same as Admin for Consistency */
        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background: var(--primary); color: #fff; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .stat-card { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border-left: 5px solid var(--primary); }
        .stat-card h3 { font-size: 28px; color: #2d3748; }
        .stat-card p { color: #718096; font-size: 14px; margin-top: 5px; }

        .welcome-box { background: linear-gradient(135deg, #0c5adb, #3b82f6); color: #fff; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden; }
        .welcome-box h1 { font-size: 30px; z-index: 1; position: relative; }
        .welcome-box p { opacity: 0.9; margin-top: 10px; z-index: 1; position: relative; }
        .welcome-box i { position: absolute; right: -20px; bottom: -20px; font-size: 150px; opacity: 0.1; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i class="fa-solid fa-house-user"></i> Dashboard</a></li>
            <li><a href="search_doctor.php"><i class="fa-solid fa-user-md"></i> Find a Doctor</a></li>
            <li><a href="my_appointments.php"><i class="fa-solid fa-calendar-check"></i> My Appointments</a></li>
            <li><a href="prescriptions.php"><i class="fa-solid fa-file-medical"></i> Prescriptions</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Patient Portal</h2>
            <div class="user-info"><strong><?php echo $_SESSION['user_name']; ?></strong></div>
        </div>

        <div class="welcome-box">
            <h1>Welcome back, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>!</h1>
            <p>Your health is our priority. Take a look at your schedule today.</p>
            <i class="fa-solid fa-stethoscope"></i>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $appointments_count; ?></h3>
                <p>Total Appointments</p>
            </div>
            <div class="stat-card">
                <h3>
                    <?php echo $last_app ? $last_app['doc_name'] : 'No Pending'; ?>
                </h3>
                <p>Last Booked Doctor</p>
            </div>
        </div>
    </div>

</body>
</html>