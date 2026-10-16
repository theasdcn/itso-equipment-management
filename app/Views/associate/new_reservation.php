<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    
    <style>
        
        .reservation-form { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { font-size: 0.95rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; display: block; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 0.9rem 1rem; border-radius: var(--radius-input); background: #fafafa; border: 1.5px solid #dcdcdc; font-size: 1rem; transition: all 0.3s ease; font-family: "Inter", sans-serif; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25); outline: none; background: #ffffff; }
        .form-textarea { min-height: 100px; resize: vertical; }
        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; }
        .form-help { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem; }
        .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }

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

        .btn { padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-block; }
        .btn-primary { background: var(--gold); color: var(--forest-green); }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; }

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

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .form-row { flex-direction: column; }
            .form-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    
    <header class="system-header">
        <div class="header-content">
            <img src="<?= base_url('img/FEU_Tech_official_seal-removebg-preview.png') ?>" alt="FEU Tech Logo" class="header-logo">           
            <div class="system-title">ITSO EMS</div>
        </div>
        <div class="system-subtitle">Equipment Management System - Associate Portal</div>
    </header>

    <nav class="nav-header">
        <div class="nav-links">
            <a href="<?= base_url('associate/dashboard') ?>" class="nav-link">Dashboard</a>
            <a href="<?= base_url('associate/equipment') ?>" class="nav-link">Browse Equipment</a>
            <a href="<?= base_url('associate/reservations') ?>" class="nav-link active">My Reservations</a>
            <a href="<?= base_url('associate/about_us') ?>" class="nav-link">About Us</a>

        </div>
        <div class="user-info">
            
            <?php 
            $profilePicture = session()->get('profile_picture');
            $defaultPicture = 'profile_default-removebg-preview.png';
            $profilePicturePath = $profilePicture ? base_url('uploads/profiles/' . $profilePicture) : base_url('img/' . $defaultPicture);
            ?>
            <img src="<?= $profilePicturePath ?>" alt="Profile Picture" class="profile-picture">

            <span>Welcome, <?= esc(session()->get('first_name') . ' ' . session()->get('last_name')) ?></span>
            <span class="user-badge">Associate</span>
            <form action="<?= base_url('logout') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <main class="dashboard-container">
        
        <div class="page-header">
            <h1 class="page-title">New Reservation</h1>
            <p class="page-subtitle">Reserve equipment with extended associate privileges</p>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error" style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1.5rem; border-left: 4px solid #c62828;">
                <?php 
                $errors = session()->getFlashdata('errors');
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        echo '<p>' . esc($error) . '</p>';
                    }
                } else {
                    echo '<p>' . esc($errors) . '</p>';
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form action="<?= base_url('associate/reservations/submit') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Select Equipment *</label>
                    <select name="equipment_id" class="form-select" required>
                        <option value="">Choose equipment...</option>
                        <?php if (!empty($equipment)): ?>
                            <?php foreach ($equipment as $item): ?>
                                <option value="<?= $item['equipment_id'] ?>" <?= set_select('equipment_id', $item['equipment_id']) ?>>
                                    <?= esc($item['name']) ?> (<?= $item['available_quantity'] ?> available)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Purpose *</label>
                    <textarea name="purpose" class="form-textarea" placeholder="Please describe the purpose of borrowing this equipment in detail (minimum 10 characters)..." required><?= set_value('purpose') ?></textarea>
                    <div class="form-help">Minimum 10 characters required</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Academic Subject *</label>
                    <input type="text" name="academic_subject" class="form-input" value="<?= set_value('academic_subject') ?>" placeholder="e.g., Computer Science, Engineering, etc." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Professor Name *</label>
                        <input type="text" name="professor_name" class="form-input" value="<?= set_value('professor_name') ?>" placeholder="Professor's full name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Room Number *</label>
                        <input type="text" name="room_number" class="form-input" value="<?= set_value('room_number') ?>" placeholder="Room number where equipment will be used" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Borrow Date *</label>
                        <input type="date" name="borrow_date" class="form-input" value="<?= set_value('borrow_date') ?>" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expected Return Date *</label>
                        <input type="date" name="expected_return_date" class="form-input" value="<?= set_value('expected_return_date') ?>" required min="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Priority Level</label>
                    <div style="background: var(--gold-tint); padding: 1rem; border-radius: var(--radius-input);">
                        <strong>Associate Priority:</strong> Your reservation will receive priority approval processing.
                        Expected approval time: Within 24 hours.
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Submit Reservation Request
                    </button>
                    <a href="<?= base_url('associate/reservations') ?>" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Associate Portal</h4>
                <p>Extended Privileges & Equipment Access</p>
            </div>
            <div class="footer-help">
                <div><strong>Associate Support</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. Associate Access.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowDateInput = document.querySelector('input[name="borrow_date"]');
            const returnDateInput = document.querySelector('input[name="expected_return_date"]');

            borrowDateInput.addEventListener('change', function() {
                returnDateInput.min = this.value;
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const borrowDate = new Date(borrowDateInput.value);
                const returnDate = new Date(returnDateInput.value);
                
                if (returnDate <= borrowDate) {
                    e.preventDefault();
                    alert('Return date must be after borrow date.');
                    return false;
                }
                
                const diffTime = Math.abs(returnDate - borrowDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 30) {
                    if (!confirm('You are requesting equipment for more than 30 days. Are you sure you want to proceed?')) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '⏳ Submitting...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>