<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark-green: #0C3B2E;
            --forest-green: #0A2F24;
            --gold: #D4AF37;
            --gold-tint: #F7EED3;
            --bg-light: #FDFCF8;
            --white: #FFFFFF;
            --text-dark: #1f1f1f;
            --text-muted: #6d6d6d;
            --radius-card: 20px;
            --radius-input: 12px;
            --shadow: 0px 8px 20px rgba(0, 0, 0, 0.08);
            --shadow-light: 0px 4px 10px rgba(0, 0, 0, 0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--bg-light); font-family: "Inter", sans-serif; line-height: 1.6; color: var(--text-dark); min-height: 100vh; }

        .system-header {
            background: var(--dark-green);
            color: var(--gold);
            padding: 1.5rem 0;
            text-align: center;
            border-bottom: 3px solid var(--gold);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .header-logo {
            height: 70px;
            width: auto;
        }

        .system-title {
            font-family: "Playfair Display", serif;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .system-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            color: var(--gold-tint);
        }

        .dashboard-container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--dark-green), var(--forest-green)); color: var(--white); padding: 2rem; border-radius: var(--radius-card); margin-bottom: 2rem; border-left: 6px solid var(--gold); }
        .page-title { font-family: "Playfair Display", serif; color: var(--gold); margin-bottom: 0.5rem; font-size: 2rem; }
        .page-subtitle { color: var(--gold-tint); font-size: 1.1rem; }

        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 1.5rem; 
            margin-bottom: 2rem; 
        }
        .stat-card { 
            background: var(--white); 
            padding: 1.5rem; 
            border-radius: var(--radius-card); 
            box-shadow: var(--shadow); 
            text-align: center; 
            border-top: 4px solid var(--gold);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--forest-green);
            display: inline-block;
            padding: 0.8rem;
            border-radius: 50%;
            background: var(--gold-tint);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }
        .stat-value { 
            font-size: 2.5rem; 
            font-weight: 700; 
            color: var(--forest-green); 
            margin-bottom: 0.5rem; 
        }
        .stat-label { 
            color: var(--text-muted); 
            font-size: 0.9rem; 
            font-weight: 500;
        }

        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .action-card { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); text-align: center; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease; border: 2px solid transparent; }
        .action-card:hover { transform: translateY(-5px); border-color: var(--gold); }
        .action-icon { font-size: 3rem; margin-bottom: 1rem; color: var(--gold); transition: transform 0.3s ease; }
        .action-card:hover .action-icon {
            transform: scale(1.1);
        }
        .action-title { font-weight: 600; margin-bottom: 0.5rem; color: var(--forest-green); font-size: 1.2rem; }
        .action-desc { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; }

        .nav-header { background: var(--forest-green); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-link { color: var(--gold-tint); text-decoration: none; padding: 0.7rem 1.2rem; border-radius: var(--radius-input); transition: all 0.3s ease; font-weight: 500; }
        .nav-link.active { background: var(--gold); color: var(--forest-green); }
        .nav-link:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); }
        .user-info { display: flex; align-items: center; gap: 1rem; color: var(--gold-tint); }
        .user-badge { background: var(--gold); color: var(--forest-green); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .btn-logout { background: var(--gold); color: var(--forest-green); padding: 0.7rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; }
        .btn-logout:hover { background: #c19a2f; transform: translateY(-2px); }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--gold);
        }

        .system-footer { background: var(--forest-green); color: var(--gold-tint); padding: 2rem 1rem; border-top: 2px solid var(--gold); margin-top: 3rem; }
        .footer-content { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
        .footer-info h4 { color: var(--gold); margin-bottom: 0.5rem; font-family: 'Playfair Display', serif; font-size: 1.1rem; }
        .footer-help { text-align: right; }
        .footer-help strong { color: var(--gold); }
        .footer-copyright { border-top: 1px solid rgba(212, 175, 55, 0.3); padding-top: 1rem; margin-top: 1rem; text-align: center; font-size: 0.85rem; opacity: 0.8; width: 100%; }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .activity-title {
            color: var(--forest-green);
            font-size: 1.3rem;
            font-weight: 600;
        }
        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--gold-tint);
            border-radius: var(--radius-input);
            margin-bottom: 0.8rem;
            transition: transform 0.2s ease;
        }
        .activity-item:hover {
            transform: translateX(5px);
        }
        .activity-item:last-child {
            margin-bottom: 0;
        }
        .activity-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .activity-icon {
            font-size: 1.2rem;
            color: var(--forest-green);
        }
        .activity-details {
            display: flex;
            flex-direction: column;
        }
        .activity-equipment {
            font-weight: 600;
            color: var(--forest-green);
        }
        .activity-date {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .activity-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-pending { background: #fff3e0; color: #f57c00; }
        .status-approved { background: #e8f5e8; color: #2e7d32; }
        .status-rejected { background: #ffebee; color: #c62828; }
        .status-completed { background: #e3f2fd; color: #1976d2; }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .quick-actions { grid-template-columns: 1fr; }
            .user-info { flex-direction: column; text-align: center; }
            .activity-header { flex-direction: column; gap: 1rem; align-items: flex-start; }
            .activity-info { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        }
    </style>
</head>
<body>
    
    <header class="system-header">
        <div class="header-content">
            <img src="<?= base_url('img/FEU_Tech_official_seal-removebg-preview.png') ?>" alt="FEU Tech Logo" class="header-logo">           
            <div class="system-title">ITSO EMS</div>
        </div>
        <div class="system-subtitle">Equipment Management System - Student Portal</div>
    </header>

    <nav class="nav-header">
        <div class="nav-links">
            <a href="<?= base_url('student/dashboard') ?>" class="nav-link active">Dashboard</a>
            <a href="<?= base_url('student/equipment') ?>" class="nav-link">Browse Equipment</a>
        </div>
        <div class="user-info">
            
            <?php 
            $profilePicture = session()->get('profile_picture');
            $defaultPicture = 'profile_default-removebg-preview.png';
            $profilePicturePath = $profilePicture ? base_url('uploads/profiles/' . $profilePicture) : base_url('img/' . $defaultPicture);
            ?>
            <img src="<?= $profilePicturePath ?>" alt="Profile Picture" class="profile-picture">

            <span>Welcome, <?= esc(session()->get('first_name') . ' ' . session()->get('last_name')) ?></span>
            <span class="user-badge"><?= ucfirst(session()->get('role')) ?></span>
            <form action="<?= base_url('logout') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <main class="dashboard-container">
        
        <div class="page-header">
            <h1 class="page-title">Student Dashboard</h1>
            <p class="page-subtitle">Welcome back! Access equipment for academic purposes.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-laptop-house"></i>
                </div>
                <div class="stat-value">2</div>
                <div class="stat-label">Active Borrowings</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-value">15</div>
                <div class="stat-label">Total Borrowings</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">3 items</div>
                <div class="stat-label">Max Simultaneous</div>
            </div>
        </div>

        <h2 style="margin-bottom: 1rem; color: var(--forest-green);">Quick Actions</h2>
        <div class="quick-actions">
            <a href="<?= base_url('student/equipment') ?>" class="action-card">
                <div class="action-icon">🔍</div>
                <div class="action-title">Browse Equipment</div>
                <div class="action-desc">Explore available equipment for academic use</div>
            </a>
        </div>
            </div>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Student Portal</h4>
                <p>Academic Equipment Borrowing System</p>
            </div>
            <div class="footer-help">
                <div><strong>Student Support</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; 2023 ITSO Equipment Management System. Student Access.</p>
            </div>
        </div>
    </footer>

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            const cards = document.querySelectorAll('.stat-card, .action-card, .activity-item');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const originalText = stat.textContent;
                stat.textContent = '0';
                
                let counter = 0;
                const target = parseInt(originalText);
                const increment = target / 50;
                
                const updateCounter = () => {
                    if (counter < target) {
                        counter += increment;
                        stat.textContent = Math.ceil(counter);
                        setTimeout(updateCounter, 30);
                    } else {
                        stat.textContent = originalText;
                    }
                };
                
                setTimeout(updateCounter, 500);
            });
        });
    </script>
</body>
</html>