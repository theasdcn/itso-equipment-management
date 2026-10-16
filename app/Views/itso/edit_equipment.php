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

        .add-form { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { font-size: 0.95rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; display: block; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 0.9rem 1rem; border-radius: var(--radius-input); background: #fafafa; border: 1.5px solid #dcdcdc; font-size: 1rem; transition: all 0.3s ease; font-family: "Inter", sans-serif; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25); outline: none; background: #ffffff; }
        .form-textarea { min-height: 100px; resize: vertical; }
        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; }
        .form-help { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem; }

        .btn-primary, .btn-secondary { padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 0.5rem; }
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
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }

        .quantity-warning { 
            background: #fff3cd; 
            border: 1px solid #ffeaa7; 
            color: #856404; 
            padding: 0.75rem; 
            border-radius: var(--radius-input); 
            margin-top: 0.5rem;
            font-size: 0.85rem;
            display: none;
        }

        .image-upload-container { margin-bottom: 1.5rem; }
        .image-upload-label { font-size: 0.95rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; display: block; }
        .image-upload-area { border: 2px dashed #dcdcdc; border-radius: var(--radius-input); padding: 2rem; text-align: center; transition: all 0.3s ease; background: #fafafa; cursor: pointer; }
        .image-upload-area:hover { border-color: var(--gold); background: #f7f7f7; }
        .image-upload-area.dragover { border-color: var(--gold); background: var(--gold-tint); }
        .image-upload-icon { font-size: 2.5rem; color: var(--text-muted); margin-bottom: 0.5rem; }
        .image-upload-text { color: var(--text-muted); margin-bottom: 0.5rem; }
        .image-upload-btn { background: var(--forest-green); color: white; padding: 0.6rem 1.2rem; border-radius: var(--radius-input); border: none; cursor: pointer; font-weight: 500; transition: all 0.3s ease; }
        .image-upload-btn:hover { background: var(--dark-green); }
        .image-upload-input { display: none; }
        .image-preview-container { margin-top: 1rem; display: none; }
        .image-preview { max-width: 200px; max-height: 200px; border-radius: var(--radius-input); box-shadow: var(--shadow-light); }
        .image-preview-actions { margin-top: 0.5rem; display: flex; gap: 0.5rem; }
        .image-preview-remove { background: #dc3545; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-input); cursor: pointer; font-size: 0.8rem; }
        .image-preview-remove:hover { background: #c82333; }
        .current-image-preview { text-align: center; }
        .current-image-preview img { border: 2px solid var(--gold); }

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
        
        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">Edit Equipment</h1>
            <p class="page-subtitle">Update equipment information in the inventory system</p>
        </div>

        <div class="add-form">
            <form action="<?= base_url('itso/equipment/update/' . ($equipment['equipment_id'] ?? '')) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Equipment Code *</label>
                    <input type="text" name="equipment_code" class="form-input" placeholder="EQ123456" value="<?= old('equipment_code', $equipment['equipment_code'] ?? '') ?>" required>
                    <div class="form-help">Unique identifier for this equipment item.</div>
                </div>

                <div class="form-group image-upload-container">
                    <label class="form-label">Equipment Image</label>

                    <?php if (!empty($equipment['image_url'])): ?>
                        <div class="current-image-preview" style="margin-bottom: 1rem;">
                            <p class="form-help">Current Image:</p>
                            <img src="<?= $equipment['image_url'] ?>" alt="Current Image" class="image-preview">
                        </div>
                    <?php endif; ?>
                    
                    <div class="image-upload-area" id="imageUploadArea">
                        <div class="image-upload-icon">📷</div>
                        <div class="image-upload-text">Drag & drop an image here or click to browse</div>
                        <button type="button" class="image-upload-btn" id="imageUploadBtn">Select Image</button>
                        <input type="file" name="equipment_image" id="imageUploadInput" class="image-upload-input" accept="image/*">
                    </div>
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <img src="" alt="Image Preview" class="image-preview" id="imagePreview">
                        <div class="image-preview-actions">
                            <button type="button" class="image-preview-remove" id="imagePreviewRemove">Remove Image</button>
                        </div>
                    </div>
                    <div class="form-help">Recommended: JPG, PNG, or WebP format. Max file size: 2MB.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Equipment Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g., Dell Laptop, DLP Projector, HDMI Cable" value="<?= old('name', $equipment['name'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category ?>" <?= (old('category', $equipment['category'] ?? '') === $category) ? 'selected' : '' ?>>
                                    <?= $category ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Quantity *</label>
                        <input type="number" name="total_quantity" class="form-input" placeholder="e.g., 5, 10, 25" value="<?= old('total_quantity', $equipment['total_quantity'] ?? '') ?>" min="1" required>
                        <div class="form-help">Total number of items in inventory.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Available Quantity *</label>
                    <input type="number" name="available_quantity" class="form-input" value="<?= old('available_quantity', $equipment['available_quantity'] ?? '') ?>" min="0" required>
                    <div class="form-help">Number of items currently available for borrowing.</div>
                    <div class="quantity-warning" id="quantityWarning">
                        ⚠️ Available quantity exceeds total quantity. This may indicate items are missing or unaccounted for.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" placeholder="Brief description of the equipment..."><?= old('description', $equipment['description'] ?? '') ?></textarea>
                    <div class="form-help">Describe what this equipment is used for and its main features.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Specifications</label>
                    <textarea name="specifications" class="form-textarea" placeholder="Technical specifications, included accessories..."><?= old('specifications', $equipment['specifications'] ?? '') ?></textarea>
                    <div class="form-help">
                        List specifications and included items. Examples:<br>
                        • Laptops: Include charger<br>
                        • DLP Projectors: Include extension cord, VGA/HDMI cable, power cable<br>
                        • Input Devices: Include lightning cable for Mac lab<br>
                        • Drawing Tablets: Include pen
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-input" placeholder="e.g., Lab Room A, Storage Cabinet B" value="<?= old('location', $equipment['location'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available" <?= (old('status', $equipment['status'] ?? '') === 'available') ? 'selected' : '' ?>>Available</option>
                            <option value="maintenance" <?= (old('status', $equipment['status'] ?? '') === 'maintenance') ? 'selected' : '' ?>>Under Maintenance</option>
                            <option value="unavailable" <?= (old('status', $equipment['status'] ?? '') === 'unavailable') ? 'selected' : '' ?>>Unavailable</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" class="form-textarea" placeholder="Any additional information, maintenance notes, or special instructions..."><?= old('notes', $equipment['notes'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <span>Update Equipment</span>
                    </button>
                    <a href="<?= base_url('itso/equipment') ?>" class="btn-secondary">Cancel</a>
                    <?php $equipmentId = $equipment['equipment_id'] ?? ''; ?>
                    <?php if (!empty($equipmentId)): ?>
                    <a href="<?= base_url('itso/equipment/delete/' . $equipmentId) ?>" class="btn-secondary" onclick="return confirm('Are you sure you want to delete this equipment? This action cannot be undone.')" style="background: #dc3545; margin-left: auto;">Delete Equipment</a>
                    <?php endif; ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formInputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
            
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

            const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            const totalQuantityInput = document.querySelector('input[name="total_quantity"]');
            const availableQuantityInput = document.querySelector('input[name="available_quantity"]');
            const quantityWarning = document.getElementById('quantityWarning');
            
            function checkQuantity() {
                const totalQty = parseInt(totalQuantityInput.value) || 0;
                const availableQty = parseInt(availableQuantityInput.value) || 0;
                
                if (availableQty > totalQty) {
                    quantityWarning.style.display = 'block';
                    availableQuantityInput.style.borderColor = '#ffc107';
                } else {
                    quantityWarning.style.display = 'none';
                    availableQuantityInput.style.borderColor = '#dcdcdc';
                }
            }
            
            if (totalQuantityInput && availableQuantityInput) {
                totalQuantityInput.addEventListener('input', checkQuantity);
                availableQuantityInput.addEventListener('input', checkQuantity);
                
                checkQuantity();
            }

            const imageUploadArea = document.getElementById('imageUploadArea');
            const imageUploadBtn = document.getElementById('imageUploadBtn');
            const imageUploadInput = document.getElementById('imageUploadInput');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewRemove = document.getElementById('imagePreviewRemove');

            imageUploadArea.addEventListener('click', function(e) {
                if (e.target !== imageUploadBtn) {
                    imageUploadInput.click();
                }
            });
            
            imageUploadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                imageUploadInput.click();
            });

            imageUploadInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPG, PNG, or WebP).');
                        this.value = '';
                        return;
                    }
                    
                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('File size must be less than 2MB.');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            imageUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            imageUploadArea.addEventListener('dragleave', function() {
                this.classList.remove('dragover');
            });
            
            imageUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    imageUploadInput.files = e.dataTransfer.files;
                    const event = new Event('change');
                    imageUploadInput.dispatchEvent(event);
                }
            });

            imagePreviewRemove.addEventListener('click', function() {
                imageUploadInput.value = '';
                imagePreviewContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>