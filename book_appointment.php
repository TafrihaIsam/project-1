<?php
session_start();
require '../includes/db.php';

// Patient Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$msg_class = "";

// ১. ইউআরএল থেকে ডক্টর আইডি নেওয়া
if (!isset($_GET['doctor_id'])) {
    header("Location: search_doctor.php");
    exit();
}

$doctor_id = $_GET['doctor_id'];

// ২. ডাক্তারের ডিটেইলস ফেচ করা (কনফার্মেশনের জন্য)
$stmt = $pdo->prepare("SELECT d.*, dept.name as dept_name FROM doctors d JOIN departments dept ON d.dept_id = dept.id WHERE d.id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: search_doctor.php");
    exit();
}

// ৩. বুকিং লজিক
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_SESSION['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $reason = $_POST['reason'];

    // একই দিনে একই ডাক্তারের সাথে অলরেডি বুকিং আছে কি না চেক করা (ঐচ্ছিক কিন্তু ভালো)
    $check = $pdo->prepare("SELECT id FROM appointments WHERE patient_id = ? AND doctor_id = ? AND appointment_date = ?");
    $check->execute([$patient_id, $doctor_id, $appointment_date]);

    if ($check->rowCount() > 0) {
        $message = "You already have an appointment with this doctor on this date!";
        $msg_class = "error";
    } else {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, reason, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$patient_id, $doctor_id, $appointment_date, $reason])) {
            $message = "Appointment Booked Successfully! Please wait for confirmation.";
            $msg_class = "success";
        } else {
            $message = "Something went wrong. Please try again.";
            $msg_class = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0c5adb; --bg: #f4f7fe; --white: #ffffff; --text: #2d3748; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; min-height: 100vh; }

        .sidebar { width: 260px; height: 100vh; background: var(--white); padding: 30px 20px; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .sidebar .logo { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-links { list-style: none; }
        .nav-links a { text-decoration: none; color: #718096; display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 12px; margin-bottom: 10px; transition: 0.3s; font-weight: 500; }
        .nav-links a.active { background: var(--primary); color: #fff; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; justify-content: center; align-items: flex-start; }

        .booking-card { 
            background: #fff; width: 100%; max-width: 800px; border-radius: 20px; 
            box-shadow: 0 20px 60px rgba(12, 90, 219, 0.1); border-top: 8px solid var(--primary); overflow: hidden;
        }
        .doctor-summary { background: #fcfdfe; padding: 30px; display: flex; align-items: center; gap: 20px; border-bottom: 1px solid #edf2f7; }
        .doc-img { width: 80px; height: 80px; border-radius: 15px; object-fit: cover; border: 2px solid var(--primary); }
        .doc-details h2 { color: var(--text); font-size: 20px; }
        .doc-details p { color: var(--primary); font-weight: 600; font-size: 14px; }

        .form-body { padding: 40px; }
        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; }
        label { font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 8px; }
        input, textarea { padding: 12px 15px; border: 2px solid #edf2f7; border-radius: 12px; outline: none; transition: 0.3s; font-size: 14px; }
        input:focus, textarea:focus { border-color: var(--primary); background: #fff; box-shadow: 0 5px 15px rgba(12, 90, 219, 0.1); }

        .submit-btn { 
            background: var(--primary); color: #fff; border: none; width: 100%; padding: 16px; 
            border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; 
        }
        .submit-btn:hover { background: #0a4bb5; transform: translateY(-2px); }

        .message { padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; }
        .success { background: #dff9fb; color: #10ac84; border: 1px solid #10ac84; }
        .error { background: #ffeaa7; color: #d63031; border: 1px solid #d63031; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-square-plus"></i> HealthCare</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-house-user"></i> Dashboard</a></li>
            <li><a href="search_doctor.php" class="active"><i class="fa-solid fa-user-md"></i> Find a Doctor</a></li>
            <li><a href="my_appointments.php"><i class="fa-solid fa-calendar-check"></i> My Appointments</a></li>
            <li style="margin-top: 50px;"><a href="../logout.php" style="color: #e74c3c;"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="booking-card">
            <div class="doctor-summary">
                <img src="../assets/images/<?php echo $doctor['image']; ?>" class="doc-img">
                <div class="doc-details">
                    <h2><?php echo $doctor['name']; ?></h2>
                    <p><?php echo $doctor['dept_name']; ?> | <?php echo $doctor['qualification']; ?></p>
                    <span style="font-size: 12px; color: #718096;">Fees: <b><?php echo $doctor['fees']; ?> BDT</b> | Time: <?php echo $doctor['schedule_time']; ?></span>
                </div>
            </div>

            <div class="form-body">
                <?php if($message): ?>
                    <div class="message <?php echo $msg_class; ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Preferred Date</label>
                        <input type="date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Reason for Visit / Symptoms</label>
                        <textarea name="reason" rows="4" placeholder="Briefly describe your health issue..." required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Confirm Appointment Booking</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>