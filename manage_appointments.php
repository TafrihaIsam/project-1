<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// স্ট্যাটাস আপডেট লজিক
if (isset($_GET['action']) && isset($_GET['id'])) {
    $status = ($_GET['action'] == 'confirm') ? 'confirmed' : 'cancelled';
    $app_id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$status, $app_id, $doctor_id]);
    header("Location: manage_appointments.php");
    exit();
}

// ডাক্তারের সব অ্যাপয়েন্টমেন্ট ফেচ করা
$sql = "SELECT a.*, u.full_name as patient_name, u.phone FROM appointments a 
        JOIN users u ON a.patient_id = u.id 
        WHERE a.doctor_id = ? ORDER BY a.appointment_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* আগের ড্যাশবোর্ডের সব CSS এখানেও থাকবে */
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; }
        .nav-links a.active { background: var(--primary); color: #fff; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .table-container { background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #edf2f7; }
        .btn-confirm { color: #2ecc71; text-decoration: none; font-weight: 600; margin-right: 10px; }
        .btn-cancel { color: #e74c3c; text-decoration: none; font-weight: 600; }
        .status { font-weight: 600; text-transform: capitalize; }
        .confirmed { color: #2ecc71; }
        .pending { color: #f39c12; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="manage_appointments.php" class="active"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="table-container">
            <h2>Patient Appointment Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($appointments as $app): ?>
                    <tr>
                        <td><?php echo $app['patient_name']; ?></td>
                        <td><?php echo $app['phone']; ?></td>
                        <td><?php echo $app['appointment_date']; ?></td>
                        <td><?php echo $app['reason']; ?></td>
                        <td class="status <?php echo $app['status']; ?>"><?php echo $app['status']; ?></td>
                        <td>
                            <?php if($app['status'] == 'pending'): ?>
                                <a href="manage_appointments.php?action=confirm&id=<?php echo $app['id']; ?>" class="btn-confirm">Confirm</a>
                                <a href="manage_appointments.php?action=cancel&id=<?php echo $app['id']; ?>" class="btn-cancel">Cancel</a>
                            <?php else: ?>
                                ---
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>