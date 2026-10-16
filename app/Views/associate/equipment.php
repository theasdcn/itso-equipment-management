<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSO EMS - Equipment Management</title>
    
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

        .dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--dark-green), var(--forest-green)); color: var(--white); padding: 2rem; border-radius: var(--radius-card); margin-bottom: 2rem; border-left: 6px solid var(--gold); }
        .page-title { font-family: "Playfair Display", serif; color: var(--gold); margin-bottom: 0.5rem; font-size: 2rem; }
        .page-subtitle { color: var(--gold-tint); font-size: 1.1rem; }

        .equipment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .equipment-card { background: var(--white); border-radius: var(--radius-card); box-shadow: var(--shadow); overflow: hidden; transition: all 0.3s ease; border: 2px solid transparent; }
        .equipment-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-light); border-color: var(--gold); }
        .card-header { background: var(--forest-green); color: var(--gold-tint); padding: 1rem; }
        .equipment-code { font-family: monospace; font-weight: 600; font-size: 0.9rem; }
        .card-body { padding: 1.5rem; }
        .equipment-name { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 0.5rem; font-size: 1.2rem; }
        .equipment-category { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; }
        .quantity-info { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; margin-bottom: 1rem; }
        .quantity-item { text-align: center; padding: 0.5rem; border-radius: var(--radius-input); }
        .quantity-total { background: #e3f2fd; color: #1976d2; }
        .quantity-available { background: #e8f5e8; color: #2e7d32; }
        .quantity-number { font-weight: 700; font-size: 1.1rem; }
        .quantity-label { font-size: 0.8rem; }
        .status-badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-available { background: #e8f5e8; color: #2e7d32; }
        .status-borrowed { background: #fff3e0; color: #f57c00; }
        .card-actions { display: flex; gap: 0.5rem; margin-top: 1rem; flex-wrap: wrap; }
        .btn { padding: 0.6rem 1.2rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem; }
        .btn-primary { background: var(--gold); color: var(--forest-green); }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-primary:disabled { background: #cccccc; cursor: not-allowed; transform: none; }
        .btn-secondary { background: var(--forest-green); color: var(--white); }
        .btn-secondary:hover { background: var(--dark-green); transform: translateY(-2px); }
        .btn-return { background: #28a745; color: white; }
        .btn-return:hover { background: #218838; transform: translateY(-2px); }
        .btn-return:disabled { background: #cccccc; cursor: not-allowed; transform: none; }

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

        .borrowed-info {
            background: #fff3e0;
            padding: 0.8rem;
            border-radius: var(--radius-input);
            margin-bottom: 1rem;
            border-left: 4px solid #f57c00;
        }

        .borrowed-label {
            font-weight: 600;
            color: #f57c00;
            margin-bottom: 0.3rem;
        }

        .borrowed-date {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .accessories-list { 
            margin: 1rem 0; 
            padding: 1rem; 
            background: var(--gold-tint); 
            border-radius: var(--radius-input); 
        }
        .accessories-title { 
            font-weight: 600; 
            color: var(--forest-green); 
            margin-bottom: 0.5rem; 
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .accessory-item { 
            display: flex; 
            align-items: center; 
            gap: 0.5rem; 
            margin-bottom: 0.3rem; 
            font-size: 0.9rem;
        }
        .accessory-item:last-child { 
            margin-bottom: 0; 
        }

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
            border-radius: 30px;
            box-shadow: var(--shadow);
            width: 70%;
            font-size: small;
            max-width: 500px;
            padding: 2rem;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            max-height: 100vh;
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
            color: var(--gold);
            background: var(--gold-tint);
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--forest-green);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: var(--radius-input);
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-col {
            flex: 1;
        }

        .info-note {
            background: #e8f5e8;
            padding: 0.8rem;
            border-radius: var(--radius-input);
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }

        .warning-note {
            background: #fff3e0;
            padding: 0.8rem;
            border-radius: var(--radius-input);
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #f57c00;
            border-left: 4px solid #f57c00;
        }

        .accessories-checklist {
            margin: 1rem 0;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-radius: var(--radius-input);
        }

        .checklist-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--gold);
        }

        .accessory-subitem {
            margin-left: 2rem;
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .equipment-grid { grid-template-columns: 1fr; }
            .card-actions { flex-direction: column; }
            .modal-actions { flex-direction: column; }
            .form-row { flex-direction: column; }
            .accessory-subitem { margin-left: 1rem; }
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
            <a href="<?= base_url('associate/equipment') ?>" class="nav-link active">Browse Equipment</a>
            <a href="<?= base_url('associate/reservations') ?>" class="nav-link">My Reservations</a>
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
            <h1 class="page-title">Browse Equipment</h1>
            <p class="page-subtitle">Available equipment for borrowing with extended associate privileges</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="message-alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="message-alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

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
                                        onerror="this.onerror=null; this.style.display='none'; this.parentNode.innerHTML='<div style=\'background: var(--gold-tint); padding: 2rem; border-radius: 8px;\'><div style=\'font-size: 2rem; color: var(--text-muted); margin-bottom: 0.5rem;\'>📷</div><div style=\'font-size: 0.8rem; color: var(--text-muted);\'>Image not found</div></div>';">
                                </div>
                            <?php else: ?>
                                <div style="margin-bottom: 1rem; text-align: center; background: var(--gold-tint); padding: 2rem; border-radius: 8px;">
                                    <div style="font-size: 2rem; color: var(--text-muted); margin-bottom: 0.5rem;">📷</div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">No Image Available</div>
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
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <span class="status-badge <?= $item['available_quantity'] > 0 ? 'status-available' : 'status-borrowed' ?>">
                                    <?= $item['available_quantity'] > 0 ? 'Available' : 'All Borrowed' ?>
                                </span>
                            </div>

                            <div class="accessories-list">
                                <div class="accessories-title">
                                    <span>📦 Included Accessories:</span>
                                </div>
                                <?php
                                $accessories = [
                                    'Laptops' => ['Charger', 'Laptop Bag'],
                                    'DLP Projectors' => ['Extension Cord', 'VGA/HDMI Cable', 'Power Cable', 'Remote Control'],
                                    'Cables & Adapters' => ['Adapter Case'],
                                    'Input Devices' => ['USB Cable', 'Batteries'],
                                    'Drawing Tablets' => ['Pen', 'USB Cable', 'Driver CD'],
                                    'Audio Equipment' => ['Cables', 'Power Adapter', 'Microphone'],
                                    'Video Equipment' => ['Tripod', 'Cables', 'Memory Card'],
                                    'Networking Tools' => ['Cables', 'Documentation'],
                                    'Lab Accessories' => ['Power Cord', 'Connectors'],
                                    'Other Equipment' => ['Standard Accessories']
                                ];
                                
                                $itemAccessories = $accessories[$item['category']] ?? ['Standard Accessories'];
                                foreach ($itemAccessories as $accessory): 
                                ?>
                                    <div class="accessory-item">
                                        <span style="color: var(--forest-green);">✓</span>
                                        <span><?= $accessory ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (isset($item['is_borrowed_by_user']) && $item['is_borrowed_by_user']): ?>
                                <div class="borrowed-info">
                                    <div class="borrowed-label">✓ Currently Borrowed by You</div>
                                    <div class="borrowed-date">Due: <?= date('M j, Y g:i A', strtotime($item['expected_return_date'] ?? '+30 days')) ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($item['description'])): ?>
                                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                                    <?= esc($item['description']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-actions">
                                
                                <button type="button" class="btn btn-primary borrow-btn" 
                                        data-equipment-id="<?= $item['equipment_id'] ?>"
                                        data-equipment-name="<?= esc($item['name']) ?>"
                                        <?= $item['available_quantity'] <= 0 ? 'disabled' : '' ?>>
                                    Borrow Now
                                </button>

                                <button type="button" class="btn btn-return return-btn" 
                                        data-equipment-id="<?= $item['equipment_id'] ?>"
                                        data-equipment-name="<?= esc($item['name']) ?>"
                                        data-equipment-category="<?= esc($item['category']) ?>"
                                        <?= !(isset($item['is_borrowed_by_user']) && $item['is_borrowed_by_user']) ? 'disabled' : '' ?>>
                                    Return
                                </button>

                                <a href="<?= base_url('associate/reservations/new?equipment_id=' . $item['equipment_id']) ?>" 
                                   class="btn btn-secondary" 
                                   style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;"
                                   <?= $item['available_quantity'] > 0 ? 'disabled' : '' ?>>
                                    Reserve
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">🔧</div>
                    <h3>No Equipment Available</h3>
                    <p>All equipment is currently checked out or under maintenance.</p>
                    <p>Please check back later or contact ITSO for assistance.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div class="modal-overlay" id="borrowModal">
        <div class="confirmation-modal">
            <div class="modal-header">
                <div class="modal-icon">📅</div>
                <h2 class="modal-title">Borrow Equipment</h2>
            </div>
            <div class="modal-body">
                <form id="borrowForm" action="<?= base_url('associate/equipment/borrow') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="equipment_id" id="modalEquipmentId">
                    
                    <div class="form-group">
                        <p>You are about to borrow: <strong id="modalEquipmentName"></strong></p>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label" for="borrow_date">Start Date *</label>
                            <input type="date" class="form-input" id="borrow_date" name="borrow_date" required>
                        </div>
                        <div class="form-col">
                            <label class="form-label" for="borrow_time">Start Time *</label>
                            <input type="time" class="form-input" id="borrow_time" name="borrow_time" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label" for="return_date">Expected Return Date *</label>
                            <input type="date" class="form-input" id="return_date" name="return_date" required>
                        </div>
                        <div class="form-col">
                            <label class="form-label" for="return_time">Return Time *</label>
                            <input type="time" class="form-input" id="return_time" name="return_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="purpose">Purpose *</label>
                        <textarea class="form-input" id="purpose" name="purpose" rows="3" placeholder="Please describe the purpose of borrowing this equipment..." required></textarea>
                    </div>
                    
                    <div class="info-note">
                        <strong>Note:</strong> Please ensure you return the equipment on or before the selected return date.
                    </div>
                </form>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="cancelBorrow">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBorrow">Confirm Borrow</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="returnModal">
        <div class="confirmation-modal">
            <div class="modal-header">
                <div class="modal-icon">↩️</div>
                <h2 class="modal-title">Return Equipment</h2>
            </div>
            <div class="modal-body">
                <form id="returnForm" action="<?= base_url('associate/equipment/return') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="equipment_id" id="returnEquipmentId">
                    
                    <div class="form-group">
                        <p>You are about to return: <strong id="returnEquipmentName"></strong></p>
                    </div>
                    
                    <div class="warning-note">
                        <strong>Important:</strong> Please ensure all accessories are included and in good condition.
                    </div>
                    
                    <div class="form-group">
                        <div class="accessories-checklist" id="accessoriesChecklist">
                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="return_notes">Return Notes (Optional)</label>
                        <textarea class="form-input" id="return_notes" name="return_notes" rows="3" placeholder="Add any notes about the equipment condition or issues encountered..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="cancelReturn">Cancel</button>
                <button type="button" class="btn btn-return" id="confirmReturn">Confirm Return</button>
            </div>
        </div>
    </div>

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
        
        const accessoriesData = {
            'Laptops': ['Charger', 'Laptop Bag'],
            'DLP Projectors': ['Extension Cord', 'VGA/HDMI Cable', 'Power Cable', 'Remote Control'],
            'Cables & Adapters': ['Adapter Case'],
            'Input Devices': ['USB Cable', 'Batteries'],
            'Drawing Tablets': ['Pen', 'USB Cable', 'Driver CD'],
            'Audio Equipment': ['Cables', 'Power Adapter', 'Microphone'],
            'Video Equipment': ['Tripod', 'Cables', 'Memory Card'],
            'Networking Tools': ['Cables', 'Documentation'],
            'Lab Accessories': ['Power Cord', 'Connectors'],
            'Other Equipment': ['Standard Accessories']
        };

        document.addEventListener('DOMContentLoaded', function() {
            const borrowModal = document.getElementById('borrowModal');
            const borrowBtns = document.querySelectorAll('.borrow-btn');
            const cancelBorrowBtn = document.getElementById('cancelBorrow');
            const confirmBorrowBtn = document.getElementById('confirmBorrow');
            const borrowForm = document.getElementById('borrowForm');
            const modalEquipmentId = document.getElementById('modalEquipmentId');
            const modalEquipmentName = document.getElementById('modalEquipmentName');
            
            const returnModal = document.getElementById('returnModal');
            const returnBtns = document.querySelectorAll('.return-btn');
            const cancelReturnBtn = document.getElementById('cancelReturn');
            const confirmReturnBtn = document.getElementById('confirmReturn');
            const returnForm = document.getElementById('returnForm');
            const returnEquipmentId = document.getElementById('returnEquipmentId');
            const returnEquipmentName = document.getElementById('returnEquipmentName');
            const accessoriesChecklist = document.getElementById('accessoriesChecklist');
            
            const today = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);
            
            const formattedToday = today.toISOString().split('T')[0];
            const formattedTomorrow = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('borrow_date').value = formattedToday;
            document.getElementById('borrow_date').min = formattedToday;
            
            document.getElementById('return_date').value = formattedTomorrow;
            document.getElementById('return_date').min = formattedTomorrow;
            
            document.getElementById('borrow_time').value = '09:00';
            document.getElementById('return_time').value = '17:00';
            
            borrowBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const equipmentId = this.getAttribute('data-equipment-id');
                    const equipmentName = this.getAttribute('data-equipment-name');
                    
                    modalEquipmentId.value = equipmentId;
                    modalEquipmentName.textContent = equipmentName;
                    
                    borrowModal.classList.add('active');
                });
            });
            
            cancelBorrowBtn.addEventListener('click', function() {
                borrowModal.classList.remove('active');
            });
            
            confirmBorrowBtn.addEventListener('click', function() {
                const borrowDate = document.getElementById('borrow_date').value;
                const borrowTime = document.getElementById('borrow_time').value;
                const returnDate = document.getElementById('return_date').value;
                const returnTime = document.getElementById('return_time').value;
                const purpose = document.getElementById('purpose').value;
                
                if (!borrowDate || !borrowTime || !returnDate || !returnTime || !purpose) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                const borrowDateTime = new Date(borrowDate + ' ' + borrowTime);
                const returnDateTime = new Date(returnDate + ' ' + returnTime);
                
                if (returnDateTime <= borrowDateTime) {
                    alert('Return date and time must be after borrow date and time.');
                    return;
                }
                
                borrowForm.submit();
            });
            
            borrowModal.addEventListener('click', function(e) {
                if (e.target === borrowModal) {
                    borrowModal.classList.remove('active');
                }
            });
            
            returnBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const equipmentId = this.getAttribute('data-equipment-id');
                    const equipmentName = this.getAttribute('data-equipment-name');
                    const equipmentCategory = this.getAttribute('data-equipment-category');
                    
                    returnEquipmentId.value = equipmentId;
                    returnEquipmentName.textContent = equipmentName;
                    
                    populateAccessoriesChecklist(equipmentCategory);
                    
                    returnModal.classList.add('active');
                });
            });
            
            cancelReturnBtn.addEventListener('click', function() {
                returnModal.classList.remove('active');
            });
            
            confirmReturnBtn.addEventListener('click', function() {

                const mainCheckbox = document.getElementById('confirm_all_accessories');
                if (!mainCheckbox.checked) {
                    alert('Please confirm that all accessories are included before returning the equipment.');
                    return;
                }
                
                returnForm.submit();
            });
            
            returnModal.addEventListener('click', function(e) {
                if (e.target === returnModal) {
                    returnModal.classList.remove('active');
                }
            });
            
            function populateAccessoriesChecklist(category) {
                accessoriesChecklist.innerHTML = '';
                
                const accessories = accessoriesData[category] || ['Standard Accessories'];

                const mainChecklistItem = document.createElement('div');
                mainChecklistItem.className = 'checklist-item';
                mainChecklistItem.style.background = 'transparent';
                mainChecklistItem.style.padding = '0.5rem 0';
                mainChecklistItem.style.marginBottom = '1rem';
                
                mainChecklistItem.innerHTML = `
                    <input type="checkbox" id="confirm_all_accessories">
                    <label for="confirm_all_accessories" style="font-weight: 500; color: var(--forest-green);">
                        Please confirm all accessories are included:
                    </label>
                `;
                accessoriesChecklist.appendChild(mainChecklistItem);

                const accessoriesContainer = document.createElement('div');
                accessoriesContainer.className = 'accessory-subitem';
                
                accessories.forEach(accessory => {
                    const checklistItem = document.createElement('div');
                    checklistItem.className = 'checklist-item';
                    checklistItem.style.background = 'transparent';
                    checklistItem.style.padding = '0.25rem 0';
                    
                    checklistItem.innerHTML = `
                        <input type="checkbox" id="accessory_${accessory.replace(/\s+/g, '_')}" name="accessories[]" value="${accessory}">
                        <label for="accessory_${accessory.replace(/\s+/g, '_')}">${accessory}</label>
                    `;
                    
                    accessoriesContainer.appendChild(checklistItem);
                });
                
                accessoriesChecklist.appendChild(accessoriesContainer);

                const mainCheckbox = document.getElementById('confirm_all_accessories');
                const accessoryCheckboxes = accessoriesContainer.querySelectorAll('input[type="checkbox"]');
                
                mainCheckbox.addEventListener('change', function() {
                    accessoryCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                accessoryCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(accessoryCheckboxes).every(cb => cb.checked);
                        mainCheckbox.checked = allChecked;
                    });
                });
            }

            const reserveButtons = document.querySelectorAll('.btn-secondary[href*="reservations/new"]');
            reserveButtons.forEach(button => {
                if (!button.disabled) {
                    button.addEventListener('click', function(e) {
                        const equipmentId = new URL(this.href).searchParams.get('equipment_id');
                        const equipmentName = this.closest('.equipment-card').querySelector('.equipment-name').textContent;
                        
                        if (!confirm(`Would you like to reserve "${equipmentName}" for future use?`)) {
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>