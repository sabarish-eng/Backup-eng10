<?php
include("dbconnect.php");

if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    mysqli_query($conn, "UPDATE stureg SET status='approved' WHERE id='$id'");
    echo "<script>alert('Student approved successfully');</script>";
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($conn, "UPDATE stureg SET status='rejected' WHERE id='$id'");
    echo "<script>alert('Student rejected successfully');</script>";
}

$result = mysqli_query($conn, "SELECT * FROM stureg WHERE status='pending'");
?>






























<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Voting System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --secondary: #EC4899;
            --dark: #1F2937;
            --light: #F9FAFB;
        }

        body {
            line-height: 1.6;
            color: var(--dark);
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: var(--primary);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background: var(--primary);
            transition: all 0.3s;
        }

        .hero {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(236, 72, 153, 0.9)), url('/api/placeholder/1920/1080');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 6rem 1rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .hero-content {
            max-width: 800px;
            position: relative;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            background: white;
            color: var(--primary);
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .features {
            padding: 6rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.5rem;
            color: var(--dark);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            text-align: center;
            padding: 2.5rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .feature-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            margin: 1rem 0;
            color: var(--dark);
            font-size: 1.5rem;
        }

        .footer {
            background: var(--dark);
            color: white;
            padding: 4rem 1rem 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            color: var(--secondary);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            opacity: 0.8;
            transition: all 0.3s;
        }

        .social-links a:hover {
            opacity: 1;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
                text-align: center;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .feature-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="logo">
                <i class="fas fa-vote-yea"></i>
                College Vote
            </div>
            <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="nav-links">
                <a href="add_election.php">Home</a>
                <a href="add_candidate.php">Add Candidate</a>
                <a href="manage_students.php">Manage Student</a>
                <a href="admin_announce_result.php">Results</a>
				   <a href="index.php">Logout</a>
            </div>
        </div>
    </nav>




<br /><br /><br /><br />

<div style="overflow-x: auto; background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); font-family: system-ui, -apple-system, sans-serif;">
  <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
    <tr style="background-color: #f8f9fa;">
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">ID</th>
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">Name</th>
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">Email</th>
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">Year</th>
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">Department</th>
      <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #e2e8f0;">Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr style="border-bottom: 1px solid #e2e8f0; hover:background-color: #f8fafc;">
        <td style="padding: 12px 16px; color: #4a5568;"><?php echo $row['id']; ?></td>
        <td style="padding: 12px 16px; color: #4a5568;"><?php echo $row['name']; ?></td>
        <td style="padding: 12px 16px; color: #4a5568;"><?php echo $row['email']; ?></td>
        <td style="padding: 12px 16px; color: #4a5568;"><?php echo $row['year']; ?></td>
        <td style="padding: 12px 16px; color: #4a5568;"><?php echo $row['dep']; ?></td>
        <td style="padding: 12px 16px; color: #4a5568;">
          <a href="manage_students.php?approve=<?php echo $row['id']; ?>" 
             style="display: inline-block; padding: 6px 12px; background-color: #22c55e; color: white; text-decoration: none; border-radius: 4px; margin-right: 8px; font-size: 12px;">
            Approve
          </a>
          <a href="manage_students.php?reject=<?php echo $row['id']; ?>" 
             style="display: inline-block; padding: 6px 12px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;">
            Reject
          </a>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>






<br /><br /><br /><br />



    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>College Vote is committed to fostering democratic participation and ensuring fair, transparent elections within our campus community.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">How It Works</a></li>
                    <li><a href="#">Election Rules</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-envelope"></i> support@collegevote.edu</li>
                    <li><i class="fas fa-phone"></i> (555) 123-4567</li>
                    <li><i class="fas fa-map-marker-alt"></i> Student Center, Room 101</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 College Vote. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');

        });

        // Close menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });

      
    </script>
</body>
</html>




