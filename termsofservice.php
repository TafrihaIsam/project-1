<?php require 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | HealthCare</title>
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
            --warning: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--bg-light); color: var(--dark); line-height: 1.8; }

        /* --- Navbar (Same as Privacy Page) --- */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 10%; background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px); position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .logo { font-size: 24px; font-weight: 800; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .nav-links { display: flex; gap: 30px; list-style: none; align-items: center; }
        .nav-links a { text-decoration: none; color: var(--dark); font-weight: 500; transition: 0.3s; font-size: 15px; }
        .btn-login { background: var(--primary); color: #fff !important; padding: 10px 25px; border-radius: 8px; font-weight: 600; transition: 0.4s ease; }

        /* --- Page Banner --- */
        .page-banner {
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            padding: 120px 8% 160px; text-align: center; color: #fff;
            position: relative;
        }
        .page-banner::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50px; background: var(--bg-light); clip-path: polygon(0 100%, 100% 100%, 100% 0); }
        .page-banner h1 { font-size: 48px; font-weight: 800; margin-bottom: 15px; }

        /* --- Terms Content --- */
        .content-section { padding: 0 8% 100px; margin-top: -80px; }
        .terms-card { 
            background: var(--white); padding: 50px 70px; border-radius: 24px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08);
            max-width: 1000px; margin: 0 auto; position: relative; z-index: 10;
        }
        .last-update { background: #fff7ed; color: var(--warning); padding: 6px 16px; border-radius: 50px; font-weight: 600; font-size: 13px; margin-bottom: 40px; display: inline-block; border: 1px solid #ffedd5; }

        .terms-item { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
        .terms-card h2 { color: var(--dark); margin-bottom: 15px; font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 12px; }
        .terms-card h2 i { color: var(--primary); font-size: 20px; }
        .terms-card p { color: var(--text-gray); font-size: 16px; text-align: justify; }
        .terms-card ul { margin-left: 20px; color: var(--text-gray); font-size: 15px; margin-top: 10px; }
        .terms-card ul li { margin-bottom: 8px; }

        /* --- Footer (Consistent) --- */
        footer { background: var(--dark); color: #94a3b8; padding: 100px 10% 40px; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 60px; }
        .footer-col h4 { color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 30px; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 40px; text-align: center; margin-top: 80px; font-size: 13px; }

        @media (max-width: 768px) {
            .terms-card { padding: 35px 25px; }
            .page-banner h1 { font-size: 34px; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fa-solid fa-house-medical"></i> HealthCare</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="login.php" class="btn-login">Login</a></li>
        </ul>
    </nav>

    <div class="page-banner">
        <h1>Terms & Conditions</h1>
        <p>Please read these rules carefully before using our hospital management system.</p>
    </div>

    <section class="content-section">
        <div class="terms-card">
            <span class="last-update"><i class="fa-solid fa-triangle-exclamation"></i> Effective Date: April 8, 2026</span>
            
            <div class="terms-item">
                <h2><i class="fa-solid fa-circle-check"></i> 1. Acceptance of Terms</h2>
                <p>Amader ei "HealthCare" platform-ti bebohar korar mane hochche apni amader niyomaboli mene nichen. Jodi apni ei terms-e raji na hon, tahole amader service bebohar na korar jonno onurodh kora holo.</p>
            </div>

            <div class="terms-item">
                <h2><i class="fa-solid fa-calendar-check"></i> 2. Appointment Booking</h2>
                <p>Online-e doctor appointment book korar somoy nicher dikgulo kheyal rakhun:</p>
                <ul>
                    <li>Apnake nishvul tottho (Naam, Mobile) provide korte hobe.</li>
                    <li>Doctor-er chamber timing jekono somoy hospital authority change korte pare.</li>
                    <li>Shorire jothil somoshya thakle online-er opekkha na kore sorasori emergency-te jogajog korun.</li>
                </ul>
            </div>

            <div class="terms-item">
                <h2><i class="fa-solid fa-file-invoice-dollar"></i> 3. Payment & Refunds</h2>
                <p>Doctor consultation fee online-e pay korle sheta ferotjoggo (non-refundable) hote pare, jodi na doctor uposthit thaken ba appointment hospital korthripokkho cancel kore.</p>
            </div>

            <div class="terms-item">
                <h2><i class="fa-solid fa-user-xmark"></i> 4. User Conduct</h2>
                <p>Amader system-e fake account khola ba bhul medical report upload kora purapuri nishiddho. Emon kichu pawa gele authority apnar account jekono somoy ban kore dite parbe.</p>
            </div>

            <div class="terms-item">
                <h2><i class="fa-solid fa-hand-holding-medical"></i> 5. Medical Advice Disclaimer</h2>
                <p>Ei system-ti sudhu matro appointment ar records manage korar jonno. App-e deya kono general tips-ke purnango medical advice hishebe dhorben na; shob somoy doctor-er sathe poramorsho korun.</p>
            </div>

            <div style="margin-top: 50px; text-align: center;">
                <p style="font-size: 14px; color: var(--text-gray);">Questions? Contact us at <strong>legal@healthcare.com</strong></p>
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