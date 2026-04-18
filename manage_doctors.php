<?php
session_start();
require '../includes/db.php';

// Admin Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete Doctor Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // প্রথমে ডাটাবেস থেকে ইমেজ নামটা নিয়ে আসব যেন ফোল্ডার থেকেও ডিলিট করা যায়
    $stmt = $pdo->prepare("SELECT image FROM doctors WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    
    if ($img && $img !== 'default_doctor.png') {
        unlink("../assets/images/" . $img); // ফোল্ডার থেকে ফাইল ডিলিট
    }

    $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_doctors.php?msg=Doctor Deleted Successfully");
    exit();
}

// Fetch all doctors with their department names
$sql = "SELECT doctors.*, departments.name AS dept_name 
        FROM doctors 
        LEFT JOIN departments ON doctors.dept_id = departments.id 
        ORDER BY doctors.id DESC";
$doctors = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors | HealthCare Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --text: #2d3748; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--bg); display: flex; }

        .sidebar { width: 260px; height: 100vh; background: #fff; padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; margin-bottom: 10px; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: var(--primary); color: #fff; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .table-container { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        h2 { margin-bottom: 25px; color: var(--text); display: flex; justify-content: space-between; align-items: center; }
        .btn-add { background: var(--primary); color: #fff; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #edf2f7; color: #718096; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; color: var(--text); vertical-align: middle; }

        .doctor-info { display: flex; align-items: center; gap: 12px; }
        .doctor-img { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; background: #eee; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #e0eaff; color: var(--primary); }
        
        .actions a { margin-right: 10px; font-size: 18px; transition: 0.3s; }
        .btn-delete { color: #e74c3c; }
        .btn-delete:hover { color: #c0392b; }

        .msg { padding: 10px; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="add_doctor.php"><i class="fa-solid fa-user-doctor"></i> Add Doctor</a></li>
            <li><a href="manage_doctors.php" class="active"><i class="fa-solid fa-users-gear"></i> Manage Doctors</a></li>
            <li><a href="view_patients.php"><i class="fa-solid fa-hospital-user"></i> Patients</a></li>
            <li><a href="all_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="table-container">
            <h2>
                Manage Doctors 
                <a href="add_doctor.php" class="btn-add"><i class="fa-solid fa-plus"></i> Add New Doctor</a>
            </h2>

            <?php if(isset($_GET['msg'])) echo "<div class='msg'>".$_GET['msg']."</div>"; ?>

            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Qualification</th>
                        <th>Experience</th>
                        <th>Fees</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($doctors) > 0): ?>
                        <?php foreach($doctors as $doc): ?>
                            <tr>
                                <td>
                                    <div class="doctor-info">
                                        <img src="../assets/images/<?php echo $doc['image']; ?>" class="doctor-img" alt="Doctor">
                                        <div>
                                            <p style="font-weight: 700;"><?php echo $doc['name']; ?></p>
                                            <p style="font-size: 12px; color: #718096;"><?php echo $doc['email']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge"><?php echo $doc['dept_name']; ?></span></td>
                                <td><?php echo $doc['qualification']; ?></td>
                                <td><?php echo $doc['experience']; ?> Years</td>
                                <td style="font-weight: 600; color: #2ecc71;"><?php echo $doc['fees']; ?> BDT</td>
                                <td class="actions">
                                    <a href="edit_doctor.php?id=<?php echo $doc['id']; ?>" style="color: #3498db;">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
                                    <a href="manage_doctors.php?delete=<?php echo $doc['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this doctor?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center; padding: 30px; color: #a0aec0;">No doctors found. Add some first!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>