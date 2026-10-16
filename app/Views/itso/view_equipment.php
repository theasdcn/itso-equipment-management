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

        .equipment-details { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); }
        .detail-section { margin-bottom: 2rem; }
        .section-title { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 1rem; font-size: 1.3rem; border-bottom: 2px solid var(--gold-tint); padding-bottom: 0.5rem; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-weight: 600; color: var(--forest-green); margin-bottom: 0.3rem; font-size: 0.9rem; }
        .info-value { color: var(--text-dark); }
        .status-badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 0.3rem; }
        .status-available { background: #e8f5e8; color: #2e7d32; }
        .status-unavailable { background: #ffebee; color: #c62828; }
        .status-maintenance { background: #fff3e0; color: #f57c00; }

        .quantity-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin: 1rem 0; }
        .quantity-item { text-align: center; padding: 1rem; border-radius: var(--radius-input); }
        .quantity-total { background: #e3f2fd; color: #1976d2; }
        .quantity-available { background: #e8f5e8; color: #2e7d32; }
        .quantity-in-use { background: #fff3e0; color: #f57c00; }
        .quantity-maintenance { background: #f3e5f5; color: #7b1fa2; }
        .quantity-number { font-weight: 700; font-size: 1.5rem; margin-bottom: 0.3rem; }
        .quantity-label { font-size: 0.8rem; }

        .text-content { background: #f8f9fa; padding: 1rem; border-radius: var(--radius-input); border-left: 4px solid var(--gold); }
        .text-content p { margin-bottom: 0.5rem; }
        .text-content p:last-child { margin-bottom: 0; }

        .action-buttons { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn-primary, .btn-secondary { padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary { background: var(--gold); color: var(--forest-green); }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; }

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

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .info-grid { grid-template-columns: 1fr; }
            .quantity-grid { grid-template-columns: repeat(2, 1fr); }
            .action-buttons { flex-direction: column; }
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
            <a href="<?= base_url(relativePath: 'itso/about_us') ?>" class="nav-link">About Us</a>

        </div>
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <main class="dashboard-container">
        
        <div class="page-header">
            <h1 class="page-title">Equipment Details</h1>
            <p class="page-subtitle">View complete information about this equipment item</p>
        </div>

        <div class="equipment-details">
            
            <div class="detail-section">
                <h3 class="section-title">Basic Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Equipment Code</span>
                        <span class="info-value" style="font-family: monospace; font-weight: 600;"><?= $equipment['equipment_code'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value"><?= esc($equipment['name']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Category</span>
                        <span class="info-value"><?= esc($equipment['category']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="status-badge status-<?= $equipment['status'] ?>">
                            <?= ucfirst($equipment['status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (!empty($equipment['image_url'])): ?>
            <div class="detail-section">
                <h3 class="section-title">Equipment Image</h3>
                <div style="text-align: center;">
                    <img src="<?= $equipment['image_url'] ?>" 
                        alt="<?= esc($equipment['name']) ?>" 
                        style="max-width: 100%; max-height: 400px; border-radius: 12px; box-shadow: var(--shadow); border: 3px solid var(--gold);">
                    <?php if (!empty($equipment['thumbnail_url'])): ?>
                        <div style="margin-top: 1rem;">
                            <small style="color: var(--text-muted);">Thumbnail Preview:</small>
                            <br>
                            <img src="<?= $equipment['thumbnail_url'] ?>" 
                                alt="<?= esc($equipment['name']) ?> Thumbnail" 
                                style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-top: 0.5rem; border: 2px solid var(--gold-tint);">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="detail-section">
                <h3 class="section-title">Equipment Image</h3>
                <div style="text-align: center; background: var(--gold-tint); padding: 3rem; border-radius: 12px;">
                    <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;">📷</div>
                    <div style="font-size: 1.1rem; color: var(--text-muted); font-weight: 500;">No Image Available</div>
                    <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.5rem;">
                        Add an image when editing this equipment
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="detail-section">
                <h3 class="section-title">Inventory Status</h3>
                <div class="quantity-grid">
                    <div class="quantity-item quantity-total">
                        <div class="quantity-number"><?= $equipment['total_quantity'] ?></div>
                        <div class="quantity-label">Total Items</div>
                    </div>
                    <div class="quantity-item quantity-available">
                        <div class="quantity-number"><?= $equipment['available_quantity'] ?></div>
                        <div class="quantity-label">Available</div>
                    </div>
                    <div class="quantity-item quantity-in-use">
                        <div class="quantity-number"><?= $equipment['in_use_quantity'] ?></div>
                        <div class="quantity-label">In Use</div>
                    </div>
                    <div class="quantity-item quantity-maintenance">
                        <div class="quantity-number"><?= $equipment['under_maintenance'] ?></div>
                        <div class="quantity-label">Maintenance</div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3 class="section-title">Location & Storage</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Storage Location</span>
                        <span class="info-value"><?= !empty($equipment['location']) ? esc($equipment['location']) : 'Not specified' ?></span>
                    </div>
                </div>
            </div>

            <?php if (!empty($equipment['description'])): ?>
            <div class="detail-section">
                <h3 class="section-title">Description</h3>
                <div class="text-content">
                    <p><?= nl2br(esc($equipment['description'])) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($equipment['specifications'])): ?>
            <div class="detail-section">
                <h3 class="section-title">Specifications & Included Items</h3>
                <div class="text-content">
                    <p><?= nl2br(esc($equipment['specifications'])) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($equipment['notes'])): ?>
            <div class="detail-section">
                <h3 class="section-title">Additional Notes</h3>
                <div class="text-content">
                    <p><?= nl2br(esc($equipment['notes'])) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="<?= base_url('itso/equipment/edit/' . $equipment['equipment_id']) ?>" class="btn-primary">
                    <span>Edit Equipment</span>
                </a>
                <a href="<?= base_url('itso/equipment') ?>" class="btn-secondary">
                    <span>Back to Equipment List</span>
                </a>
            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>