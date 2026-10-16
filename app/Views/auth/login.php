<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ITSO Equipment Management System</title>
    
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
            display: flex;
            flex-direction: column;
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

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .auth-container {
            background: var(--white);
            width: 100%;
            max-width: 440px;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 2px solid var(--gold-tint);
            animation: fadeIn 0.6s ease;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .auth-title {
            text-align: center;
            font-size: 1.7rem;
            font-family: "Playfair Display", serif;
            color: var(--forest-green);
            margin-bottom: 0.5rem;
        }

        .auth-description {
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fbeaea;
            color: #a12626;
            border-left: 4px solid #a12626;
        }

        .alert-success {
            background: #e9f7ef;
            color: #2b7a4b;
            border-left: 4px solid #2b7a4b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--forest-green);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: var(--radius-input);
            background: #fafafa;
            border: 1.5px solid #dcdcdc;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: "Inter", sans-serif;
        }

        .form-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25);
            outline: none;
            background: #ffffff;
        }

        .form-input.invalid {
            border-color: #a12626;
            background: #ffe3e3;
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: var(--radius-input);
            background: var(--gold);
            color: var(--forest-green);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Inter", sans-serif;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            background: #c19a2f;
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-link {
            text-decoration: none;
            font-size: 0.95rem;
            color: var(--dark-green);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: var(--gold);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.8rem 0;
            color: var(--text-muted);
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.85rem;
        }

        .role-badges {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .role-badge {
            background: var(--gold-tint);
            color: var(--dark-green);
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid var(--gold);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-badge:hover {
            background: var(--gold);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .role-badge.active {
            background: var(--gold);
            color: var(--forest-green);
            border-color: var(--forest-green);
            font-weight: 600;
        }

        .role-info {
            text-align: center;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--gold-tint);
            border-radius: var(--radius-input);
            border-left: 4px solid var(--gold);
            display: none;
        }

        .role-info.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .role-info h4 {
            color: var(--forest-green);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .role-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .system-footer {
            background: var(--forest-green);
            color: var(--gold-tint);
            padding: 2rem 1rem;
            border-top: 2px solid var(--gold);
        }

        .footer-content {
            max-width: 1200px;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .auth-body {
                padding: 2rem 1.5rem;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-help {
                text-align: center;
            }
            
            .system-title {
                font-size: 1.8rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .header-logo {
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .auth-body {
                padding: 1.5rem 1rem;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
            
            .role-badges {
                gap: 0.5rem;
            }
            
            .role-badge {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .header-logo {
                height: 45px;
            }
        }
    </style>
</head>
<body>
    <header class="system-header">
        <div class="header-content">
        <img src="<?= base_url('img/FEU_Tech_official_seal-removebg-preview.png') ?>" alt="FEU Tech Logo" class="header-logo">        <div class="system-title">ITSO EMS</div>
        </div>
        <div class="system-subtitle">Equipment Management System</div>
    </header>

    <main class="main-content">
        <div class="auth-container">
            <div class="auth-body">
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-description">Sign in to your account to continue</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <div class="alert alert-error"><?= esc($error) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label">Username or Email</label>
                        <input 
                            type="text" 
                            name="username"
                            value="<?= old('username') ?>"
                            class="form-input <?= isset(session()->getFlashdata('errors')['username']) ? 'invalid' : '' ?>"
                            placeholder="Enter your username or email"
                            required
                            id="usernameInput"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input 
                            type="password"
                            name="password"
                            class="form-input <?= isset(session()->getFlashdata('errors')['password']) ? 'invalid' : '' ?>"
                            placeholder="Enter your password"
                            required
                            id="passwordInput"
                        >
                    </div>

                    <button type="submit" class="btn-primary">Sign In</button>
                </form>

                <div class="role-info" id="roleInfo">
                    <h4 id="roleTitle">Select a role to learn more</h4>
                    <p id="roleDescription">Click on any role badge above to see detailed information about access privileges and features available for that role.</p>
                    <p id="roleFeatures" class="role-features"></p>
                </div>

                <div class="auth-links">
                    <a href="<?= base_url('forgot-password') ?>" class="auth-link">Forgot your password?</a>
                </div>

                <div class="divider">
                    <span>New to ITSO?</span>
                </div>

                <div class="auth-links">
                    <a href="<?= base_url('register') ?>" class="auth-link">Create an account</a>
                </div>

                <div class="role-badges">
                    <div class="role-badge" data-role="itso_personnel" data-username="itso_admin" data-password="admin123">
                        ITSO Personnel
                    </div>
                    <div class="role-badge" data-role="associate" data-username="faculty_user" data-password="faculty123">
                        Associates
                    </div>
                    <div class="role-badge" data-role="student" data-username="student_user" data-password="student123">
                        Students
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Equipment Management</h4>
                <p>Streamlining equipment borrowing and reservations</p>
            </div>
            
            <div class="footer-help">
                <div><strong>Need Help?</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleBadges = document.querySelectorAll('.role-badge');
            const roleInfo = document.getElementById('roleInfo');
            const roleTitle = document.getElementById('roleTitle');
            const roleDescription = document.getElementById('roleDescription');
            const roleFeatures = document.getElementById('roleFeatures');
            const usernameInput = document.getElementById('usernameInput');
            const passwordInput = document.getElementById('passwordInput');
            const loginForm = document.getElementById('loginForm');

            const roleData = {
                itso_personnel: {
                    title: 'ITSO Personnel',
                    description: 'Full system administrators with complete access to all features and management capabilities.',
                    features: [
                        'Manage all equipment and categories',
                        'Manage user accounts and permissions',
                        'Generate comprehensive reports',
                        'System configuration and maintenance',
                        'Access to all dashboards and modules'
                    ],
                    demoCredentials: {
                        username: 'itso_admin',
                        password: 'admin123'
                    }
                },
                associate: {
                    title: 'Associates & Faculty',
                    description: 'Faculty members and associates with extended borrowing privileges and reservation capabilities.',
                    features: [
                        'Borrow equipment with extended limits',
                        'Make equipment reservations',
                        'Longer borrowing periods',
                        'Priority reservation approval'
                    ],
                    demoCredentials: {
                        username: 'faculty_user',
                        password: 'faculty123'
                    }
                },
                student: {
                    title: 'Students',
                    description: 'Student users with basic equipment borrowing privileges for academic purposes.',
                    features: [
                        'Borrow available equipment',
                        'Standard borrowing periods',
                        'Academic use only',
                        'Limited simultaneous borrows'
                    ],
                    demoCredentials: {
                        username: 'student_user',
                        password: 'student123'
                    }
                }
            };

            roleBadges.forEach(badge => {
                badge.addEventListener('click', function() {
                    const role = this.getAttribute('data-role');

                    roleBadges.forEach(b => b.classList.remove('active'));

                    this.classList.add('active');
                    
                    showRoleInfo(role);
                    
                    fillDemoCredentials(role);
                });
            });

            function showRoleInfo(role) {
                const data = roleData[role];
                
                if (data) {
                    roleTitle.textContent = data.title;
                    roleDescription.textContent = data.description;

                    const featuresList = data.features.map(feature => 
                        `• ${feature}`
                    ).join('<br>');
                    
                    roleFeatures.innerHTML = `<strong>Features:</strong><br>${featuresList}`;
                    
                    roleInfo.classList.add('show');
                }
            }

            function fillDemoCredentials(role) {
                const data = roleData[role];
                
                if (data && data.demoCredentials) {
                    usernameInput.value = data.demoCredentials.username;
                    passwordInput.value = data.demoCredentials.password;
                    
                    const tempMessage = document.createElement('div');
                    tempMessage.className = 'alert alert-success';
                    tempMessage.style.marginTop = '1rem';
                    tempMessage.style.fontSize = '0.8rem';
                    tempMessage.innerHTML = `Demo credentials filled for ${data.title}. Click "Sign In" to continue.`;

                    const existingTemp = document.querySelector('.temp-demo-message');
                    if (existingTemp) {
                        existingTemp.remove();
                    }
                    
                    tempMessage.classList.add('temp-demo-message');
                    loginForm.appendChild(tempMessage);
                    
                    setTimeout(() => {
                        if (tempMessage.parentNode) {
                            tempMessage.remove();
                        }
                    }, 5000);
                }
            }

            const formInputs = document.querySelectorAll('.form-input');
            
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#D4AF37';
                    this.style.boxShadow = '0 0 0 3px rgba(212, 175, 55, 0.25)';
                    this.style.background = '#ffffff';
                });
                
                input.addEventListener('blur', function() {
                    this.style.borderColor = '#dcdcdc';
                    this.style.boxShadow = 'none';
                    this.style.background = '#fafafa';
                });
            });

            const primaryButton = document.querySelector('.btn-primary');
            if (primaryButton) {
                primaryButton.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                primaryButton.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }

            if (usernameInput && !usernameInput.value) {
                usernameInput.focus();
            }

            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key === '1') {
                    e.preventDefault();
                    document.querySelector('[data-role="itso_personnel"]').click();
                }
                else if (e.altKey && e.key === '2') {
                    e.preventDefault();
                    document.querySelector('[data-role="associate"]').click();
                }
                else if (e.altKey && e.key === '3') {
                    e.preventDefault();
                    document.querySelector('[data-role="student"]').click();
                }
            });

            console.log('Keyboard shortcuts: Alt+1 (ITSO), Alt+2 (Associates), Alt+3 (Students)');
        });
    </script>
</body>
</html>