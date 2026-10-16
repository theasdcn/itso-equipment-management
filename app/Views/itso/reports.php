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
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
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

        .reports-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 2rem; }
        .report-card { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); border: 2px solid var(--gold-tint); transition: all 0.3s ease; position: relative; overflow: hidden; }
        .report-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-light); }
        .report-card.active { border-color: var(--success); }
        .report-card.unusable { border-color: var(--danger); }
        .report-card.history { border-color: var(--warning); }
        
        .card-icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
        .card-title { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 1rem; font-size: 1.4rem; border-bottom: 2px solid var(--gold-tint); padding-bottom: 0.5rem; }
        .card-content { color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem; }
        
        .report-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin: 1.5rem 0; }
        .stat-item { text-align: center; padding: 1rem; background: var(--gold-tint); border-radius: var(--radius-input); }
        .stat-value { font-size: 1.5rem; font-weight: 600; color: var(--forest-green); display: block; }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); }
        
        .btn-primary { background: var(--gold); color: var(--forest-green); padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 0.5rem; }
        .btn-primary:hover { background: #c19a2f; transform: translateY(-2px); }
        .btn-secondary { background: var(--forest-green); color: var(--white); padding: 0.8rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-secondary:hover { background: var(--dark-green); transform: translateY(-2px); }

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

        .filter-section { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .filter-title { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 1rem; font-size: 1.2rem; }
        .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end; }
        .form-group { margin-bottom: 0; }
        .form-label { font-size: 0.9rem; font-weight: 500; color: var(--forest-green); margin-bottom: 0.5rem; display: block; }
        .form-input, .form-select { width: 100%; padding: 0.7rem 1rem; border-radius: var(--radius-input); background: #fafafa; border: 1.5px solid #dcdcdc; font-size: 0.9rem; transition: all 0.3s ease; font-family: "Inter", sans-serif; }
        .form-input:focus, .form-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25); outline: none; background: #ffffff; }

        .report-results { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-top: 2rem; display: none; }
        .results-header { display: flex; justify-content: between; align-items: center; margin-bottom: 1rem; }
        .results-title { font-family: "Playfair Display", serif; color: var(--forest-green); font-size: 1.3rem; }
        .results-summary { background: var(--gold-tint); padding: 1rem; border-radius: var(--radius-input); margin-bottom: 1rem; }
        .results-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .results-table th { background: var(--forest-green); color: var(--white); padding: 0.8rem; text-align: left; }
        .results-table td { padding: 0.8rem; border-bottom: 1px solid #eee; }
        .results-table tr:hover { background: #f9f9f9; }

        .loading { display: none; text-align: center; padding: 2rem; }
        .loading-spinner { border: 4px solid #f3f3f3; border-top: 4px solid var(--gold); border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .reports-grid { grid-template-columns: 1fr; }
            .report-stats { grid-template-columns: 1fr; }
            .filter-form { grid-template-columns: 1fr; }
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
            <a href="<?= base_url('itso/returned_equipment') ?>" class="nav-link">Returned Equipment</a>
            <a href="<?= base_url('itso/reports') ?>" class="nav-link active">Reports</a>
            <a href="<?= base_url('itso/about_us') ?>" class="nav-link">About Us</a>
        </div>
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <main class="dashboard-container">
        <div class="page-header">
            <h1 class="page-title">Equipment Management Reports</h1>
            <p class="page-subtitle">Generate comprehensive reports for equipment tracking and user activity</p>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Report Filters</h3>
            <form class="filter-form" id="reportFilters" method="get">
                <div class="form-group">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" name="date_range" id="date_range">
                        <option value="today" <?= ($filters['date_range'] ?? 'week') === 'today' ? 'selected' : '' ?>>Today</option>
                        <option value="week" <?= ($filters['date_range'] ?? 'week') === 'week' ? 'selected' : '' ?>>This Week</option>
                        <option value="month" <?= ($filters['date_range'] ?? 'week') === 'month' ? 'selected' : '' ?>>This Month</option>
                        <option value="quarter" <?= ($filters['date_range'] ?? 'week') === 'quarter' ? 'selected' : '' ?>>This Quarter</option>
                        <option value="year" <?= ($filters['date_range'] ?? 'week') === 'year' ? 'selected' : '' ?>>This Year</option>
                        <option value="custom" <?= ($filters['date_range'] ?? 'week') === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category" id="category">
                        <option value="">All Categories</option>
                        <option value="Laptops" <?= ($filters['category'] ?? '') === 'Laptops' ? 'selected' : '' ?>>Laptops</option>
                        <option value="DLP Projectors" <?= ($filters['category'] ?? '') === 'DLP Projectors' ? 'selected' : '' ?>>DLP Projectors</option>
                        <option value="Cables & Adapters" <?= ($filters['category'] ?? '') === 'Cables & Adapters' ? 'selected' : '' ?>>Cables & Adapters</option>
                        <option value="Input Devices" <?= ($filters['category'] ?? '') === 'Input Devices' ? 'selected' : '' ?>>Input Devices</option>
                        <option value="Drawing Tablets" <?= ($filters['category'] ?? '') === 'Drawing Tablets' ? 'selected' : '' ?>>Drawing Tablets</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <select class="form-select" name="department" id="department">
                        <option value="">All Departments</option>
                        <option value="Computer Science" <?= ($filters['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Studies</option>
                        <option value="Engineering" <?= ($filters['department'] ?? '') === 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                        <option value="Multimedia Arts" <?= ($filters['department'] ?? '') === 'Multimedia Arts' ? 'selected' : '' ?>>Multimedia Arts</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn-primary" onclick="applyFilters()">
                        <span>Apply Filters</span>
                    </button>
                    <button type="button" class="btn-secondary" onclick="resetFilters()">
                        <span>Reset</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="loading" id="loadingIndicator">
            <div class="loading-spinner"></div>
            <p>Generating report...</p>
        </div>

        <div class="report-results" id="reportResults">
            <div class="results-header">
                <h3 class="results-title" id="resultsTitle">Report Results</h3>
            </div>
            <div class="results-summary" id="resultsSummary"></div>
            <div class="results-content" id="resultsContent"></div>
        </div>

        <div class="reports-grid">
            
            <div class="report-card active">
                <div class="card-icon">📊</div>
                <h3 class="card-title">Active Equipment List Report</h3>
                <div class="card-content">
                    <p>Generate a comprehensive list of all active equipment in the inventory system. This report includes detailed information about available equipment, their specifications, and current status.</p>
                    
                    <div class="report-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= $active_equipment_count ?? '0' ?></span>
                            <span class="stat-label">Active Items</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $available_count ?? '0' ?></span>
                            <span class="stat-label">Available Now</span>
                        </div>
                    </div>

                    <p><strong>Report Includes:</strong></p>
                    <ul style="margin: 0.5rem 0; padding-left: 1.2rem;">
                        <li>Equipment details and specifications</li>
                        <li>Current availability status</li>
                        <li>Location and category information</li>
                        <li>Quantity and usage statistics</li>
                        <li>Last maintenance dates</li>
                    </ul>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn-primary" onclick="generateReport('active')">
                        <span>📄 Generate Report</span>
                    </button>
                </div>
            </div>

            <div class="report-card unusable">
                <div class="card-icon">⚠️</div>
                <h3 class="card-title">Unusable Equipment Report</h3>
                <div class="card-content">
                    <p>Identify and track equipment that is currently unusable, under maintenance, or requires replacement. This report helps in maintenance planning and budget allocation.</p>
                    
                    <div class="report-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= $unusable_count ?? '0' ?></span>
                            <span class="stat-label">Unusable Items</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $maintenance_count ?? '0' ?></span>
                            <span class="stat-label">Under Maintenance</span>
                        </div>
                    </div>

                    <p><strong>Report Includes:</strong></p>
                    <ul style="margin: 0.5rem 0; padding-left: 1.2rem;">
                        <li>Equipment requiring maintenance</li>
                        <li>Damaged or faulty equipment</li>
                        <li>Replacement recommendations</li>
                        <li>Maintenance cost estimates</li>
                        <li>Repair timeline and status</li>
                    </ul>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn-primary" onclick="generateReport('unusable')">
                        <span>📄 Generate Report</span>
                    </button>
                </div>
            </div>

            <div class="report-card history">
                <div class="card-icon">👥</div>
                <h3 class="card-title">User Borrowing History</h3>
                <div class="card-content">
                    <p>Track and analyze user borrowing patterns, frequency, and equipment usage. This report provides insights into user behavior and equipment utilization rates.</p>
                    
                    <div class="report-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= $active_borrows ?? '0' ?></span>
                            <span class="stat-label">Active Borrows</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $total_users ?? '0' ?></span>
                            <span class="stat-label">Active Users</span>
                        </div>
                    </div>

                    <p><strong>Report Includes:</strong></p>
                    <ul style="margin: 0.5rem 0; padding-left: 1.2rem;">
                        <li>Individual user borrowing history</li>
                        <li>Most frequently borrowed equipment</li>
                        <li>Borrowing patterns and trends</li>
                        <li>Overdue equipment tracking</li>
                        <li>Department-wise usage statistics</li>
                    </ul>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn-primary" onclick="generateReport('borrowing')">
                        <span>📄 Generate Report</span>
                    </button>
                </div>
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
                <p>Contact: itso-admin@fit.edu.ph</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> ITSO Equipment Management System. Administrator Access.</p>
            </div>
        </div>
    </footer>

    <script>
        function applyFilters() {
            const formData = new FormData(document.getElementById('reportFilters'));
            const params = new URLSearchParams(formData);
            
            document.querySelectorAll('.btn-primary, .btn-secondary').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<span>⏳ Applying Filters...</span>';
            });
            
            window.location.href = '<?= base_url('itso/reports') ?>?' + params.toString();
        }

        function resetFilters() {
            document.getElementById('reportFilters').reset();
            window.location.href = '<?= base_url('itso/reports') ?>';
        }

        async function generateReport(type) {
            const formData = new FormData(document.getElementById('reportFilters'));
            const params = new URLSearchParams(formData);

            showLoading();
            hideResults();
            
            try {
                let endpoint;
                const reportNames = {
                    'active': 'Active Equipment List',
                    'unusable': 'Unusable Equipment Report',
                    'borrowing': 'User Borrowing History'
                };
                
                switch(type) {
                    case 'active':
                        endpoint = '<?= base_url('itso/generate-active-equipment-report') ?>';
                        break;
                    case 'unusable':
                        endpoint = '<?= base_url('itso/generate-unusable-equipment-report') ?>';
                        break;
                    case 'borrowing':
                        endpoint = '<?= base_url('itso/generate-borrowing-history-report') ?>';
                        break;
                    default:
                        throw new Error('Invalid report type');
                }
                
                const response = await fetch(endpoint + '?' + params.toString());
                const result = await response.json();
                
                if (result.success) {
                    displayReportResults(type, result);
                } else {
                    throw new Error('Failed to generate report');
                }
                
            } catch (error) {
                console.error('Error generating report:', error);
                alert('Failed to generate report: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function displayReportResults(type, result) {
            const reportNames = {
                'active': 'Active Equipment List Report',
                'unusable': 'Unusable Equipment Report',
                'borrowing': 'User Borrowing History Report'
            };
            
            document.getElementById('resultsTitle').textContent = reportNames[type];
            
            const summary = document.getElementById('resultsSummary');
            summary.innerHTML = `<strong>Report Summary:</strong> ${getSummaryText(type, result.summary)}`;
            
            const content = document.getElementById('resultsContent');
            content.innerHTML = generateTableHTML(type, result.data);
            
            showResults();
        }

        function getSummaryText(type, summary) {
            switch(type) {
                case 'active':
                    return `Total Items: ${summary.total_items} | Total Quantity: ${summary.total_quantity} | Available: ${summary.available_quantity}`;
                case 'unusable':
                    return `Total Items: ${summary.total_items} | Under Maintenance: ${summary.maintenance_count} | Unavailable: ${summary.unavailable_count}`;
                case 'borrowing':
                    return `Total Records: ${summary.total_records} | Active Borrows: ${summary.active_borrows} | Returned Items: ${summary.returned_items}`;
                default:
                    return '';
            }
        }

        function generateTableHTML(type, data) {
            if (data.length === 0) {
                return '<p>No data found for the selected filters.</p>';
            }

            let headers = '';
            let rows = '';

            switch(type) {
                case 'active':
                    headers = '<tr><th>Equipment Code</th><th>Name</th><th>Category</th><th>Total Qty</th><th>Available Qty</th><th>Status</th></tr>';
                    data.forEach(item => {
                        rows += `<tr>
                            <td>${item.equipment_code || 'N/A'}</td>
                            <td>${item.name}</td>
                            <td>${item.category}</td>
                            <td>${item.total_quantity}</td>
                            <td>${item.available_quantity}</td>
                            <td>${item.status}</td>
                        </tr>`;
                    });
                    break;
                    
                case 'unusable':
                    headers = '<tr><th>Equipment Code</th><th>Name</th><th>Category</th><th>Status</th><th>Under Maintenance</th><th>Last Updated</th></tr>';
                    data.forEach(item => {
                        rows += `<tr>
                            <td>${item.equipment_code || 'N/A'}</td>
                            <td>${item.name}</td>
                            <td>${item.category}</td>
                            <td>${item.status}</td>
                            <td>${item.under_maintenance || 0}</td>
                            <td>${formatDate(item.updated_at)}</td>
                        </tr>`;
                    });
                    break;
                    
                case 'borrowing':
                    headers = '<tr><th>User</th><th>Equipment</th><th>Category</th><th>Borrow Date</th><th>Expected Return</th><th>Status</th><th>Approved By</th></tr>';
                    data.forEach(item => {
                        rows += `<tr>
                            <td>${item.first_name} ${item.last_name} (${item.role})</td>
                            <td>${item.equipment_name}</td>
                            <td>${item.category}</td>
                            <td>${formatDate(item.borrow_date)}</td>
                            <td>${formatDate(item.expected_return_date)}</td>
                            <td>${item.status}</td>
                            <td>${item.approved_first_name ? item.approved_first_name + ' ' + item.approved_last_name : 'N/A'}</td>
                        </tr>`;
                    });
                    break;
            }

            return `<table class="results-table">${headers}${rows}</table>`;
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString();
        }

        function showLoading() {
            document.getElementById('loadingIndicator').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').style.display = 'none';
        }

        function showResults() {
            document.getElementById('reportResults').style.display = 'block';
        }

        function hideResults() {
            document.getElementById('reportResults').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Reports page loaded successfully');
            hideResults();
        });
    </script>
</body>
</html>