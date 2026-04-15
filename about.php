<?php require 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Our Excellence | HealthCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0c5adb;
            --dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fbff;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--white); color: var(--dark); line-height: 1.6; }

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
        .btn-login { background: var(--primary); color: #fff !important; padding: 12px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; }

        /* --- Page Banner --- */
        .page-banner {
            background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
            padding: 120px 8%; text-align: center; color: #fff;
        }
        .page-banner h1 { font-size: 48px; font-weight: 800; margin-bottom: 15px; }
        .page-banner p { color: #94a3b8; font-size: 18px; max-width: 700px; margin: 0 auto; }

        /* --- Main Content --- */
        .section-padding { padding: 100px 8%; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
        .about-img { position: relative; }
        .about-img img { width: 100%; border-radius: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
        .experience-badge {
            position: absolute; bottom: -30px; right: -30px; background: var(--primary);
            color: #fff; padding: 30px; border-radius: 20px; text-align: center;
            box-shadow: 0 15px 30px rgba(12, 90, 219, 0.3);
        }
        .experience-badge h2 { font-size: 35px; font-weight: 800; }

        /* --- Mission & Vision Cards --- */
        .vision-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 80px; }
        .vision-card {
            background: #fff; padding: 50px; border-radius: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.02); border: 1px solid #f0f4f8; transition: 0.3s;
        }
        .vision-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        .vision-card i { font-size: 40px; color: var(--primary); margin-bottom: 25px; display: block; }
        .vision-card h3 { margin-bottom: 15px; font-size: 22px; }

        /* --- Values Section --- */
        .values-section { background: var(--bg-light); padding: 100px 8%; text-align: center; }
        .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-top: 60px; }
        .value-item i { font-size: 45px; color: var(--primary); margin-bottom: 20px; }
        .value-item h4 { margin-bottom: 10px; font-weight: 700; }

        /* --- Footer (Same as index) --- */
        footer { background: var(--dark); color: #94a3b8; padding: 80px 8% 30px; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.2fr; gap: 50px; }
        .footer-col h4 { color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 25px; position: relative; }
        .footer-col h4::after { content: ''; position: absolute; left: 0; bottom: -8px; width: 30px; height: 2px; background: var(--primary); }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .footer-col ul li a:hover { color: var(--primary); padding-left: 5px; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 30px; text-align: center; margin-top: 50px; font-size: 13px; }

        @media (max-width: 992px) { .about-grid { grid-template-columns: 1fr; } .footer-grid { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fa-solid fa-house-medical"></i> HealthCare</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" style="color: var(--primary);">About</a></li>
            <li><a href="login.php" class="btn-login">Get Started</a></li>
        </ul>
    </nav>

    <div class="page-banner">
        <h1>Transforming Healthcare in Uttara</h1>
        <p>Combining world-class medical expertise with deep compassion to serve our community since 2026.</p>
    </div>

    <section class="section-padding">
        <div class="about-grid">
            <div class="about-img">
                <img src="https://img.freepik.com/free-photo/portrait-successful-medical-team-hospital_23-2148901768.jpg" alt="HealthCare Team">
                <div class="experience-badge">
                    <h2>10+</h2>
                    <p>Years of Medical <br> Excellence</p>
                </div>
            </div>
            <div class="about-text">
                <h4 style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 14px; margin-bottom: 15px;">Welcome to HealthCare</h4>
                <h2 style="font-size: 38px; margin-bottom: 25px;">The Great Place of Medical Hospital Center</h2>
                <p style="margin-bottom: 20px;">HealthCare Hospital is a leading provider of medical services in Dhaka. We offer a wide range of medical specialties and diagnostic services, all delivered by a team of highly skilled and compassionate medical professionals.</p>
                <p style="margin-bottom: 30px; color: var(--text-gray);">Our facility is equipped with the latest medical technology and advanced treatment rooms, ensuring that our patients receive the most accurate diagnosis and effective treatment plans possible.</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-square-check" style="color: var(--primary);"></i>
                        <span style="font-weight: 600;">Certified Specialists</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-square-check" style="color: var(--primary);"></i>
                        <span style="font-weight: 600;">Modern Technology</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-square-check" style="color: var(--primary);"></i>
                        <span style="font-weight: 600;">24/7 Emergency</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-square-check" style="color: var(--primary);"></i>
                        <span style="font-weight: 600;">Affordable Care</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="vision-grid">
            <div class="vision-card">
                <i class="fa-solid fa-bullseye"></i>
                <h3>Our Mission</h3>
                <p style="color: var(--text-gray); font-size: 14px;">To provide superior healthcare services that improve the lives of our patients and the community we serve through clinical excellence.</p>
            </div>
            <div class="vision-card">
                <i class="fa-solid fa-eye"></i>
                <h3>Our Vision</h3>
                <p style="color: var(--text-gray); font-size: 14px;">To be the first choice for patients by providing high-quality medical services with advanced technology and compassion.</p>
            </div>
            <div class="vision-card">
                <i class="fa-solid fa-heart-pulse"></i>
                <h3>Our Commitment</h3>
                <p style="color: var(--text-gray); font-size: 14px;">We are committed to delivering safe, effective, and patient-centered care to every individual who walks through our doors.</p>
            </div>
        </div>
    </section>

    <section class="values-section">
        <h2 style="font-size: 32px; margin-bottom: 10px;">Our Core Values</h2>
        <p style="color: var(--text-gray);">The principles that guide our everyday actions.</p>
        <div class="values-grid">
            <div class="value-item">
                <i class="fa-solid fa-hand-holding-heart"></i>
                <h4>Compassion</h4>
                <p style="font-size: 14px; color: var(--text-gray);">Treating every patient with kindness and empathy.</p>
            </div>
            <div class="value-item">
                <i class="fa-solid fa-shield-halved"></i>
                <h4>Integrity</h4>
                <p style="font-size: 14px; color: var(--text-gray);">Maintaining the highest ethical standards in medicine.</p>
            </div>
            <div class="value-item">
                <i class="fa-solid fa-microscope"></i>
                <h4>Innovation</h4>
                <p style="font-size: 14px; color: var(--text-gray);">Continuously improving through research and technology.</p>
            </div>
            <div class="value-item">
                <i class="fa-solid fa-users-viewfinder"></i>
                <h4>Teamwork</h4>
                <p style="font-size: 14px; color: var(--text-gray);">Collaborating across departments for better results.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <a href="#" class="logo" style="color: #fff; margin-bottom: 20px; display: inline-flex;">
                    <i class="fa-solid fa-house-medical"></i> HealthCare
                </a>
                <p>Providing expert medical consultation across 31+ specialized categories in Dhaka.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="login.php">Book Appointment</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p style="font-size: 14px; margin-bottom: 10px;"><i class="fa-solid fa-phone" style="color: var(--primary);"></i> +880 123 456 7890</p>
                <p style="font-size: 14px;"><i class="fa-solid fa-location-dot" style="color: var(--primary);"></i> Uttara, Sector 10, Dhaka</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 HealthCare Hospital Management System. All Rights Reserved. | MD Rohejul Islam Hemal</p>
        </div>
    </footer>

</body>
</html>