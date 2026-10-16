<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ITSO Equipment Management System</title>
    
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
            max-width: 500px;
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

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--forest-green);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: var(--radius-input);
            background: #fafafa;
            border: 1.5px solid #dcdcdc;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: "Inter", sans-serif;
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25);
            outline: none;
            background: #ffffff;
        }

        .form-input.invalid {
            border-color: #a12626;
            background: #ffe3e3;
        }

        .form-help {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.3rem;
        }

        .file-upload-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .file-upload-label {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--gold-tint);
            color: var(--forest-green);
            border-radius: var(--radius-input);
            cursor: pointer;
            border: 1px dashed var(--gold);
            text-align: center;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .file-upload-label:hover {
            background: var(--gold);
        }

        .file-name {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-style: italic;
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
            margin-top: 1rem;
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
                transform: translateY(20px);
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
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-row .form-group {
                margin-bottom: 1.5rem;
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
        }

        @media (max-width: 480px) {
            .auth-body {
                padding: 1.5rem 1rem;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
            
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="system-header">
        <div class="header-content">
        <img src="<?= base_url(relativePath: 'img/FEU_Tech_official_seal-removebg-preview.png') ?>" alt="FEU Tech Logo" class="header-logo">           
        <div class="system-title">ITSO EMS</div>
        </div>
        <div class="system-subtitle">Equipment Management System</div>
    </header>

    <main class="main-content">
        <div class="auth-container">
            <div class="auth-body">
                <h2 class="auth-title">Create Account</h2>
                <p class="auth-description">Register for equipment access</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <div class="alert alert-error"><?= esc($error) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="<?= base_url('register') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input 
                                type="text" 
                                name="first_name" 
                                value="<?= old('first_name') ?>" 
                                class="form-input <?= isset(session()->getFlashdata('errors')['first_name']) ? 'invalid' : '' ?>"
                                placeholder="Enter your first name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input 
                                type="text" 
                                name="last_name" 
                                value="<?= old('last_name') ?>" 
                                class="form-input <?= isset(session()->getFlashdata('errors')['last_name']) ? 'invalid' : '' ?>"
                                placeholder="Enter your last name"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input 
                            type="text" 
                            name="middle_name" 
                            value="<?= old('middle_name') ?>" 
                            class="form-input"
                            placeholder="Enter your middle name (optional)"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username *</label>
                        <input 
                            type="text" 
                            name="username" 
                            value="<?= old('username') ?>" 
                            class="form-input <?= isset(session()->getFlashdata('errors')['username']) ? 'invalid' : '' ?>"
                            placeholder="Choose a username"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?= old('email') ?>" 
                            class="form-input <?= isset(session()->getFlashdata('errors')['email']) ? 'invalid' : '' ?>"
                            placeholder="Enter your email address"
                            required
                        >
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-input <?= isset(session()->getFlashdata('errors')['password']) ? 'invalid' : '' ?>"
                                placeholder="Create a password"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password *</label>
                            <input 
                                type="password" 
                                name="confirm_password" 
                                id="confirm_password"
                                class="form-input <?= isset(session()->getFlashdata('errors')['confirm_password']) ? 'invalid' : '' ?>"
                                placeholder="Confirm password"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Type *</label>
                        <select 
                            name="role" 
                            class="form-select <?= isset(session()->getFlashdata('errors')['role']) ? 'invalid' : '' ?>"
                            required
                        >
                            <option value="">Select Account Type</option>
                            <option value="student" <?= old('role') == 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="associate" <?= old('role') == 'associate' ? 'selected' : '' ?>>Associate/Faculty</option>
                            <option value="itso_personnel" <?= old('role') == 'itso_personnel' ? 'selected' : '' ?>>ITSO Personnel</option>
                        </select>
                    </div>

                    <div class="form-group">
                         <label class="form-label">Department *</label>
                        <select 
                         name="department" 
                      class="form-select <?= isset(session()->getFlashdata('errors')['department']) ? 'invalid' : '' ?>" 
                     required
                    >
                    <option value="">Select Department</option>
                    <option value="cs" <?= old('department') == 'cs' ? 'selected' : '' ?>>Computer Studies (CS)</option>
                    <option value="engr" <?= old('department') == 'engr' ? 'selected' : '' ?>>Engineering (ENGR)</option>
                    <option value="multimedia" <?= old('department') == 'multimedia' ? 'selected' : '' ?>>Multimedia Arts</option>
                </select>
            </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input 
                            type="tel" 
                            name="contact_number" 
                            value="<?= old('contact_number') ?>" 
                            class="form-input"
                            placeholder="Enter your contact number"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Profile Picture</label>
                        <div class="file-upload-container">
                            <input type="file" name="profile_picture" id="profile_picture" hidden accept="image/*">
                            <label for="profile_picture" class="file-upload-label">
                                Choose Profile Picture
                            </label>
                            <div class="file-name" id="file-name">No file chosen</div>
                        </div>
                        <div class="form-help">Optional: Upload a profile picture (JPG, PNG, GIF - max 2MB)</div>
                    </div>

                    <button type="submit" class="btn-primary">Create Account</button>
                </form>

                <div class="auth-links">
                    Already have an account? 
                    <a href="<?= base_url('login') ?>" class="auth-link">Sign in here</a>
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
            const formInputs = document.querySelectorAll('.form-input, .form-select');
            
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

            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (password && confirmPassword) {
                function validatePassword() {
                    if (password.value !== confirmPassword.value && confirmPassword.value !== '') {
                        confirmPassword.classList.add('invalid');
                    } else {
                        confirmPassword.classList.remove('invalid');
                    }
                }
                
                password.addEventListener('input', validatePassword);
                confirmPassword.addEventListener('input', validatePassword);
            }

            const fileInput = document.getElementById('profile_picture');
            const fileName = document.getElementById('file-name');
            
            if (fileInput && fileName) {
                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        fileName.textContent = e.target.files[0].name;
                    } else {
                        fileName.textContent = 'No file chosen';
                    }
                });
            }

            const primaryButton = document.querySelector('.btn-primary');
            if (primaryButton) {
                primaryButton.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                primaryButton.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }
        });
    </script>
</body>
</html>