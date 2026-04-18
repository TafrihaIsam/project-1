<?php
session_start();
require '../includes/db.php';

// চেক করা হচ্ছে ইউজার অ্যাডমিন কি না
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// কিছু ডাটা আনা ড্যাশবোর্ডে দেখানোর জন্য
$total_doctors = $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$total_patients = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'patient'")->fetchColumn();
$total_appointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | HealthCare Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0c5adb;
            --bg-color: #f4f7fe;
            --sidebar-color: #ffffff;
            --text-color: #2d3748;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); display: flex; }

        /* Sidebar Style */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-color);
            padding: 30px 20px;
            position: fixed;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar .logo {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 15px; }
        .nav-links a {
            text-decoration: none;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background: var(--primary-color);
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 { color: var(--text-color); font-weight: 700; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        }

        .stat-card i {
            font-size: 30px;
            color: var(--primary-color);
            background: #f0f7ff;
            padding: 15px;
            border-radius: 12px;
        }

        .stat-info h3 { font-size: 24px; color: var(--text-color); }
        .stat-info p { color: #a0aec0; font-size: 14px; }

        .btn-logout {
            color: #e74c3c;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="add_doctor.php"><i class="fa-solid fa-user-doctor"></i> Add Doctor</a></li>
            <li><a href="manage_doctors.php"><i class="fa-solid fa-users-gear"></i> Manage Doctors</a></li>
            <li><a href="view_patients.php"><i class="fa-solid fa-hospital-user"></i> Patients</a></li>
            <li><a href="all_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Welcome Admin, <?php echo $_SESSION['user_name']; ?></h2>
            <a href="../logout.php" class="btn-logout"><i class="fa-solid fa-power-off"></i> Sign Out</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa-solid fa-user-md"></i>
                <div class="stat-info">
                    <h3><?php echo $total_doctors; ?></h3>
                    <p>Total Doctors</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-user-injured"></i>
                <div class="stat-info">
                    <h3><?php echo $total_patients; ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-calendar-check"></i>
                <div class="stat-info">
                    <h3><?php echo $total_appointments; ?></h3>
                    <p>Appointments</p>
                </div>
            </div>
        </div>

        </div>

</body>
</html>