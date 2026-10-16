<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    
    <style>
        .reservations-list { display: flex; flex-direction: column; gap: 1rem; }
        .reservation-card { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); border-left: 4px solid var(--gold); }
        .reservation-header { display: flex; justify-content: between; align-items: center; margin-bottom: 1rem; }
        .reservation-title { font-weight: 600; color: var(--forest-green); font-size: 1.1rem; }
        .reservation-status { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-confirmed { background: #e8f5e8; color: #2e7d32; }
        .status-pending { background: #fff3e0; color: #f57c00; }
        .status-cancelled { background: #ffebee; color: #c62828; }
        .status-approved { background: #e3f2fd; color: #1976d2; }
        .status-rejected { background: #ffebee; color: #c62828; }
        .status-borrowed { background: #e8f5e8; color: #2e7d32; }
        .status-returned { background: #f3e5f5; color: #7b1fa2; }
        .reservation-details { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; }
        .reservation-actions { display: flex; gap: 0.5rem; }
        
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

        .btn { padding: 0.6rem 1.2rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem; }
        .btn-primary { background: var(--gold); color: var(--forest-green); }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-secondary { background: var(--forest-green); color: var(--white); }
        .btn-secondary:hover { background: var(--dark-green); transform: translateY(-2px); }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; transform: translateY(-2px); }

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

        .empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
        .empty-state-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .reservation-actions { flex-direction: column; }
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
            <h1 class="page-title">My Reservations</h1>
            <p class="page-subtitle">Manage your equipment reservations and borrowing history</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="background: #e8f5e8; color: #2e7d32; padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1.5rem; border-left: 4px solid #2e7d32;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error" style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1.5rem; border-left: 4px solid #c62828;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="<?= base_url('associate/reservations/new') ?>" class="btn btn-primary">
                📅 Make New Reservation
            </a>
            <a href="<?= base_url('associate/equipment') ?>" class="btn btn-secondary">
                🔍 Browse Equipment
            </a>
        </div>

        <div class="reservations-list">
            <?php if (!empty($borrow_requests)): ?>
                <?php foreach ($borrow_requests as $request): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <div class="reservation-title"><?= esc($request['equipment_name']) ?> (<?= esc($request['equipment_code']) ?>)</div>
                            <div class="reservation-status status-<?= $request['status'] ?>">
                                <?= ucfirst($request['status']) ?>
                            </div>
                        </div>
                        <div class="reservation-details">
                            <strong>Purpose:</strong> <?= esc($request['purpose']) ?><br>
                            <strong>Requested Period:</strong> <?= date('M j, Y g:i A', strtotime($request['borrow_date'])) ?> - <?= date('M j, Y g:i A', strtotime($request['expected_return_date'])) ?><br>
                            <strong>Academic Subject:</strong> <?= esc($request['academic_subject']) ?><br>
                            <strong>Professor:</strong> <?= esc($request['professor_name']) ?><br>
                            <strong>Room:</strong> <?= esc($request['room_number']) ?><br>
                            <strong>Request Date:</strong> <?= date('M j, Y g:i A', strtotime($request['created_at'])) ?>
                        </div>
                        <div class="reservation-actions">
                            <?php if ($request['status'] === 'pending'): ?>
                                <form action="<?= base_url('associate/reservations/cancel/' . $request['borrow_id']) ?>" method="post" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel Request</button>
                                </form>
                            <?php elseif ($request['status'] === 'approved'): ?>
                                <button class="btn btn-primary" disabled>Ready for Pickup</button>
                            <?php elseif ($request['status'] === 'borrowed'): ?>
                                <button class="btn btn-secondary" disabled>Currently Borrowed</button>
                            <?php elseif ($request['status'] === 'returned'): ?>
                                <button class="btn btn-secondary" disabled>Completed</button>
                            <?php elseif ($request['status'] === 'rejected'): ?>
                                <button class="btn btn-danger" disabled>Rejected</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <h3>No Reservations Yet</h3>
                    <p>You haven't made any equipment reservations yet.</p>
                    <a href="<?= base_url('associate/reservations/new') ?>" class="btn btn-primary" style="margin-top: 1rem;">
                        Make Your First Reservation
                    </a>
                </div>
            <?php endif; ?>
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
        function cancelReservation(reservationId) {
            if (confirm('Are you sure you want to cancel this reservation?')) {
                const buttons = document.querySelectorAll('.btn');
                buttons.forEach(btn => btn.disabled = true);

                setTimeout(() => {
                    alert('Reservation cancelled successfully.');
                    buttons.forEach(btn => btn.disabled = false);
                    
                    console.log(`Cancelling reservation ID: ${reservationId}`);
                }, 1000);
            }
        }
    </script>
</body>
</html>