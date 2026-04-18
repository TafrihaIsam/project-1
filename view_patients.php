<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// সার্চ লজিক
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT id, full_name, email, phone, gender, created_at FROM users WHERE role = 'patient'";
$params = [];

if ($search) {
    $sql .= " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients List | HealthCare Admin</title>
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
        
        /* Search Box */
        .search-container { background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; gap: 10px; }
        .search-container input { flex: 1; padding: 12px; border: 2px solid #edf2f7; border-radius: 10px; outline: none; }
        .btn-search { background: var(--primary); color: #fff; border: none; padding: 0 25px; border-radius: 10px; cursor: pointer; font-weight: 600; }

        .table-container { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #edf2f7; color: #718096; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; color: #2d3748; }
        
        .patient-avatar { width: 35px; height: 35px; background: #e0eaff; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; }
        .name-cell { display: flex; align-items: center; gap: 12px; font-weight: 600; }
        .badge { background: #f0f7ff; color: var(--primary); padding: 4px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="add_doctor.php"><i class="fa-solid fa-user-doctor"></i> Add Doctor</a></li>
            <li><a href="manage_doctors.php"><i class="fa-solid fa-users-gear"></i> Manage Doctors</a></li>
            <li><a href="view_patients.php" class="active"><i class="fa-solid fa-hospital-user"></i> Patients</a></li>
            <li><a href="all_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 25px;">Patient Management</h2>

        <form method="GET" class="search-container">
            <input type="text" name="search" placeholder="Search by name, phone or email..." value="<?php echo $search; ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if($search): ?>
                <a href="view_patients.php" style="display: flex; align-items: center; text-decoration: none; color: #718096; padding: 0 10px;">Clear</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($patients) > 0): ?>
                        <?php foreach($patients as $p): ?>
                        <tr>
                            <td>
                                <div class="name-cell">
                                    <div class="patient-avatar"><?php echo strtoupper(substr($p['full_name'], 0, 1)); ?></div>
                                    <?php echo $p['full_name']; ?>
                                </div>
                            </td>
                            <td><?php echo $p['email']; ?></td>
                            <td><?php echo $p['phone']; ?></td>
                            <td><span class="badge"><?php echo $p['gender']; ?></span></td>
                            <td style="color: #718096;"><?php echo date('d M, Y', strtotime($p['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center; padding: 30px;">No patients found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>