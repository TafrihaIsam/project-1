<?php
session_start();
require '../includes/db.php';

// Patient Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// পেশেন্টের সব অ্যাপয়েন্টমেন্ট ফেচ করা (ডাক্তারের নাম এবং ডিপার্টমেন্টসহ)
$sql = "SELECT a.*, d.name as doc_name, d.image as doc_img, dept.name as dept_name 
        FROM appointments a 
        JOIN doctors d ON a.doctor_id = d.id 
        JOIN departments dept ON d.dept_id = dept.id 
        WHERE a.patient_id = ? 
        ORDER BY a.appointment_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; --text: #2d3748; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; }

        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; font-weight: 500; }
        .nav-links a.active { background: var(--primary); color: #fff; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .table-container { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #edf2f7; color: #718096; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; vertical-align: middle; }

        .doc-profile { display: flex; align-items: center; gap: 12px; }
        .doc-thumb { width: 45px; height: 45px; border-radius: 10px; object-fit: cover; }

        /* Status Badges */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .pending { background: #fff8e1; color: #ffa000; }
        .confirmed { background: #e8f5e9; color: #2e7d32; }
        .cancelled { background: #ffebee; color: #c62828; }
        
        .date-box { font-weight: 600; color: var(--primary); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-house-user"></i> Dashboard</a></li>
            <li><a href="search_doctor.php"><i class="fa-solid fa-user-md"></i> Find a Doctor</a></li>
            <li><a href="my_appointments.php" class="active"><i class="fa-solid fa-calendar-check"></i> My Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="table-container">
            <h2 style="color: var(--text);"><i class="fa-solid fa-clock-rotate-left"></i> My Booking History</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Appointment Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($appointments) > 0): ?>
                        <?php foreach($appointments as $app): ?>
                            <tr>
                                <td>
                                    <div class="doc-profile">
                                        <img src="../assets/images/<?php echo $app['doc_img']; ?>" class="doc-thumb">
                                        <span><?php echo $app['doc_name']; ?></span>
                                    </div>
                                </td>
                                <td><?php echo $app['dept_name']; ?></td>
                                <td class="date-box"><?php echo date('d M, Y', strtotime($app['appointment_date'])); ?></td>
                                <td style="max-width: 200px; color: #718096; font-size: 13px;"><?php echo $app['reason']; ?></td>
                                <td>
                                    <span class="badge <?php echo $app['status']; ?>">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #a0aec0;">
                                You haven't booked any appointments yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>