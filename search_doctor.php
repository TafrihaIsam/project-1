<?php
session_start();
require '../includes/db.php';

// Patient Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

// ১. সব ডিপার্টমেন্ট ফেচ করা (ফিল্টার ড্রপডাউনের জন্য)
$depts = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

// ২. সার্চ এবং ফিল্টার লজিক
$dept_filter = isset($_GET['dept_id']) ? $_GET['dept_id'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT doctors.*, departments.name AS dept_name 
        FROM doctors 
        JOIN departments ON doctors.dept_id = departments.id 
        WHERE 1=1";

$params = [];

if ($dept_filter) {
    $sql .= " AND doctors.dept_id = ?";
    $params[] = $dept_filter;
}

if ($search_query) {
    $sql .= " AND (doctors.name LIKE ? OR doctors.qualification LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find a Doctor | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; --text: #2d3748; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; }

        /* Sidebar - Common Style */
        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); z-index: 1000; }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; font-weight: 500; }
        .nav-links a:hover, .nav-links a.active { background: var(--primary); color: #fff; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        
        /* Search Bar Area */
        .filter-section { background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { flex: 1; min-width: 200px; display: flex; flex-direction: column; }
        .filter-group label { font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #718096; }
        input, select { padding: 12px; border: 2px solid #edf2f7; border-radius: 12px; outline: none; }
        .btn-search { background: var(--primary); color: #fff; border: none; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600; }

        /* Doctor Cards Grid */
        .doctor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .doctor-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; transition: 0.3s; text-align: center; }
        .doctor-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(12, 90, 219, 0.1); }
        .doc-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid var(--primary); padding: 3px; }
        .doc-name { font-size: 18px; font-weight: 700; color: var(--text); }
        .doc-dept { color: var(--primary); font-size: 13px; font-weight: 600; background: #f0f7ff; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-top: 5px; }
        .doc-qual { font-size: 13px; color: #718096; margin: 10px 0; min-height: 40px; }
        .doc-info { border-top: 1px solid #edf2f7; margin-top: 15px; padding-top: 15px; display: flex; justify-content: space-between; font-size: 13px; }
        .btn-book { display: block; width: 100%; background: var(--primary); color: #fff; text-decoration: none; padding: 12px; border-radius: 12px; margin-top: 20px; font-weight: 600; transition: 0.3s; }
        .btn-book:hover { background: #0a4bb5; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-house-user"></i> Dashboard</a></li>
            <li><a href="search_doctor.php" class="active"><i class="fa-solid fa-user-md"></i> Find a Doctor</a></li>
            <li><a href="my_appointments.php"><i class="fa-solid fa-calendar-check"></i> My Appointments</a></li>
            <li><a href="prescriptions.php"><i class="fa-solid fa-file-medical"></i> Prescriptions</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 25px; color: var(--text);">Find Your Specialist</h2>

        <form method="GET" class="filter-section">
            <div class="filter-group">
                <label>Search by Name</label>
                <input type="text" name="search" placeholder="Doctor name..." value="<?php echo $search_query; ?>">
            </div>
            <div class="filter-group">
                <label>Specialty</label>
                <select name="dept_id">
                    <option value="">All Departments</option>
                    <?php foreach($depts as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>" <?php echo ($dept_filter == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo $dept['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
        </form>

        <div class="doctor-grid">
            <?php if(count($doctors) > 0): ?>
                <?php foreach($doctors as $doc): ?>
                    <div class="doctor-card">
                        <img src="../assets/images/<?php echo $doc['image']; ?>" class="doc-img" alt="Doctor">
                        <div class="doc-name"><?php echo $doc['name']; ?></div>
                        <div class="doc-dept"><?php echo $doc['dept_name']; ?></div>
                        <p class="doc-qual"><?php echo $doc['qualification']; ?></p>
                        
                        <div class="doc-info">
                            <span><i class="fa-solid fa-clock"></i> <?php echo $doc['schedule_time']; ?></span>
                            <span style="font-weight:700; color: #2ecc71;"><?php echo $doc['fees']; ?> BDT</span>
                        </div>

                        <a href="book_appointment.php?doctor_id=<?php echo $doc['id']; ?>" class="btn-book">Book Appointment</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #fff; border-radius: 20px;">
                    <i class="fa-solid fa-user-slash" style="font-size: 50px; color: #cbd5e0; margin-bottom: 15px;"></i>
                    <p style="color: #718096;">No doctors found matching your search.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>