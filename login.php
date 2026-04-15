<?php
session_start();
require 'includes/db.php';

$message = "";
$msg_class = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // ইউজার যে রোল সিলেক্ট করেছে

    if ($role == 'patient' || $role == 'admin') {
        // Patient বা Admin এর জন্য 'users' টেবিল চেক করবে
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: patient/dashboard.php");
            }
            exit();
        } else {
            $message = "Invalid Email, Password or Role!";
            $msg_class = "error";
        }
    } elseif ($role == 'doctor') {
        // Doctor এর জন্য 'doctors' টেবিল চেক করবে
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = ?");
        $stmt->execute([$email]);
        $doctor = $stmt->fetch();

        if ($doctor && password_verify($password, $doctor['password'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['name'];
            $_SESSION['user_role'] = 'doctor';
            header("Location: doctor/dashboard.php");
            exit();
        } else {
            $message = "Invalid Doctor Credentials!";
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
    <title>Login | HealthCare Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background-image: linear-gradient(rgba(12, 90, 219, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(12, 90, 219, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .hospital-card {
            background: var(--white);
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(12, 90, 219, 0.15);
            border-top: 8px solid var(--primary-color);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .card-header {
            text-align: center;
            padding: 40px 30px 20px;
        }

        .card-header .logo {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo-plus { color: #e74c3c; font-size: 36px; }

        .card-header p {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }

        .form-body { padding: 0 40px 40px; }

        .message {
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .error { background: #ffeaa7; color: #d63031; border: 1px solid #d63031; }

        .form-group { display: flex; flex-direction: column; margin-bottom: 20px; }
        
        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            padding-left: 5px;
        }

        input, select {
            padding: 12px 15px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            background: #fcfdfe;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 5px 15px rgba(12, 90, 219, 0.1);
        }

        .submit-btn {
            background: var(--primary-color);
            color: #fff;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(12, 90, 219, 0.2);
        }

        .submit-btn:hover { background: #0a4bb5; transform: translateY(-2px); }

        .footer {
            text-align: center;
            padding: 20px;
            background: #f9fbff;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #636e72;
        }

        .footer a { color: var(--primary-color); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>

<div class="hospital-card">
    <div class="card-header">
        <div class="logo">
            <span class="logo-plus">+</span> HealthCare
        </div>
        <p>Login to your specific portal</p>
    </div>

    <div class="form-body">
        <?php if($message): ?>
            <div class="message <?php echo $msg_class; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Login As</label>
                <select name="role" required>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="submit-btn">Authorize & Login</button>
        </form>
    </div>

    <div class="footer">
        New patient? <a href="register.php">Register Here</a>
    </div>
</div>

</body>
</html>