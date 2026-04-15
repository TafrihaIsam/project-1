<?php
require 'includes/db.php';

$message = "";
$msg_class = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $message = "Email is already registered!";
        $msg_class = "error";
    } else {
        $sql = "INSERT INTO users (full_name, email, phone, gender, dob, blood_group, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'patient')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$full_name, $email, $phone, $gender, $dob, $blood_group, $password])) {
            $message = "Profile Created Successfully! <a href='login.php'>Login</a>";
            $msg_class = "success";
        } else {
            $message = "Registration failed. Please try again.";
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
    <title>Register | HealthCare Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0c5adb; /* Clinical Blue */
            --secondary-color: #f0f7ff; /* Soft Background Blue */
            --accent-color: #2ecc71; /* Health/Success Green */
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
            max-width: 550px;
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
        .success { background: #dff9fb; color: #10ac84; border: 1px solid #10ac84; }
        .error { background: #ffeaa7; color: #d63031; border: 1px solid #d63031; }

        .row { display: flex; gap: 20px; margin-bottom: 15px; }
        .form-group { flex: 1; display: flex; flex-direction: column; }
        
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
            margin-top: 20px;
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

        @media (max-width: 500px) { .row { flex-direction: column; gap: 15px; } }
    </style>
</head>
<body>

<div class="hospital-card">
    <div class="card-header">
        <div class="logo">
            <span class="logo-plus">+</span> HealthCare
        </div>
        <p>Patient Portal | Secure Registration</p>
    </div>

    <div class="form-body">
        <?php if($message): ?>
            <div class="message <?php echo $msg_class; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Full Patient Name</label>
                <input type="text" name="full_name" placeholder="Ex: Mr. Hemal Islam" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="hemal@hospital.com" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="phone" placeholder="017XXXXXXXX" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                </div>
                <div class="form-group">
                    <label>Gender Identity</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" required>
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Security Password</label>
                    <input type="password" name="password" placeholder="Create strong password" required>
                </div>
            </div>

            <button type="submit" class="submit-btn">Create Medical Profile</button>
        </form>
    </div>

    <div class="footer">
        Already registered? <a href="login.php">Login to Dashboard</a>
    </div>
</div>

</body>
</html>