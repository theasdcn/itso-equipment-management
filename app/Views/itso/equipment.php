<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ITSO EMS - Equipment Management') ?></title>
    
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

        .action-bar { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .btn-primary { background: var(--gold); color: var(--forest-green); padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }

        .filter-section { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .form-group { display: flex; flex-direction: column; }
        .form-label { font-size: 0.9rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; }
        .form-select, .form-input { padding: 0.7rem; border: 1.5px solid #dcdcdc; border-radius: var(--radius-input); background: #fafafa; font-family: inherit; }
        .form-select:focus, .form-input:focus { outline: none; border-color: var(--gold); background: white; }

        .equipment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .equipment-card { background: var(--white); border-radius: var(--radius-card); box-shadow: var(--shadow); overflow: hidden; transition: all 0.3s ease; border: 2px solid transparent; }
        .equipment-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-light); border-color: var(--gold); }
        .card-header { background: var(--forest-green); color: var(--gold-tint); padding: 1rem; }
        .equipment-code { font-family: monospace; font-weight: 600; font-size: 0.9rem; }
        .card-body { padding: 1.5rem; }
        .equipment-name { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 0.5rem; font-size: 1.2rem; }
        .equipment-category { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; }
        .quantity-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem; }
        .quantity-item { text-align: center; padding: 0.5rem; border-radius: var(--radius-input); }
        .quantity-total { background: #e3f2fd; color: #1976d2; }
        .quantity-available { background: #e8f5e8; color: #2e7d32; }
        .quantity-in-use { background: #fff3e0; color: #f57c00; }
        .quantity-number { font-weight: 700; font-size: 1.1rem; }
        .quantity-label { font-size: 0.8rem; }
        .status-badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-available { background: #e8f5e8; color: #2e7d32; }
        .status-unavailable { background: #ffebee; color: #c62828; }
        .status-maintenance { background: #fff3e0; color: #f57c00; }
        .card-actions { display: flex; gap: 0.5rem; margin-top: 1rem; }
        .btn-action { padding: 0.5rem 1rem; border-radius: var(--radius-input); text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; flex: 1; text-align: center; }
        .btn-view { background: var(--gold); color: var(--forest-green); }
        .btn-edit { background: #2196f3; color: white; }
        .btn-deactivate { background: #f57c00; color: white; }

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

        .empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
        .empty-state-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .confirmation-modal {
            background: var(--white);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow);
            width: 90%;
            max-width: 500px;
            padding: 2rem;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .confirmation-modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .modal-icon {
            font-size: 1.5rem;
            color: #f57c00;
            background: #fff3e0;
            padding: 0.8rem;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-title {
            font-family: "Playfair Display", serif;
            color: var(--forest-green);
            font-size: 1.3rem;
        }

        .modal-body {
            margin-bottom: 2rem;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .maintenance-info {
            background: #fff8e1;
            border: 1px solid #ffecb3;
            border-radius: var(--radius-input);
            padding: 1rem;
            margin: 1rem 0;
        }

        .maintenance-count {
            font-weight: 700;
            color: #f57c00;
            font-size: 1.1rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-input);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-confirm {
            background: #f57c00;
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-input);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: #e67300;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .action-bar { flex-direction: column; align-items: stretch; }
            .filter-form { grid-template-columns: 1fr; }
            .equipment-grid { grid-template-columns: 1fr; }
            .card-actions { flex-direction: column; }
            .pagination { flex-direction: column; gap: 1rem; text-align: center; }
            .modal-actions { flex-direction: column; }
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
            <a href="<?= base_url('itso/equipment') ?>" class="nav-link active">Equipment</a>
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
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">Equipment Management</h1>
            <p class="page-subtitle">Manage all equipment, gadgets, and inventory items</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-number"><?= $stats['total_items'] ?? 0 ?></div>
                <div class="stat-label">Total Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-number"><?= $stats['total_quantity'] ?? 0 ?></div>
                <div class="stat-label">Total Quantity</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?= $stats['categories_count'] ?? 0 ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?= $stats['available_quantity'] ?? 0 ?></div>
                <div class="stat-label">Available</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-laptop-house"></i>
                </div>
                <div class="stat-number"><?= $stats['in_use_quantity'] ?? 0 ?></div>
                <div class="stat-label">In Use</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-number"><?= $stats['maintenance_quantity'] ?? 0 ?></div>
                <div class="stat-label">Maintenance</div>
            </div>
        </div>

        <div class="action-bar">
            <a href="<?= base_url('itso/equipment/add') ?>" class="btn-primary">
                <i class="fas fa-plus"></i>
                <span>Add New Equipment</span>
            </a>
            <div style="font-weight: 500; color: var(--forest-green);">
                Showing <?= count($equipment ?? []) ?> equipment item(s)
            </div>
        </div>

        <div class="filter-section">
            <form method="get" class="filter-form">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories ?? [] as $category): ?>
                            <option value="<?= $category['category'] ?>" <?= ($filters['category'] ?? '') === $category['category'] ? 'selected' : '' ?>>
                                <?= $category['category'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available" <?= ($filters['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="unavailable" <?= ($filters['status'] ?? '') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                        <option value="maintenance" <?= ($filters['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-input" placeholder="Search equipment..." value="<?= $filters['search'] ?? '' ?>">
                </div>
                <div class="form-group" style="justify-content: flex-end;">
                    <button type="submit" class="btn-primary" style="margin-top: 1.7rem;">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="<?= base_url('itso/equipment') ?>" class="btn-action" style="background: #6c757d; color: white; margin-top: 1.7rem; text-decoration: none;">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <div class="equipment-grid">
            <?php if (!empty($equipment)): ?>
                <?php foreach ($equipment as $item): ?>
                    <div class="equipment-card">
                        <div class="card-header">
                            <div class="equipment-code"><?= $item['equipment_code'] ?></div>
                        </div>
                        <div class="card-body">
                            <h3 class="equipment-name"><?= esc($item['name']) ?></h3>
                            <div class="equipment-category"><?= esc($item['category']) ?></div>

                            <?php if (!empty($item['image_url'])): ?>
                                <div style="margin-bottom: 1rem; text-align: center;">
                                    <img src="<?= $item['image_url'] ?>" 
                                        alt="<?= esc($item['name']) ?>" 
                                        style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid var(--gold-tint);"
                                        onerror="this.onerror=null; this.style.display='none'; this.parentNode.innerHTML='<div style=\'background: var(--gold-tint); padding: 2rem; border-radius: 8px;\'><div style=\'font-size: 2rem; color: var(--text-muted); margin-bottom: 0.5rem;\'>📷</div><div style=\'font-size: 0.8rem; color: var(--text-muted);\'>Image not found</div><div style=\'font-size: 0.7rem; color: #999; margin-top: 0.5rem;\'>URL: <?= esc($item['image_url']) ?></div></div>';">
                                </div>
                            <?php else: ?>
                                <div style="margin-bottom: 1rem; text-align: center; background: var(--gold-tint); padding: 2rem; border-radius: 8px;">
                                    <div style="font-size: 2rem; color: var(--text-muted); margin-bottom: 0.5rem;">📷</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">No Image Available</div>
                                    <div style="font-size: 0.7rem; color: #999; margin-top: 0.5rem;">Category: <?= esc($item['category'] ?? 'N/A') ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="quantity-info">
                                <div class="quantity-item quantity-total">
                                    <div class="quantity-number"><?= $item['total_quantity'] ?></div>
                                    <div class="quantity-label">Total</div>
                                </div>
                                <div class="quantity-item quantity-available">
                                    <div class="quantity-number"><?= $item['available_quantity'] ?></div>
                                    <div class="quantity-label">Available</div>
                                </div>
                                <div class="quantity-item quantity-in-use">
                                    <div class="quantity-number"><?= $item['in_use_quantity'] ?></div>
                                    <div class="quantity-label">In Use</div>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <span class="status-badge status-<?= $item['status'] ?>">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </div>
                            
                            <div class="card-actions">
                                <a href="<?= base_url('itso/equipment/view/' . $item['equipment_id']) ?>" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <?php if ($item['status'] !== 'maintenance'): ?>
                                    <a href="<?= base_url('itso/equipment/edit/' . $item['equipment_id']) ?>" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                <?php else: ?>
                                    <button class="btn-action" style="background: #cccccc; color: #666666; cursor: not-allowed;" title="Cannot edit equipment under maintenance">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn-action btn-deactivate" onclick="showDeactivateConfirmation('<?= $item['equipment_id'] ?>', '<?= esc($item['name']) ?>', <?= $stats['maintenance_quantity'] ?? 0 ?>)">
                                    <i class="fas fa-power-off"></i> Deactivate
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">🔧</div>
                    <h3>No Equipment Found</h3>
                    <p>No equipment items match your current filters.</p>
                    <a href="<?= base_url('itso/equipment/add') ?>" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i>
                        Add Your First Equipment
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Page <?= $currentPage ?? 1 ?> of <?= $totalPages ?? 1 ?> | Showing <?= $startItem ?? 1 ?> to <?= $endItem ?? count($equipment ?? []) ?> of <?= $totalItems ?? count($equipment ?? []) ?> products
            </div>
            <div class="pagination-controls">
                <?php if (($currentPage ?? 1) > 1): ?>
                    <a href="<?= base_url('itso/equipment?page=' . (($currentPage ?? 1) - 1) . (!empty($filters['category']) ? '&category=' . $filters['category'] : '') . (!empty($filters['status']) ? '&status=' . $filters['status'] : '') . (!empty($filters['search']) ? '&search=' . $filters['search'] : '')) ?>" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>
                        <i class="fas fa-chevron-left"></i> Previous
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="<?= base_url('itso/equipment?page=' . $i . (!empty($filters['category']) ? '&category=' . $filters['category'] : '') . (!empty($filters['status']) ? '&status=' . $filters['status'] : '') . (!empty($filters['search']) ? '&search=' . $filters['search'] : '')) ?>" class="pagination-btn <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="<?= base_url('itso/equipment?page=' . (($currentPage ?? 1) + 1) . (!empty($filters['category']) ? '&category=' . $filters['category'] : '') . (!empty($filters['status']) ? '&status=' . $filters['status'] : '') . (!empty($filters['search']) ? '&search=' . $filters['search'] : '')) ?>" class="pagination-btn">
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

    <div class="modal-overlay" id="deactivateModal">
        <div class="confirmation-modal">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="modal-title">Confirm Deactivation</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate <strong id="equipmentName"></strong>?</p>
                <p>Deactivated equipment will no longer be available for borrowing but will remain in the system for record-keeping purposes.</p>
                
                <div class="maintenance-info">
                    <p><strong>Maintenance Overview:</strong></p>
                    <p>Currently, there are <span class="maintenance-count" id="maintenanceCount">0</span> equipment items under maintenance in the system.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDeactivateModal()">Cancel</button>
                <button class="btn-confirm" id="confirmDeactivate">Yes, Deactivate</button>
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

    <script>
        
        let currentEquipmentId = null;

        function showDeactivateConfirmation(equipmentId, equipmentName, maintenanceCount) {
            currentEquipmentId = equipmentId;
            document.getElementById('equipmentName').textContent = equipmentName;
            document.getElementById('maintenanceCount').textContent = maintenanceCount;
            document.getElementById('deactivateModal').classList.add('active');
        }

        function closeDeactivateModal() {
            document.getElementById('deactivateModal').classList.remove('active');
            currentEquipmentId = null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            
            document.getElementById('confirmDeactivate').addEventListener('click', function() {
                if (currentEquipmentId) {
                    window.location.href = '<?= base_url('itso/equipment/toggle-status/') ?>' + currentEquipmentId;
                }
            });

            document.getElementById('deactivateModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeactivateModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeactivateModal();
                }
            });
        });
    </script>
</body>
</html>