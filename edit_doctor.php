<?php
session_start();
require '../includes/db.php';

// Admin Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$message = "";
$msg_class = "";

// ডাক্তার এর বর্তমান ডাটা ফেচ করা
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: manage_doctors.php");
    exit();
}

$depts = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dept_id = $_POST['dept_id'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $fees = $_POST['fees'];
    $schedule = $_POST['schedule'];

    // ইমেজ আপডেট লজিক
    if (isset($_FILES['doctor_image']) && $_FILES['doctor_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_ext = pathinfo($_FILES["doctor_image"]["name"], PATHINFO_EXTENSION);
        $image_name = time() . "_" . uniqid() . "." . $file_ext;
        move_uploaded_file($_FILES["doctor_image"]["tmp_name"], $target_dir . $image_name);
        
        // পুরনো ইমেজ ডিলিট (যদি ডিফল্ট না হয়)
        if ($doctor['image'] !== 'default_doctor.png' && file_exists($target_dir . $doctor['image'])) {
            unlink($target_dir . $doctor['image']);
        }
    } else {
        $image_name = $doctor['image']; 
    }

    $sql = "UPDATE doctors SET name=?, email=?, image=?, dept_id=?, qualification=?, experience=?, fees=?, schedule_time=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$name, $email, $image_name, $dept_id, $qualification, $experience, $fees, $schedule, $id])) {
        header("Location: manage_doctors.php?msg=Doctor Updated Successfully");
        exit();
    } else {
        $message = "Failed to update profile.";
        $msg_class = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor | HealthCare Hospital</title>
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
        body { background-color: var(--secondary-color); display: flex; min-height: 100vh; }

        /* --- FIXED SIDEBAR (Same as add_doctor) --- */
        .sidebar {
            width: 260px; height: 100vh; background: var(--white); padding: 30px 20px;
            position: fixed; left: 0; top: 0; box-shadow: 4px 0 10px rgba(0,0,0,0.05); z-index: 1000;
        }
        .sidebar .logo {
            font-size: 22px; font-weight: 800; color: var(--primary-color);
            margin-bottom: 40px; display: flex; align-items: center; gap: 10px;
        }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 10px; }
        .nav-links a {
            text-decoration: none; color: #718096; display: flex; align-items: center;
            gap: 12px; padding: 12px 18px; border-radius: 12px; transition: 0.3s; font-weight: 500;
        }
        .nav-links a:hover, .nav-links a.active { background: var(--primary-color); color: #fff; }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: 260px; width: calc(100% - 260px); padding: 40px;
            display: flex; flex-direction: column; align-items: center;
        }

        /* --- PREMIUM HOSPITAL CARD --- */
        .hospital-card {
            background: var(--white); width: 100%; max-width: 850px;
            border-radius: 20px; box-shadow: 0 20px 60px rgba(12, 90, 219, 0.1);
            border-top: 8px solid var(--primary-color); overflow: hidden;
        }
        .card-header {
            text-align: center; padding: 30px; background: #fcfdfe; border-bottom: 1px solid #edf2f7;
        }
        .card-header h2 { color: var(--primary-color); font-size: 26px; font-weight: 700; }

        .form-body { padding: 40px; }
        .row { display: flex; gap: 25px; margin-bottom: 20px; }
        .form-group { flex: 1; display: flex; flex-direction: column; }
        
        label { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; padding-left: 5px; }
        input, select {
            padding: 12px 15px; border: 2px solid #edf2f7; border-radius: 12px;
            font-size: 14px; transition: 0.3s; background: #fcfdfe;
        }
        input:focus, select:focus { outline: none; border-color: var(--primary-color); background: #fff; box-shadow: 0 5px 15px rgba(12, 90, 219, 0.05); }

        .submit-btn {
            background: var(--primary-color); color: #fff; border: none; width: 100%;
            padding: 16px; border-radius: 12px; font-size: 16px; font-weight: 700;
            cursor: pointer; margin-top: 15px; transition: 0.3s;
        }
        .submit-btn:hover { background: #0a4bb5; transform: translateY(-2px); }

        .current-img-box {
            display: flex; align-items: center; gap: 15px; margin-bottom: 10px;
            padding: 10px; background: #f8fbff; border-radius: 12px; border: 1px dashed #cbd5e0;
        }
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
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="hospital-card">
            <div class="card-header">
                <h2><i class="fa-solid fa-user-edit"></i> Edit Doctor Profile</h2>
            </div>

            <div class="form-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group">
                            <label>Profile Image (Update)</label>
                            <div class="current-img-box">
                                <img src="../assets/images/<?php echo $doctor['image']; ?>" width="50" height="50" style="border-radius: 8px; object-fit: cover;">
                                <span style="font-size: 12px; color: #718096;">Current Photo</span>
                            </div>
                            <input type="file" name="doctor_image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo $doctor['name']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Official Email</label>
                            <input type="email" name="email" value="<?php echo $doctor['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Specialty</label>
                            <select name="dept_id" required>
                                <?php foreach($depts as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>" <?php echo ($dept['id'] == $doctor['dept_id']) ? 'selected' : ''; ?>>
                                        <?php echo $dept['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Qualification</label>
                            <input type="text" name="qualification" value="<?php echo $doctor['qualification']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Fees (BDT)</label>
                            <input type="number" name="fees" value="<?php echo $doctor['fees']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Schedule</label>
                            <input type="text" name="schedule" value="<?php echo $doctor['schedule_time']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Experience (Years)</label>
                            <input type="number" name="experience" value="<?php echo $doctor['experience']; ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Update Profile Information</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>