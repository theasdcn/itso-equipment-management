<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
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

        .nav-header { background: var(--forest-green); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 0.8rem; align-items: center; font-size: 12px; }
        .nav-link { color: var(--gold-tint); text-decoration: none; padding: 0.3rem 0.9rem; border-radius: var(--radius-input); transition: all 0.3s ease; font-weight: 500; font-size: 12px; }
        .nav-link.active { background: var(--gold); color: var(--forest-green); font-size: 12px; }
        .nav-link:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); font-size: 12px; }
        .user-info { display: flex; align-items: center; gap: 1rem; color: var(--gold-tint);font-size: 12px; }
        .user-badge { background: var(--gold); color: var(--forest-green); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.6rem; font-weight: 600; }
        .btn-logout { background: var(--gold); color: var(--forest-green); padding: 0.3rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit;font-size: 12px }
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

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: rem;
            margin-bottom: 3rem;
        }
        
        .about-text {
            padding-right: 1rem;
        }
        
        .about-text h2 {
            color: var(--forest-green);
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
        }
        
        .about-text p {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            line-height: 1.7;
        }
        
        .mission-vision {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            border-top: 4px solid var(--gold);
        }
        
        .mission-vision h3 {
            color: var(--forest-green);
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }
        
        .team-section {
            margin-top: 3rem;
        }
        
        .team-title {
            text-align: center;
            color: var(--forest-green);
            margin-bottom: 3rem;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
        }
        
        .leader-section {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .leader-title {
            color: var(--forest-green);
            margin-bottom: 2rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
        }
        
        .leader-card {
            background: var(--white);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow);
            max-width: 400px;
            margin: 0 auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .leader-header {
            background: linear-gradient(135deg, var(--dark-green), var(--forest-green));
            padding: 2.5rem;
            text-align: center;
            color: var(--white);
            position: relative;
        }
        
        .leader-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .leader-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--gold);
            margin: 0 auto 1.5rem;
            background-color: var(--gold-tint);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .leader-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .leader-name {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }
        
        .leader-role {
            color: var(--gold);
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .leader-details {
            padding: 2rem;
        }
        
        .leader-bio {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            line-height: 1.6;
            text-align: center;
        }
        
        .leader-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .members-section {
            margin-top: 2rem;
        }
        
        .members-title {
            text-align: center;
            color: var(--forest-green);
            margin-bottom: 2rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
        }
        
        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .member-card {
            background: var(--white);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .member-header {
            background: linear-gradient(135deg, var(--dark-green), var(--forest-green));
            padding: 2rem;
            text-align: center;
            color: var(--white);
        }
        
        .member-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--gold);
            margin: 0 auto 1rem;
            background-color: var(--gold-tint);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--forest-green);
            overflow: hidden;
        }
        
        .member-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .member-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }
        
        .member-role {
            color: var(--gold);
            font-weight: 500;
        }
        
        .member-details {
            padding: 1.5rem;
        }
        
        .member-bio {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .member-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .skill-tag {
            background: var(--gold-tint);
            color: var(--forest-green);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .stats-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 3rem 0;
        }
        
        .stat-highlight {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-light);
            text-align: center;
            border-top: 3px solid var(--gold);
        }
        
        .stat-highlight i {
            font-size: 2rem;
            color: var(--forest-green);
            margin-bottom: 1rem;
        }
        
        .stat-highlight .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--forest-green);
            margin-bottom: 0.5rem;
        }
        
        .stat-highlight .label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .about-content { grid-template-columns: 1fr; }
            .members-grid { grid-template-columns: 1fr; }
            .user-info { flex-direction: column; text-align: center; }
            .leader-photo { 
                width: 120px; 
                height: 120px; 
            }
            .leader-card,
            .member-card {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <header class="system-header">
        <div class="header-content">
            <img src="<?= base_url('img/FEU_Tech_official_seal-removebg-preview.png') ?>" alt="FEU Tech Logo" class="header-logo">           
            <div class="system-title">ITSO EMS</div>
        </div>
        <div class="system-subtitle">Equipment Management System</div>
    </header>

    <nav class="nav-header">
        <div class="nav-links">
            <?php if(session()->get('role') === 'associate'): ?>
                <a href="<?= base_url('itso/dashboard') ?>" class="nav-link">Dashboard</a>
                <a href="<?= base_url('itso/equipment') ?>" class="nav-link">Browse Equipment</a>
                <a href="<?= base_url('itso/users') ?>" class="nav-link">Users</a>
            <?php elseif(session()->get('role') === 'student'): ?>
                <a href="<?= base_url('itso/borrow_requests') ?>" class="nav-link">Dashboard</a>
                <a href="<?= base_url('itso/returned_equipment') ?>" class="nav-link">Browse Equipment</a>
                <a href="<?= base_url('itso/reports') ?>" class="nav-link">My Reservations</a>
            <?php elseif(session()->get('role') === 'itso_personnel'): ?>
                <a href="<?= base_url('itso/dashboard') ?>" class="nav-link">Dashboard</a>
                <a href="<?= base_url('itso/equipment') ?>" class="nav-link">Equipment</a>
                <a href="<?= base_url('itso/borrow-requests') ?>" class="nav-link">Borrow Requests</a>
                <a href="<?= base_url('itso/users') ?>" class="nav-link">Users</a>
                <a href="<?= base_url('itso/returned_equipment') ?>" class="nav-link">Returned Equipment</a>
                <a href="<?= base_url('itso/reports') ?>" class="nav-link">Reports</a>
            <?php endif; ?>

            <a href="<?= base_url('about_us') ?>" class="nav-link active">About Us</a>
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

        <div class="team-section">
            <h2 class="team-title">Meet Our Team</h2>

            <div class="leader-section">
                <h3 class="leader-title">Project Leader</h3>
                <div class="leader-card">
                    <div class="leader-header">
                        <div class="leader-badge">Team Lead</div>
                        <div class="leader-photo">
                            <img src="<?= base_url('img/thea.jpg') ?>" alt="Althea Sadicon" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-crown\'></i>';">
                        </div>
                        <div class="leader-name">Althea Zuleika Sadicon</div>
                        <div class="leader-role">Project Lead & Full-Stack Developer</div>
                    </div>
                    <div class="leader-details">
                        <p class="leader-bio">Althea oversees the entire development process and ensures the project aligns with user needs. With expertise in system architecture and user experience design, she coordinates between team members and stakeholders to deliver a robust and user-friendly system.</p>
                        <div class="leader-skills">
                            <span class="skill-tag">Frontend</span>
                            <span class="skill-tag">Backend</span>
                            <span class="skill-tag">Database</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="members-section">
                <h3 class="members-title">Development Team</h3>
                <div class="members-grid">
                    
                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-photo">
                                <img src="<?= base_url('img/Guinto_profile.jpg') ?>" alt="Khristian Guinto" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-user\'></i>';">
                            </div>
                            <div class="member-name">Khristian Jose Guinto</div>
                            <div class="member-role">Backend Developer & Database Architect</div>
                        </div>
                        <div class="member-details">
                            <p class="member-bio">Khristian specializes in backend development and database design. He ensures the system runs efficiently and securely, with optimized queries and robust data management practices. His expertise in database architecture guarantees data integrity and system reliability.</p>
                            <div class="member-skills">
                                <span class="skill-tag">Backend</span>
                                <span class="skill-tag">Database</span>
                            </div>
                        </div>
                    </div>

                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-photo">
                                <img src="<?= base_url('img/Rod.png') ?>" alt="Jan Rodmil Santos" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-user\'></i>';">
                            </div>
                            <div class="member-name">Jan Rodmil Santos</div>
                            <div class="member-role">Backend Developer</div>
                        </div>
                        <div class="member-details">
                            <p class="member-bio">Rodmil focuses on creating intuitive and visually appealing interfaces. He ensures that the EMS is accessible and easy to use for all members of the FEU Tech community. Her attention to detail and user-centered design approach creates an exceptional user experience.</p>
                            <div class="member-skills">    
                             <span class="skill-tag">Backend</span>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Equipment Management System</h4>
                <p>Streamlining Equipment Access for FEU Tech</p>
            </div>
            <div class="footer-help">
                <div><strong>Technical Support</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>

        document.addEventListener('DOMContentLoaded', function() {

            const teamCards = document.querySelectorAll('.leader-card, .member-card');
            teamCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = this.classList.contains('leader-card') ? 'translateY(-10px)' : 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            const statNumbers = document.querySelectorAll('.stat-highlight .number');
            statNumbers.forEach(stat => {
                const originalText = stat.textContent;
                const isNumeric = /^\d+\+?$/.test(originalText);
                
                if (isNumeric) {
                    stat.textContent = '0';
                    
                    let counter = 0;
                    const target = parseInt(originalText);
                    const increment = target / 30;
                    
                    const updateCounter = () => {
                        if (counter < target) {
                            counter += increment;
                            stat.textContent = Math.ceil(counter) + (originalText.includes('+') ? '+' : '');
                            setTimeout(updateCounter, 50);
                        } else {
                            stat.textContent = originalText;
                        }
                    };
                    
                    setTimeout(updateCounter, 800);
                }
            });
        });
    </script>
</body>
</html>