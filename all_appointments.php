<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// ফিল্টার ভ্যালু রিসিভ করা
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

// কোয়েরি বিল্ড করা
$sql = "SELECT a.*, u.full_name as patient_name, u.phone as patient_phone, d.name as doctor_name, dept.name as dept_name 
        FROM appointments a 
        JOIN users u ON a.patient_id = u.id 
        JOIN doctors d ON a.doctor_id = d.id 
        JOIN departments dept ON d.dept_id = dept.id 
        WHERE 1=1";

$params = [];

if ($search_name) {
    $sql .= " AND u.full_name LIKE ?";
    $params[] = "%$search_name%";
}

if ($filter_date) {
    $sql .= " AND a.appointment_date = ?";
    $params[] = $filter_date;
}

if ($filter_status) {
    $sql .= " AND a.status = ?";
    $params[] = $filter_status;
}

$sql .= " ORDER BY a.appointment_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$all_apps = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Master List | HealthCare Admin</title>
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
        .nav-links a.active { background: var(--primary); color: #fff; }
        
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        
        /* Filter Bar Style */
        .filter-box { background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .filter-form { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .input-group { flex: 1; min-width: 180px; display: flex; flex-direction: column; }
        .input-group label { font-size: 12px; font-weight: 600; color: #718096; margin-bottom: 8px; }
        input, select { padding: 10px 15px; border: 2px solid #edf2f7; border-radius: 10px; outline: none; font-size: 14px; }
        .btn-filter { background: var(--primary); color: #fff; border: none; padding: 10px 25px; border-radius: 10px; cursor: pointer; font-weight: 600; height: 45px; }
        .btn-reset { background: #edf2f7; color: #718096; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; height: 45px; display: flex; align-items: center; }

        .table-container { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #edf2f7; color: #718096; font-size: 13px; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; color: #2d3748; }
        
        .status { font-weight: 700; font-size: 12px; text-transform: uppercase; padding: 5px 10px; border-radius: 20px; }
        .confirmed { background: #e8f5e9; color: #2e7d32; }
        .pending { background: #fff8e1; color: #ffa000; }
        .cancelled { background: #ffebee; color: #c62828; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="add_doctor.php"><i class="fa-solid fa-user-doctor"></i> Add Doctor</a></li>
            <li><a href="manage_doctors.php"><i class="fa-solid fa-users-gear"></i> Manage Doctors</a></li>
            <li><a href="view_patients.php"><i class="fa-solid fa-hospital-user"></i> Patients</a></li>
            <li><a href="all_appointments.php" class="active"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 20px;">Master Appointments Control</h2>

        <div class="filter-box">
            <form method="GET" class="filter-form">
                <div class="input-group">
                    <label>Patient Name</label>
                    <input type="text" name="search_name" placeholder="Search patient..." value="<?php echo $search_name; ?>">
                </div>
                <div class="input-group">
                    <label>By Date</label>
                    <input type="date" name="filter_date" value="<?php echo $filter_date; ?>">
                </div>
                <div class="input-group">
                    <label>Status</label>
                    <select name="filter_status">
                        <option value="">All Status</option>
                        <option value="pending" <?php if($filter_status == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="confirmed" <?php if($filter_status == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                        <option value="cancelled" <?php if($filter_status == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Apply Filter</button>
                <a href="all_appointments.php" class="btn-reset">Reset</a>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Appointment Date</th>
                        <th>Patient Details</th>
                        <th>Assigned Doctor</th>
                        <th>Specialty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($all_apps) > 0): ?>
                        <?php foreach($all_apps as $app): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">
                                <?php echo date('d M, Y', strtotime($app['appointment_date'])); ?>
                            </td>
                            <td>
                                <div><?php echo $app['patient_name']; ?></div>
                                <div style="font-size: 12px; color: #718096;"><?php echo $app['patient_phone']; ?></div>
                            </td>
                            <td><?php echo $app['doctor_name']; ?></td>
                            <td><?php echo $app['dept_name']; ?></td>
                            <td>
                                <span class="status <?php echo $app['status']; ?>">
                                    <?php echo $app['status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding: 30px; color: #a0aec0;">No records found for this search.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>