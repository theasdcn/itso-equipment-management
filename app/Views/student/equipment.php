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
        .status-unavailable { background: #ffebee; color: #c62828; }
        .card-actions { display: flex; gap: 0.5rem; margin-top: 1rem; }
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

        .accessories-list { margin: 1rem 0; padding: 1rem; background: var(--gold-tint); border-radius: var(--radius-input); }
        .accessories-title { font-weight: 600; color: var(--forest-green); margin-bottom: 0.5rem; }
        .accessory-item { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem; }
        .accessory-item:last-child { margin-bottom: 0; }

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
            max-width: 450px;
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

        .btn-confirm {
            background: var(--gold);
            color: var(--forest-green);
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-input);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: #c19a2f;
            transform: translateY(-2px);
        }

        .message-alert {
            padding: 1rem;
            border-radius: var(--radius-input);
            margin-bottom: 1rem;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .equipment-grid { grid-template-columns: 1fr; }
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
        <div class="system-subtitle">Equipment Management System - Student Portal</div>
    </header>

    <nav class="nav-header">
        <div class="nav-links">
            <a href="<?= base_url('student/dashboard') ?>" class="nav-link">Dashboard</a>
            <a href="<?= base_url('student/equipment') ?>" class="nav-link active">Browse Equipment</a>
        </div>
        <div class="user-info">
            
            <?php 
            $profilePicture = session()->get('profile_picture');
            $defaultPicture = 'profile_default-removebg-preview.png';
            $profilePicturePath = $profilePicture ? base_url('uploads/profiles/' . $profilePicture) : base_url('img/' . $defaultPicture);
            ?>
            <img src="<?= $profilePicturePath ?>" alt="Profile Picture" class="profile-picture">

            <span>Welcome, <?= esc(session()->get('first_name') . ' ' . session()->get('last_name')) ?></span>
            <span class="user-badge"><?= ucfirst(session()->get('role')) ?></span>
            <form action="<?= base_url('logout') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <main class="dashboard-container">
        
        <div class="page-header">
            <h1 class="page-title">Browse Equipment</h1>
            <p class="page-subtitle">Available equipment for academic purposes - Borrow equipment directly</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="message-alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="message-alert alert-error">
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
                                <span class="status-badge <?= $item['available_quantity'] > 0 ? 'status-available' : 'status-unavailable' ?>">
                                    <?= $item['available_quantity'] > 0 ? 'Available for Academic Use' : 'Currently Unavailable' ?>
                                </span>
                            </div>

                            <div class="accessories-list">
                                <div class="accessories-title">📦 Included Accessories:</div>
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
                                        <span>✓</span>
                                        <span><?= $accessory ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (!empty($item['description'])): ?>
                                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                                    <?= esc($item['description']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-actions">
                                <button class="btn btn-primary" 
                                        onclick="showBorrowConfirmation('<?= $item['equipment_id'] ?>', '<?= esc($item['name']) ?>')"
                                        <?= $item['available_quantity'] <= 0 ? 'disabled' : '' ?>>
                                    Borrow Equipment
                                </button>
                                <button class="btn btn-return" 
                                        onclick="showReturnConfirmation('<?= $item['equipment_id'] ?>', '<?= esc($item['name']) ?>')"
                                        <?= !$item['is_borrowed_by_user'] ? 'disabled' : '' ?>>
                                    Return Equipment
                                </button>
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
                <div class="modal-icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <h3 class="modal-title">Confirm Borrow</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to borrow <strong id="borrowEquipmentName"></strong>?</p>
                <p>By borrowing this equipment, you agree to:</p>
                <div style="background: var(--gold-tint); padding: 1rem; border-radius: var(--radius-input); margin-top: 1rem;">
                    <strong>Borrowing Agreement:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        <li>Use equipment for academic purposes only</li>
                        <li>Return equipment in good condition</li>
                        <li>Return all accessories included</li>
                        <li>Return by the specified due date</li>
                        <li>Report any issues immediately</li>
                    </ul>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeBorrowModal()">Cancel</button>
                <form id="borrowForm" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="equipment_id" id="borrowEquipmentId">
                    <button type="submit" class="btn-confirm">Yes, Borrow Equipment</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="returnModal">
        <div class="confirmation-modal">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h3 class="modal-title">Confirm Return</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to return <strong id="returnEquipmentName"></strong>?</p>
                <p>Please ensure all accessories are included and the equipment is in good condition before returning.</p>
                <div style="background: #fff3e0; padding: 1rem; border-radius: var(--radius-input); margin-top: 1rem;">
                    <strong>Return Checklist:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        <li>All accessories are included</li>
                        <li>Equipment is in working condition</li>
                        <li>No visible damage or issues</li>
                        <li>All cables and components are present</li>
                    </ul>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeReturnModal()">Cancel</button>
                <form id="returnForm" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="equipment_id" id="returnEquipmentId">
                    <button type="submit" class="btn-confirm">Yes, Return Equipment</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="system-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>ITSO Student Portal</h4>
                <p>Academic Equipment Borrowing System</p>
            </div>
            <div class="footer-help">
                <div><strong>Student Support</strong></div>
                <p>Contact: itso-support@fit.edu.ph</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. Student Access.</p>
            </div>
        </div>
    </footer>

    <script>
        let currentEquipmentId = null;
        
        function showBorrowConfirmation(equipmentId, equipmentName) {
            currentEquipmentId = equipmentId;
            document.getElementById('borrowEquipmentName').textContent = equipmentName;
            document.getElementById('borrowEquipmentId').value = equipmentId;
            document.getElementById('borrowModal').classList.add('active');
        }
        
        function closeBorrowModal() {
            document.getElementById('borrowModal').classList.remove('active');
            currentEquipmentId = null;
        }
        
        function showReturnConfirmation(equipmentId, equipmentName) {
            currentEquipmentId = equipmentId;
            document.getElementById('returnEquipmentName').textContent = equipmentName;
            document.getElementById('returnEquipmentId').value = equipmentId;
            document.getElementById('returnModal').classList.add('active');
        }
        
        function closeReturnModal() {
            document.getElementById('returnModal').classList.remove('active');
            currentEquipmentId = null;
        }
        
        document.getElementById('borrowForm').action = '<?= base_url('student/equipment/borrow') ?>';
        document.getElementById('returnForm').action = '<?= base_url('student/equipment/return') ?>';
        
        document.getElementById('borrowModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });
        
        document.getElementById('returnModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });
    </script>
</body>
</html>