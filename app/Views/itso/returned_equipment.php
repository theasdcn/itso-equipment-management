<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - ITSO EMS</title>

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

        .table-container {
            background: var(--white);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-top: 2rem;
        }

        .table-header {
            background: var(--forest-green);
            color: var(--gold);
            padding: 1.5rem;
            border-bottom: 2px solid var(--gold);
        }

        .table-title {
            font-family: "Playfair Display", serif;
            font-size: 1.5rem;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--gold-tint);
        }

        th {
            background: var(--gold-tint);
            color: var(--forest-green);
            font-weight: 600;
        }

        tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-returned {
            background: #e9f7ef;
            color: #2b7a4b;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .filter-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--forest-green);
        }

        .form-input {
            padding: 0.7rem;
            border: 1px solid var(--gold-tint);
            border-radius: var(--radius-input);
            font-family: inherit;
        }

        .btn-filter {
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: var(--radius-input);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background: #c19a2f;
        }

        .btn-clear {
            background: var(--text-muted);
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: var(--radius-input);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        }

        .pagination { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-top: 2rem; 
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
            
            table {
                display: block;
                overflow-x: auto;
            }

            .filter-form {
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
            <a href="<?= base_url('itso-dashboard') ?>" class="nav-link">Dashboard</a>
            <a href="<?= base_url('itso/equipment') ?>" class="nav-link">Equipment</a>
            <a href="<?= base_url('itso/users') ?>" class="nav-link">Users</a>
            <a href="<?= base_url('itso/borrow-requests') ?>" class="nav-link">Borrow Requests</a>
            <a href="<?= base_url('itso/returned_equipment') ?>" class="nav-link active">Returned Equipment</a>
            <a href="<?= base_url('itso/reports') ?>" class="nav-link">Reports</a>
            <a href="<?= base_url(relativePath: 'itso/about_us') ?>" class="nav-link">About Us</a>

        </div>
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <main class="dashboard-container">
        
        <div class="admin-welcome">
            <div class="admin-badge">RETURNED EQUIPMENT</div>
            <h1 class="welcome-title">Returned Equipment History</h1>
            <p class="welcome-subtitle">View all equipment that has been returned by users.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $returnStats['total_returns'] ?></div>
                <div class="stat-label">Total Returns</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $returnStats['returns_today'] ?></div>
                <div class="stat-label">Returns Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $returnStats['returns_this_week'] ?></div>
                <div class="stat-label">Returns This Week</div>
            </div>
        </div>

        <div class="filter-section">
            <form method="get" class="filter-form">
                <div class="form-group">
                    <label class="form-label" for="date_from">Date From</label>
                    <input type="date" class="form-input" id="date_from" name="date_from" value="<?= $filters['date_from'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_to">Date To</label>
                    <input type="date" class="form-input" id="date_to" name="date_to" value="<?= $filters['date_to'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                </div>
                <div class="form-group">
                    <a href="<?= base_url('itso/returned_equipment') ?>" class="btn-clear">Clear Filters</a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Returned Equipment Records</h2>
            </div>
            
            <?php if (!empty($returnedEquipment)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th>Borrowed By</th>
                            <th>Role</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($returnedEquipment as $equipment): ?>
                            <tr>
                                <td><?= esc($equipment['equipment_name']) ?> (<?= esc($equipment['equipment_code']) ?>)</td>
                                <td><?= esc($equipment['first_name']) ?> <?= esc($equipment['last_name']) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $equipment['role'])) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($equipment['borrow_date'])) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($equipment['returned_at'])) ?></td>
                                <td>
                                    <span class="status-badge status-returned">Returned</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <h3>No returned equipment found</h3>
                    <p>There are no equipment return records matching your current filters.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="pagination">
            <div class="pagination-info">
                Page <?= $currentPage ?? 1 ?> of <?= $totalPages ?? 1 ?> | Showing <?= $startItem ?? 1 ?> to <?= $endItem ?? count($returnedEquipment ?? []) ?> of <?= $totalItems ?? count($returnedEquipment ?? []) ?> returns
            </div>
            <div class="pagination-controls">
                <?php if (($currentPage ?? 1) > 1): ?>
                    <a href="<?= base_url('itso/returned_equipment?page=' . (($currentPage ?? 1) - 1) . (!empty($filters['date_from']) ? '&date_from=' . $filters['date_from'] : '') . (!empty($filters['date_to']) ? '&date_to=' . $filters['date_to'] : '')) ?>" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>
                        <i class="fas fa-chevron-left"></i> Previous
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="<?= base_url('itso/returned_equipment?page=' . $i . (!empty($filters['date_from']) ? '&date_from=' . $filters['date_from'] : '') . (!empty($filters['date_to']) ? '&date_to=' . $filters['date_to'] : '')) ?>" class="pagination-btn <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="<?= base_url('itso/returned_equipment?page=' . (($currentPage ?? 1) + 1) . (!empty($filters['date_from']) ? '&date_from=' . $filters['date_from'] : '') . (!empty($filters['date_to']) ? '&date_to=' . $filters['date_to'] : '')) ?>" class="pagination-btn">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>
                        Next <i class="fas fa-chevron-right"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Equipment Management</h4>
                <p>Returned Equipment Tracking</p>
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