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
            --beige-bg: #F9F5EB;
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

        .dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--dark-green), var(--forest-green)); color: var(--white); padding: 2rem; border-radius: var(--radius-card); margin-bottom: 2rem; border-left: 6px solid var(--gold); }
        .page-title { font-family: "Playfair Display", serif; color: var(--gold); margin-bottom: 0.5rem; font-size: 2rem; }
        .page-subtitle { color: var(--gold-tint); font-size: 1.1rem; }

        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 1rem; 
            margin-bottom: 2rem; 
        }
        .stat-card { 
            background: var(--white); 
            padding: 1.5rem; 
            border-radius: var(--radius-card); 
            box-shadow: var(--shadow); 
            text-align: center; 
            border-top: 4px solid var(--gold);
            width: 300px;
            margin-left: 50px;
            position: relative;
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
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-number { 
            font-size: 2rem; 
            font-weight: 700; 
            color: var(--forest-green); 
            margin-bottom: 0.5rem; 
        }
        .stat-label { 
            font-size: 0.9rem; 
            color: var(--text-muted); 
            font-weight: 500;
        }

        .filter-section { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .filter-title { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 1rem; font-size: 1.2rem; }
        .filter-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
        .filter-btn { padding: 0.7rem 1.5rem; border: 2px solid var(--gold-tint); background: var(--white); color: var(--forest-green); border-radius: var(--radius-input); text-decoration: none; font-weight: 500; transition: all 0.3s ease; cursor: pointer; }
        .filter-btn.active, .filter-btn:hover { background: var(--gold); color: var(--forest-green); border-color: var(--gold); }

        .users-table-container { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--forest-green); color: var(--gold-tint); padding: 1rem; text-align: left; font-weight: 600; }
        .table td { padding: 1rem; border-bottom: 1px solid var(--gold-tint); }
        .table tr:hover { background: var(--gold-tint); }

        .role-badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .role-itso { background: #e3f2fd; color: #1976d2; }
        .role-associate { background: #e8f5e8; color: #2e7d32; }
        .role-student { background: #fff3e0; color: #f57c00; }

        .status-badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-active { background: #e8f5e8; color: #2e7d32; }
        .status-inactive { background: #ffebee; color: #c62828; }

        .action-buttons { display: flex; gap: 0.5rem; }
        .btn-edit, .btn-toggle { padding: 0.5rem 1rem; border-radius: var(--radius-input); text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-edit { background: var(--gold); color: var(--forest-green); }
        .btn-edit:hover { background: #c19a2f; }
        .btn-toggle { background: #6c757d; color: white; }
        .btn-toggle:hover { background: #545b62; }

        .pagination { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 1.5rem;
            border-radius: var(--radius-card);
            background: var(--beige-bg);
            box-shadow: var(--shadow-light);
            border: 1px solid var(--gold-tint);
        }
        .pagination-info { 
            font-size: 0.9rem; 
            color: var(--text-dark);
            font-weight: 500;
        }
        .pagination-controls { 
            display: flex; 
            gap: 0.5rem; 
            align-items: center;
        }
        .pagination-btn { 
            padding: 0.5rem 1rem; 
            border: 1px solid var(--gold); 
            background: var(--white); 
            color: var(--forest-green); 
            border-radius: var(--radius-input); 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.3s ease; 
            cursor: pointer;
            font-size: 0.9rem;
        }
        .pagination-btn:hover { 
            background: var(--gold); 
            border-color: var(--gold);
            transform: translateY(-1px);
        }
        .pagination-btn.active { 
            background: var(--gold); 
            color: var(--forest-green); 
            border-color: var(--gold);
            font-weight: 600;
        }
        .pagination-btn:disabled { 
            opacity: 0.5; 
            cursor: not-allowed;
            background: var(--gold-tint);
            border-color: var(--gold-tint);
        }
        .pagination-btn:disabled:hover { 
            background: var(--gold-tint); 
            border-color: var(--gold-tint);
            transform: none;
        }

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
        .alert-success { background: #e9f7ef; color: #2b7a4b; border-left: 4px solid #2b7a4b; }
        .alert-error { background: #fbeaea; color: #a12626; border-left: 4px solid #a12626; }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: var(--white);
            padding: 2rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        
        .modal-icon {
            font-size: 3rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }
        
        .modal-title {
            font-family: "Playfair Display", serif;
            font-size: 1.5rem;
            color: var(--forest-green);
            margin-bottom: 1rem;
        }
        
        .modal-message {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .modal-btn {
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-input);
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .modal-btn-confirm {
            background-color: #d9534f;
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background-color: #c9302c;
        }
        
        .modal-btn-cancel {
            background-color: var(--gold-tint);
            color: var(--forest-green);
        }
        
        .modal-btn-cancel:hover {
            background-color: var(--gold);
        }
        
        #modal-toggle {
            display: none;
        }
        
        #modal-toggle:checked ~ .modal {
            display: flex;
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .filter-buttons { flex-direction: column; }
            .table { display: block; overflow-x: auto; }
            .pagination { flex-direction: column; gap: 1rem; text-align: center; }
            .modal-buttons { flex-direction: column; }
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
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage all user accounts, permissions, and access levels</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?= $userCounts['total'] ?? 0 ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-number"><?= $userCounts['itso'] ?? 0 ?></div>
                <div class="stat-label">ITSO Personnel</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number"><?= $userCounts['associates'] ?? 0 ?></div>
                <div class="stat-label">Associates</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number"><?= $userCounts['students'] ?? 0 ?></div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number"><?= $userCounts['active'] ?? 0 ?></div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-number"><?= $userCounts['inactive'] ?? 0 ?></div>
                <div class="stat-label">Inactive Users</div>
            </div>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Filter by Role</h3>
            <div class="filter-buttons">
                <a href="<?= base_url('itso/users') ?>" class="filter-btn <?= empty($roleFilter) ? 'active' : '' ?>">All Users</a>
                <a href="<?= base_url('itso/users?role=itso_personnel') ?>" class="filter-btn <?= ($roleFilter ?? '') === 'itso_personnel' ? 'active' : '' ?>">ITSO Personnel</a>
                <a href="<?= base_url('itso/users?role=associate') ?>" class="filter-btn <?= ($roleFilter ?? '') === 'associate' ? 'active' : '' ?>">Associates</a>
                <a href="<?= base_url('itso/users?role=student') ?>" class="filter-btn <?= ($roleFilter ?? '') === 'student' ? 'active' : '' ?>">Students</a>
            </div>
        </div>

        <div class="users-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <span class="role-badge role-<?= $user['role'] ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td><?= esc($user['department'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                        <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="<?= base_url('itso/users/edit/' . $user['id']) ?>" class="btn-edit">Edit</a>
                                    <?php if ($user['is_active']): ?>
                                        
                                        <label for="modal-toggle" class="btn-toggle" 
                                               onclick="document.getElementById('deactivate-user-id').value = '<?= $user['id'] ?>';
                                                        document.getElementById('user-name').textContent = '<?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?>';
                                                        document.querySelector('#deactivate-user-id').closest('form').action = '<?= base_url('itso/users/toggle-status/') ?><?= $user['id'] ?>'">
                                            Deactivate
                                        </label>
                                    <?php else: ?>
                                        <a href="<?= base_url('itso/users/toggle-status/' . $user['id']) ?>" class="btn-toggle">Activate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Page <?= $currentPage ?? 1 ?> of <?= $totalPages ?? 1 ?> | Showing <?= $startItem ?? 1 ?> to <?= $endItem ?? count($users ?? []) ?> of <?= $totalItems ?? count($users ?? []) ?> users
            </div>
            <div class="pagination-controls">
                <?php if (($currentPage ?? 1) > 1): ?>
                    <a href="<?= base_url('itso/users?page=' . (($currentPage ?? 1) - 1) . (!empty($roleFilter) ? '&role=' . $roleFilter : '')) ?>" class="pagination-btn">Previous</a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>Previous</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="<?= base_url('itso/users?page=' . $i . (!empty($roleFilter) ? '&role=' . $roleFilter : '')) ?>" class="pagination-btn <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="<?= base_url('itso/users?page=' . (($currentPage ?? 1) + 1) . (!empty($roleFilter) ? '&role=' . $roleFilter : '')) ?>" class="pagination-btn">Next</a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>Next</span>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <input type="checkbox" id="modal-toggle">
    <div class="modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="modal-title">Confirm Deactivation</h2>
            <p class="modal-message">
                Are you sure you want to deactivate <strong id="user-name"></strong>? 
                This user will no longer be able to access the system.
            </p>
            <div class="modal-buttons">
                <form action="" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" id="deactivate-user-id" name="user_id" value="">
                    <button type="submit" class="modal-btn modal-btn-confirm">Yes, Deactivate</button>
                </form>
                <label for="modal-toggle" class="modal-btn modal-btn-cancel">Cancel</label>
            </div>
        </div>
    </div>

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