<?php
session_start();
require '../includes/db.php';

// Admin Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$msg_class = "";

// Fetch Departments
$depts = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $dept_id = $_POST['dept_id'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $fees = $_POST['fees'];
    $schedule = $_POST['schedule'];

    // Image Upload Logic
    $image_name = "default_doctor.png";
    if (isset($_FILES['doctor_image']) && $_FILES['doctor_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES["doctor_image"]["name"], PATHINFO_EXTENSION);
        $image_name = time() . "_" . uniqid() . "." . $file_ext;
        move_uploaded_file($_FILES["doctor_image"]["tmp_name"], $target_dir . $image_name);
    }

    $stmt = $pdo->prepare("SELECT id FROM doctors WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $message = "Doctor email already exists!";
        $msg_class = "error";
    } else {
        $sql = "INSERT INTO doctors (name, email, password, image, dept_id, qualification, experience, fees, schedule_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$name, $email, $password, $image_name, $dept_id, $qualification, $experience, $fees, $schedule])) {
            $message = "Doctor Profile Created Successfully!";
            $msg_class = "success";
        } else {
            $message = "Error: Could not save data.";
            $msg_class = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor | HealthCare Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0c5adb;
            --secondary-color: #f0f7ff;
            --text-dark: #1a2b48;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background-color: var(--secondary-color); 
            display: flex; 
            min-height: 100vh;
        }

        /* --- FIXED SIDEBAR --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--white);
            padding: 30px 20px;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
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
        .nav-links li { margin-bottom: 10px; }
        .nav-links a {
            text-decoration: none;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 12px;
            transition: 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background: var(--primary-color);
            color: #fff;
        }

        /* --- MAIN CONTENT AREA --- */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* --- PREMIUM HOSPITAL CARD DESIGN --- */
        .hospital-card {
            background: var(--white);
            width: 100%;
            max-width: 850px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(12, 90, 219, 0.1);
            border-top: 8px solid var(--primary-color);
            overflow: hidden;
            margin-top: 20px;
        }

        .card-header {
            text-align: center;
            padding: 30px;
            background: #fcfdfe;
            border-bottom: 1px solid #edf2f7;
        }

        .card-header h2 {
            color: var(--primary-color);
            font-size: 26px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-body { padding: 40px; }

        .message {
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .success { background: #dff9fb; color: #10ac84; border: 1px solid #10ac84; }
        .error { background: #ffeaa7; color: #d63031; border: 1px solid #d63031; }

        .row { display: flex; gap: 25px; margin-bottom: 20px; }
        .form-group { flex: 1; display: flex; flex-direction: column; }
        
        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            padding-left: 5px;
        }

        input, select {
            padding: 12px 15px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            font-size: 14px;
            transition: 0.3s;
            background: #fcfdfe;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 5px 15px rgba(12, 90, 219, 0.05);
        }

        .submit-btn {
            background: var(--primary-color);
            color: #fff;
            border: none;
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(12, 90, 219, 0.2);
        }

        .submit-btn:hover {
            background: #0a4bb5;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .row { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="add_doctor.php" class="active"><i class="fa-solid fa-user-doctor"></i> Add Doctor</a></li>
            <li><a href="manage_doctors.php"><i class="fa-solid fa-users-gear"></i> Manage Doctors</a></li>
            <li><a href="view_patients.php"><i class="fa-solid fa-hospital-user"></i> Patients</a></li>
            <li><a href="all_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="hospital-card">
            <div class="card-header">
                <h2><i class="fa-solid fa-user-plus"></i> Register Professional Doctor</h2>
                <p style="color: #7f8c8d; font-size: 13px; margin-top: 5px;">Fill all fields to add a specialist to the system</p>
            </div>

            <div class="form-body">
                <?php if($message): ?>
                    <div class="message <?php echo $msg_class; ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group">
                            <label>Doctor's Profile Photo</label>
                            <input type="file" name="doctor_image" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" placeholder="Ex: Dr. Rohejul Islam" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Official Email</label>
                            <input type="email" name="email" placeholder="doctor@healthcare.com" required>
                        </div>
                        <div class="form-group">
                            <label>Specialty / Department</label>
                            <select name="dept_id" required>
                                <option value="">-- Select Specialty --</option>
                                <?php foreach($depts as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Qualification</label>
                            <input type="text" name="qualification" placeholder="MBBS, FCPS, FRCS" required>
                        </div>
                        <div class="form-group">
                            <label>Consultation Fees (BDT)</label>
                            <input type="number" name="fees" placeholder="1000" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Duty Hours / Schedule</label>
                            <input type="text" name="schedule" placeholder="Sat-Thu (05:00 PM - 09:00 PM)" required>
                        </div>
                        <div class="form-group">
                            <label>Years of Experience</label>
                            <input type="number" name="experience" placeholder="e.g. 12" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Doctor's Account Password</label>
                        <input type="password" name="password" placeholder="Create a secure password" required>
                    </div>

                    <button type="submit" class="submit-btn">Authorize & Save Doctor Profile</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>