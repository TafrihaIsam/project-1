<?php
require 'includes/db.php';

// ডাটাবেস থেকে প্রথম ৬টি ডিপার্টমেন্ট ফেচ করা
$depts = $pdo->query("SELECT * FROM departments LIMIT 6")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthCare | Premium Medical Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0c5adb;
            --primary-hover: #0a4bb5;
            --dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fbff;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--white); color: var(--dark); overflow-x: hidden; }

        /* --- Navbar --- */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 8%; background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 30px rgba(0,0,0,0.03);
        }
        .logo { font-size: 26px; font-weight: 800; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .nav-links { display: flex; gap: 35px; list-style: none; align-items: center; }
        .nav-links a { text-decoration: none; color: var(--dark); font-weight: 500; transition: 0.3s; font-size: 15px; }
        .nav-links a:hover { color: var(--primary); }
        .btn-login { background: var(--primary); color: #fff !important; padding: 12px 28px; border-radius: 50px; font-weight: 600; box-shadow: 0 10px 20px rgba(12, 90, 219, 0.15); }

        /* --- Hero Section --- */
        .hero {
            padding: 80px 8%; background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
            display: flex; align-items: center; min-height: 85vh;
        }
        .hero-content { flex: 1; }
        .hero-content h1 { font-size: 56px; line-height: 1.1; font-weight: 800; color: var(--dark); margin-bottom: 25px; }
        .hero-content h1 span { color: var(--primary); }
        .hero-content p { font-size: 18px; color: var(--text-gray); margin-bottom: 35px; max-width: 550px; }
        
        .hero-image { flex: 1; display: flex; justify-content: flex-end; }
        .hero-image img { width: 100%; max-width: 550px; border-radius: 30px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.1)); }

        .btn-group { display: flex; gap: 20px; }
        .btn { padding: 16px 35px; border-radius: 12px; text-decoration: none; font-weight: 600; transition: 0.3s; display: inline-block; }
        .btn-filled { background: var(--primary); color: #fff; box-shadow: 0 10px 25px rgba(12, 90, 219, 0.2); }
        .btn-outline { border: 2px solid var(--primary); color: var(--primary); }
        .btn:hover { transform: translateY(-3px); }

        /* --- Departments Section --- */
        .section-padding { padding: 100px 8%; background: var(--bg-light); }
        .section-title { text-align: center; margin-bottom: 60px; }
        .section-title h2 { font-size: 36px; font-weight: 700; margin-bottom: 15px; }
        .section-title span { color: var(--primary); }

        .dept-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .dept-card {
            background: var(--white); padding: 40px; border-radius: 24px;
            border: 1px solid #f0f4f8; transition: 0.4s;
        }
        .dept-card:hover { border-color: var(--primary); transform: translateY(-10px); box-shadow: 0 20px 40px rgba(12, 90, 219, 0.05); }
        .dept-card i { font-size: 35px; color: var(--primary); margin-bottom: 20px; display: block; }
        .dept-card h3 { font-size: 20px; margin-bottom: 12px; }
        .dept-card p { color: var(--text-gray); font-size: 14px; margin-bottom: 20px; }
        .dept-card .link { color: var(--primary); font-weight: 700; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px; }

        /* --- Premium Footer --- */
        footer {
            background: var(--dark); color: #94a3b8; padding: 80px 8% 30px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.2fr; gap: 50px; margin-bottom: 50px;
        }
        .footer-col h4 {
            color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 25px; position: relative;
        }
        .footer-col h4::after {
            content: ''; position: absolute; left: 0; bottom: -8px; width: 30px; height: 2px; background: var(--primary);
        }
        .footer-col p { line-height: 1.8; font-size: 14px; margin-bottom: 20px; }
        .social-links { display: flex; gap: 15px; }
        .social-links a {
            width: 38px; height: 38px; background: rgba(255, 255, 255, 0.05); color: #fff;
            display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: 0.3s; text-decoration: none;
        }
        .social-links a:hover { background: var(--primary); transform: translateY(-3px); }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
        .footer-col ul li a:hover { color: var(--primary); padding-left: 5px; }
        .contact-item { display: flex; gap: 15px; margin-bottom: 20px; }
        .contact-item i { color: var(--primary); font-size: 18px; margin-top: 4px; }
        .contact-item h5 { color: #fff; font-size: 15px; margin-bottom: 3px; }
        .contact-item p { font-size: 13px; margin-bottom: 0; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 30px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }

        @media (max-width: 992px) {
            .hero { flex-direction: column; text-align: center; }
            .hero-image { margin-top: 50px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 576px) {
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 20px; text-align: center; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fa-solid fa-house-medical"></i> HealthCare</a>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#departments">Departments</a></li>
            <li><a href="login.php" class="btn-login">Get Started</a></li>
        </ul>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Expert Care for <span>A Better Life.</span></h1>
            <p>Your health is our priority. Experience world-class healthcare with our expert doctors. Book your appointment today and take the first step towards a healthier life.</p>
            <div class="btn-group">
                <a href="login.php" class="btn btn-filled">Book Appointment</a>
                <a href="#departments" class="btn btn-outline">See Departments</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://img.freepik.com/free-photo/doctor-offering-medical-teleconsultation_23-2149329007.jpg" alt="Doctor Consultation">
        </div>
    </section>

    <section class="section-padding" id="departments">
        <div class="section-title">
            <h2>Our Specialized <span>Departments</span></h2>
            <p>Providing expert medical consultation across 31+ specialized categories.</p>
        </div>

        <div class="dept-grid">
            <?php foreach($depts as $dept): ?>
                <div class="dept-card">
                    <i class="fa-solid fa-hand-holding-medical"></i>
                    <h3><?php echo $dept['name']; ?></h3>
                    <p>Consult with our experienced specialists in the <?php echo $dept['name']; ?> department for expert care.</p>
                    <a href="login.php" class="link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="login.php" style="color: var(--primary); font-weight: 700; text-decoration: none;">View All 31+ Departments <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <a href="#" class="logo" style="color: #fff; margin-bottom: 20px; display: inline-flex;">
                    <i class="fa-solid fa-house-medical"></i> HealthCare
                </a>
                <p>Ensuring quality healthcare services through innovation and expertise. Your trust is our greatest achievement.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="login.php"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> Find Doctor</a></li>
                    <li><a href="login.php"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> Book Appointment</a></li>
                    <li><a href="#"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> About Us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="privacy.php"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> Privacy Policy</a></li>
                    <li><a href="termsofservice.php"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> Terms of Service</a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> Contact Support</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Emergency Contact</h4>
                <div class="contact-item">
                    <i class="fa-solid fa-phone-volume"></i>
                    <div>
                        <h5>Emergency Call</h5>
                        <p>+880 123 456 7890</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <h5>Our Location</h5>
                        <p>Uttara Sector 10, Dhaka</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 HealthCare Hospital. All Rights Reserved. | Tafriha & Tabassum</p>
            <p>Designed with <i class="fa-solid fa-heart" style="color: #e74c3c;"></i> for better health.</p>
        </div>
    </footer>

</body>
</html>