<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-light);
            font-family: "Inter", sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            min-height: 100vh;
        }

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

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .admin-welcome {
            background: linear-gradient(135deg, var(--dark-green), var(--forest-green));
            color: var(--white);
            padding: 2.5rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border-left: 6px solid var(--gold);
            position: relative;
            overflow: hidden;
        }

        .admin-welcome::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        }

        .welcome-title {
            font-family: "Playfair Display", serif;
            color: var(--gold);
            margin-bottom: 0.5rem;
            font-size: 2.2rem;
        }

        .welcome-subtitle {
            color: var(--gold-tint);
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .admin-badge {
            display: inline-block;
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gold);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-light);
            border-color: var(--gold-tint);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--forest-green);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stat-trend {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .trend-up {
            background: #e9f7ef;
            color: #2b7a4b;
        }

        .trend-down {
            background: #fbeaea;
            color: #a12626;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            border: 1px solid var(--gold-tint);
            transition: all 0.3s ease;
            position: relative;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-light);
            border-color: var(--gold);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--gold);
        }

        .feature-title {
            font-family: "Playfair Display", serif;
            color: var(--forest-green);
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .feature-description {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gold-tint);
            color: var(--text-dark);
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: '✓';
            color: var(--gold);
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .btn-feature {
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-input);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-feature:hover {
            background: #c19a2f;
            transform: translateY(-2px);
        }

        .nav-header {
            background: var(--forest-green);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: var(--gold-tint);
            text-decoration: none;
            padding: 0.7rem 1.2rem;
            border-radius: var(--radius-input);
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.86rem;
        }

        .nav-link.active {
            background: var(--gold);
            color: var(--forest-green);
        }

        .nav-link:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold);
        }

        .btn-logout {
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-input);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-logout:hover {
            background: #c19a2f;
            transform: translateY(-2px);
        }

        .system-footer {
            background: var(--forest-green);
            color: var(--gold-tint);
            padding: 2rem 1rem;
            border-top: 2px solid var(--gold);
            margin-top: 3rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-info h4 {
            color: var(--gold);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
        }

        .footer-help {
            text-align: right;
        }

        .footer-help strong {
            color: var(--gold);
        }

        .footer-copyright {
            border-top: 1px solid rgba(212, 175, 55, 0.3);
            padding-top: 1rem;
            margin-top: 1rem;
            text-align: center;
            font-size: 0.85rem;
            opacity: 0.8;
            width: 100%;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .quick-action-btn {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-input);
            text-decoration: none;
            color: var(--forest-green);
            text-align: center;
            border: 2px solid var(--gold-tint);
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .quick-action-btn:hover {
            border-color: var(--gold);
            transform: translateY(-3px);
            box-shadow: var(--shadow-light);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .nav-header {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .nav-link {
                text-align: center;
                width: 100%;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-help {
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
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
        <div class="system-subtitle">Equipment Management System - Administration</div>
    </header>

    <nav class="nav-header">
        <div class="nav-links">
        <a href="<?= base_url('itso-dashboard') ?>" class="nav-link active">Dashboard</a>
        <a href="<?= base_url('itso/equipment') ?>" class="nav-link">Equipment</a>
        <a href="<?= base_url('itso/users') ?>" class="nav-link">Users</a>
        <a href="<?= base_url('itso/borrow-requests') ?>" class="nav-link">Borrow Requests</a>
        <a href="<?= base_url('itso/returned_equipment') ?>" class="nav-link">Returned Equipment</a>
        <a href="<?= base_url('itso/reports') ?>" class="nav-link">Reports</a>
        <a href="<?= base_url('itso/about_us') ?>" class="nav-link">About Us</a>

        </div>
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <main class="dashboard-container">
        
        <div class="admin-welcome">
            <div class="admin-badge">ITSO PERSONNEL</div>
            <h1 class="welcome-title">System Administration Dashboard</h1>
            <p class="welcome-subtitle">Welcome back, <?= esc($user['first_name']) ?>! Full system control at your fingertips.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_equipment'] ?></div>
                <div class="stat-label">Total Equipment</div>
                <div class="stat-trend trend-up">+5 this week</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['active_borrows'] ?></div>
                <div class="stat-label">Active Borrows</div>
                <div class="stat-trend trend-down">-2 from yesterday</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-label">Registered Users</div>
                <div class="stat-trend trend-up">+3 new users</div>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⚙️</div>
                <h3 class="feature-title">Equipment Management</h3>
                <p class="feature-description">Complete control over all equipment and categories in the system.</p>
                <ul class="feature-list">
                    <li>Add/Edit equipment items</li>
                    <li>Manage categories and specifications</li>
                    <li>Track equipment status and maintenance</li>
                    <li>Update inventory quantities</li>
                </ul>
                <a href="<?= base_url('itso/equipment') ?>" class="btn-feature">Manage Equipment</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3 class="feature-title">User Management</h3>
                <p class="feature-description">Manage all user accounts, permissions, and access levels.</p>
                <ul class="feature-list">
                    <li>Create and edit user accounts</li>
                    <li>Manage user permissions and roles</li>
                    <li>Reset passwords and account status</li>
                    <li>View user activity logs</li>
                </ul>
                <a href="<?= base_url('itso/users') ?>" class="btn-feature">Manage Users</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Reports & Analytics</h3>
                <p class="feature-description">Generate comprehensive reports and system analytics.</p>
                <ul class="feature-list">
                    <li>Equipment usage reports</li>
                    <li>User activity analytics</li>
                    <li>Borrowing trends and patterns</li>
                    <li>System performance metrics</li>
                </ul>
                <a href="<?= base_url('itso/reports') ?>" class="btn-feature">View Reports</a>
            </div>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Equipment Management</h4>
                <p>Full System Administration & Control</p>
            </div>
            
            <div class="footer-help">
                <div><strong>Administrator Support</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. Administrator Access.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cards = document.querySelectorAll('.stat-card, .feature-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            const quickActions = document.querySelectorAll('.quick-action-btn');
            quickActions.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            const logoutBtn = document.querySelector('.btn-logout');
            if (logoutBtn) {
                logoutBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                logoutBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }
        });
    </script>
</body>
</html>