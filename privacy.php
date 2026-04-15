<?php require 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0c5adb;
            --primary-light: #e0ebff;
            --dark: #0f172a;
            --dark-blue: #1e293b;
            --text-gray: #475569;
            --bg-light: #f8fbff;
            --white: #ffffff;
            --accent: #22c55e; /* Medical Green */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--bg-light); color: var(--dark); line-height: 1.8; }

        /* --- Navbar (Enhanced) --- */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 10%; background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px); position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .logo { font-size: 24px; font-weight: 800; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .nav-links { display: flex; gap: 30px; list-style: none; align-items: center; }
        .nav-links a { text-decoration: none; color: var(--dark); font-weight: 500; transition: 0.3s; font-size: 15px; }
        .nav-links a:hover { color: var(--primary); }
        .btn-login { background: var(--primary); color: #fff !important; padding: 10px 25px; border-radius: 8px; font-weight: 600; transition: 0.4s ease; box-shadow: 0 4px 15px rgba(12, 90, 219, 0.2); }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(12, 90, 219, 0.3); }

        /* --- Modern Banner --- */
        .page-banner {
            background: radial-gradient(circle at top right, var(--dark-blue), var(--dark));
            padding: 120px 8% 160px; text-align: center; color: #fff;
            position: relative; overflow: hidden;
        }
        .page-banner::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50px; background: var(--bg-light); clip-path: polygon(0 100%, 100% 100%, 100% 0); }
        .page-banner h1 { font-size: 48px; font-weight: 800; letter-spacing: -1px; margin-bottom: 15px; }
        .page-banner p { color: #cbd5e1; font-size: 18px; max-width: 600px; margin: 0 auto; opacity: 0.9; }

        /* --- Policy Card Section --- */
        .content-section { padding: 0 8% 100px; margin-top: -80px; }
        .policy-card { 
            background: var(--white); padding: 50px 70px; border-radius: 24px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08);
            border: 1px solid rgba(226, 232, 240, 0.8); position: relative; z-index: 10;
            max-width: 1000px; margin: 0 auto;
        }
        .last-update { background: var(--primary-light); color: var(--primary); padding: 6px 16px; border-radius: 50px; font-weight: 600; font-size: 13px; margin-bottom: 40px; display: inline-block; }

        .policy-item { margin-bottom: 40px; border-left: 4px solid var(--primary-light); padding-left: 25px; transition: 0.3s; }
        .policy-item:hover { border-left-color: var(--primary); }
        
        .policy-card h2 { color: var(--dark); margin-bottom: 15px; font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 12px; }
        .policy-card h2 i { background: var(--primary-light); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; color: var(--primary); font-size: 18px; }
        .policy-card p { color: var(--text-gray); font-size: 16px; margin-bottom: 0; text-align: justify; }

        /* --- Footer (Modernized) --- */
        footer { background: var(--dark); color: #94a3b8; padding: 100px 10% 40px; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 60px; }
        .footer-col h4 { color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 30px; }
        .footer-col p { font-size: 14px; line-height: 1.8; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 15px; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s ease; }
        .footer-col ul li a:hover { color: var(--white); transform: translateX(5px); display: inline-block; }
        
        .contact-info { display: flex; flex-direction: column; gap: 15px; }
        .contact-info div { display: flex; align-items: center; gap: 12px; font-size: 14px; }
        .contact-info i { color: var(--primary); font-size: 16px; }

        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 40px; text-align: center; margin-top: 80px; font-size: 13px; color: #64748b; }

        @media (max-width: 992px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 768px) {
            .policy-card { padding: 35px 25px; }
            .page-banner h1 { font-size: 34px; }
            .footer-grid { grid-template-columns: 1fr; gap: 40px; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fa-solid fa-house-medical"></i> HealthCare</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="doctors.php">Doctors</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="login.php" class="btn-login">Login / Join</a></li>
        </ul>
    </nav>

    <div class="page-banner">
        <h1>Privacy Policy</h1>
        <p>Your trust is our priority. Learn how we keep your medical data secure and private.</p>
    </div>

    <section class="content-section">
        <div class="policy-card">
            <span class="last-update"><i class="fa-regular fa-clock"></i> Last Updated: April 8, 2026</span>
            
            <p style="font-size: 18px; color: var(--dark); margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 30px;">
                Amader <strong>HealthCare Management System</strong>-e apnar medical ebong personal data-r shopnoboro nirapotta nishchit kora amader prothom lokkho.
            </p>

            <div class="policy-item">
                <h2><i class="fa-solid fa-id-card-clip"></i> 1. Information Collection</h2>
                <p>Registration ba appointment-er somoy amra apnar name, phone, blood group, ebong medical history songroho kori. Eita muloto apnake nishvul treatment deyar jonno proyojon.</p>
            </div>

            <div class="policy-item">
                <h2><i class="fa-solid fa-shield-heart"></i> 2. Usage of Data</h2>
                <p>Apnar data sudhu matro treatment, digital prescription generate, ebong appointment schedule-er jonno bebohar kora hoy. Emergency-te apnar sathe jogajog korteo eita kaaje lage.</p>
            </div>

            <div class="policy-item">
                <h2><i class="fa-solid fa-user-lock"></i> 3. Security Standards</h2>
                <p>Amra high-level encryption bebohar kori jate apnar medical reports onno keu dekhte na pare. Sudhu apnar assigned doctor-ei eita access korte parben.</p>
            </div>

            <div class="policy-item">
                <h2><i class="fa-solid fa-handshake-slash"></i> 4. No Third-Party Sharing</h2>
                <p>HealthCare kono dhoroner pharmaceutical ba marketing company-r sathe patient-er personal details share ba bikri kore na.</p>
            </div>

            <div style="margin-top: 60px; text-align: center;">
                <a href="index.php" style="color: var(--primary); text-decoration: none; font-weight: 700; border: 2px solid var(--primary-light); padding: 12px 30px; border-radius: 12px; transition: 0.3s;">
                    <i class="fa-solid fa-house"></i> Return to Homepage
                </a>
            </div>
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