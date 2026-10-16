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

        .dashboard-container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--dark-green), var(--forest-green)); color: var(--white); padding: 2rem; border-radius: var(--radius-card); margin-bottom: 2rem; border-left: 6px solid var(--gold); }
        .page-title { font-family: "Playfair Display", serif; color: var(--gold); margin-bottom: 0.5rem; font-size: 2rem; }
        .page-subtitle { color: var(--gold-tint); font-size: 1.1rem; }

        .edit-form { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { font-size: 0.95rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; display: block; }
        .form-input, .form-select { width: 100%; padding: 0.9rem 1rem; border-radius: var(--radius-input); background: #fafafa; border: 1.5px solid #dcdcdc; font-size: 1rem; transition: all 0.3s ease; font-family: "Inter", sans-serif; }
        .form-input:focus, .form-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25); outline: none; background: #ffffff; }
        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; }

        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; }
        .checkbox { width: auto; }

        .btn-primary, .btn-secondary { padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-block; }
        .btn-primary { background: var(--gold); color: var(--forest-green); }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; }

        .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }

        .nav-header { background: var(--forest-green); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-link { color: var(--gold-tint); text-decoration: none; padding: 0.7rem 1.2rem; border-radius: var(--radius-input); transition: all 0.3s ease; font-weight: 500; }
        .nav-link.active { background: var(--gold); color: var(--forest-green); }
        .nav-link:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); }
        .btn-logout { background: var(--gold); color: var(--forest-green); padding: 0.7rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; }
        .btn-logout:hover { background: #c19a2f; transform: translateY(-2px); }

        .system-footer { background: var(--forest-green); color: var(--gold-tint); padding: 2rem 1rem; border-top: 2px solid var(--gold); margin-top: 3rem; }
        .footer-content { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
        .footer-info h4 { color: var(--gold); margin-bottom: 0.5rem; font-family: 'Playfair Display', serif; font-size: 1.1rem; }
        .footer-help { text-align: right; }
        .footer-help strong { color: var(--gold); }
        .footer-copyright { border-top: 1px solid rgba(212, 175, 55, 0.3); padding-top: 1rem; margin-top: 1rem; text-align: center; font-size: 0.85rem; opacity: 0.8; width: 100%; }

        .alert { padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-error { background: #fbeaea; color: #a12626; border-left: 4px solid #a12626; }
        .alert-success { background: #eafbea; color: #1a7a1a; border-left: 4px solid #1a7a1a; }

        .password-note { 
            font-size: 0.8rem; 
            color: var(--text-muted); 
            margin-top: 0.3rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .form-row { flex-direction: column; }
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
            <a href="<?= base_url('itso-dashboard') ?>" class="nav-link">Dashboard</a>
            <a href="<?= base_url('itso/equipment') ?>" class="nav-link">Equipment</a>
            <a href="<?= base_url('itso/users') ?>" class="nav-link active">Users</a>
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
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Update user information and permissions</p>
        </div>

        <div class="edit-form">
            <form action="<?= base_url('itso/users/update/' . $editUser['id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-input" value="<?= old('first_name', $editUser['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-input" value="<?= old('last_name', $editUser['last_name']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-input" value="<?= old('middle_name', $editUser['middle_name']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-input" value="<?= old('email', $editUser['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">New Password (Leave blank to keep current)</label>
                    <input type="password" name="new_password" class="form-input" placeholder="Enter new password">
                    <div class="password-note">Minimum 8 characters. Leave empty if you don't want to change the password.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="student" <?= old('role', $editUser['role']) == 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="associate" <?= old('role', $editUser['role']) == 'associate' ? 'selected' : '' ?>>Associate</option>
                        <option value="itso_personnel" <?= old('role', $editUser['role']) == 'itso_personnel' ? 'selected' : '' ?>>ITSO Personnel</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-input" value="<?= old('department', $editUser['department']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" name="contact_number" class="form-input" value="<?= old('contact_number', $editUser['contact_number']) ?>">
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_active" id="is_active" class="checkbox" value="1" <?= old('is_active', $editUser['is_active']) ? 'checked' : '' ?>>
                        <label class="form-label" for="is_active">Active Account</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Update User</button>
                    <a href="<?= base_url('itso/users') ?>" class="btn-secondary">Cancel</a>
                </div>
            </form>
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
</body>
</html>