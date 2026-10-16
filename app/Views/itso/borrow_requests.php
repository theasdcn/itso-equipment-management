<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
            --beige-bg: #F7F3E8;
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
            grid-template-columns: repeat(4, 1fr); 
            gap: 1rem; 
            margin-bottom: 2rem; 
        }
        .stat-card { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); text-align: center; border-top: 4px solid var(--gold); }
        .stat-number { font-size: 2rem; font-weight: 700; color: var(--forest-green); margin-bottom: 0.5rem; }
        .stat-label { font-size: 0.9rem; color: var(--text-muted); }

        .filter-section { background: var(--white); padding: 1.5rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .filter-title { font-family: "Playfair Display", serif; color: var(--forest-green); margin-bottom: 1rem; font-size: 1.2rem; }
        .filter-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
        .filter-btn { padding: 0.7rem 1.5rem; border: 2px solid var(--gold-tint); background: var(--white); color: var(--forest-green); border-radius: var(--radius-input); text-decoration: none; font-weight: 500; transition: all 0.3s ease; cursor: pointer; }
        .filter-btn.active, .filter-btn:hover { background: var(--gold); color: var(--forest-green); border-color: var(--gold); }

        .requests-table-container { background: var(--white); padding: 2rem; border-radius: var(--radius-card); box-shadow: var(--shadow); margin-bottom: 2rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--forest-green); color: var(--gold-tint); padding: 1rem; text-align: left; font-weight: 600; }
        .table td { padding: 1rem; border-bottom: 1px solid var(--gold-tint); }
        .table tr:hover { background: var(--gold-tint); }

        .user-badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .user-student { background: #e3f2fd; color: #1976d2; }
        .user-associate { background: #e8f5e8; color: #2e7d32; }
        .user-itso { background: #fff3e0; color: #f57c00; }

        .status-badge { padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-pending { background: #fff3e0; color: #f57c00; }
        .status-approved { background: #e8f5e8; color: #2e7d32; }
        .status-rejected { background: #ffebee; color: #c62828; }
        .status-borrowed { background: #e3f2fd; color: #1976d32; }
        .status-returned { background: #e8f5e8; color: #2e7d32; }

        .action-buttons { display: flex; gap: 0.5rem; }
        .btn { padding: 0.5rem 1rem; border-radius: var(--radius-input); text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-approve { background: #28a745; color: white; }
        .btn-approve:hover { background: #218838; }
        .btn-reject { background: #dc3545; color: white; }
        .btn-reject:hover { background: #c82333; }
        .btn-complete { background: #17a2b8; color: white; }
        .btn-complete:hover { background: #138496; }

        .nav-header { background: var(--forest-green); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-link { color: var(--gold-tint); text-decoration: none; padding: 0.7rem 1.2rem; border-radius: var(--radius-input); transition: all 0.3s ease; font-weight: 500; }
        .nav-link.active { background: var(--gold); color: var(--forest-green); }
        .nav-link:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); }
        .btn-logout { background: var(--gold); color: var(--forest-green); padding: 0.7rem 1.5rem; border-radius: var(--radius-input); text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-family: inherit; }
        .btn-logout:hover { background: #c19a2f; transform: translateY(-2px); }

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

        @media (max-width: 768px) {
            .dashboard-container { padding: 1rem; }
            .nav-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-links { flex-direction: column; gap: 0.5rem; width: 100%; }
            .nav-link { text-align: center; width: 100%; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .filter-buttons { flex-direction: column; }
            .table { display: block; overflow-x: auto; }
            .action-buttons { flex-direction: column; }
            .pagination { flex-direction: column; gap: 1rem; text-align: center; }
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
            <a href="<?= base_url('itso/borrow-requests') ?>" class="nav-link active">Borrow Requests</a>
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
            <h1 class="page-title">Borrow Requests Management</h1>
            <p class="page-subtitle">Manage and approve equipment borrowing requests from users</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $borrowStats['pending_requests'] ?? 0 ?></div>
                <div class="stat-label">Pending Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $borrowStats['approved_requests'] ?? 0 ?></div>
                <div class="stat-label">Approved Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $borrowStats['active_borrows'] ?? 0 ?></div>
                <div class="stat-label">Active Borrows</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $borrowStats['overdue_borrows'] ?? 0 ?></div>
                <div class="stat-label">Overdue Borrows</div>
            </div>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Filter Requests</h3>
            <div class="filter-buttons">
                <a href="<?= base_url('itso/borrow-requests') ?>" class="filter-btn <?= empty($statusFilter) ? 'active' : '' ?>">All Requests</a>
                <a href="<?= base_url('itso/borrow-requests?status=pending') ?>" class="filter-btn <?= ($statusFilter ?? '') === 'pending' ? 'active' : '' ?>">Pending</a>
                <a href="<?= base_url('itso/borrow-requests?status=approved') ?>" class="filter-btn <?= ($statusFilter ?? '') === 'approved' ? 'active' : '' ?>">Approved</a>
                <a href="<?= base_url('itso/borrow-requests?status=borrowed') ?>" class="filter-btn <?= ($statusFilter ?? '') === 'borrowed' ? 'active' : '' ?>">Borrowed</a>
                <a href="<?= base_url('itso/borrow-requests?status=returned') ?>" class="filter-btn <?= ($statusFilter ?? '') === 'returned' ? 'active' : '' ?>">Returned</a>
                <a href="<?= base_url('itso/borrow-requests?status=rejected') ?>" class="filter-btn <?= ($statusFilter ?? '') === 'rejected' ? 'active' : '' ?>">Rejected</a>
            </div>
        </div>

        <div class="requests-table-container">
            <?php if (!empty($borrowRequests)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Request Date</th>
                            <th>Expected Return</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrowRequests as $request): ?>
                            <tr>
                                <td>#BR-<?= $request['borrow_id'] ?></td>
                                <td>
                                    <?= esc($request['first_name']) ?> <?= esc($request['last_name']) ?>
                                    <span class="user-badge user-<?= $request['role'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $request['role'])) ?>
                                    </span>
                                </td>
                                <td><?= esc($request['equipment_name']) ?> (<?= esc($request['equipment_code']) ?>)</td>
                                <td><?= date('M j, Y g:i A', strtotime($request['borrow_date'])) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($request['expected_return_date'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $request['status'] ?>">
                                        <?= ucfirst($request['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($request['status'] === 'pending'): ?>
                                            
                                            <form action="<?= base_url('itso/borrow-requests/approve/' . $request['borrow_id']) ?>" method="post" style="display: inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this borrow request?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-reject" onclick="showRejectModal(<?= $request['borrow_id'] ?>)">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                            
                                        <?php elseif ($request['status'] === 'approved' || $request['status'] === 'borrowed'): ?>
                                            
                                            <form action="<?= base_url('itso/borrow-requests/complete/' . $request['borrow_id']) ?>" method="post" style="display: inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-complete" onclick="return confirm('Are you sure you want to mark this equipment as returned?')">
                                                    <i class="fas fa-check-circle"></i> Complete
                                                </button>
                                            </form>
                                            
                                        <?php elseif ($request['status'] === 'returned'): ?>
                                            <span class="status-badge status-returned">Completed</span>
                                        <?php elseif ($request['status'] === 'rejected'): ?>
                                            <span class="status-badge status-rejected">Rejected</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>No borrow requests found</h3>
                    <p>There are no borrow requests matching your current filter.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Page <?= $currentPage ?? 1 ?> of <?= $totalPages ?? 1 ?> | Showing <?= $startItem ?? 1 ?> to <?= $endItem ?? count($borrowRequests ?? []) ?> of <?= $totalItems ?? count($borrowRequests ?? []) ?> requests
            </div>
            <div class="pagination-controls">
                <?php if (($currentPage ?? 1) > 1): ?>
                    <a href="<?= base_url('itso/borrow-requests?page=' . (($currentPage ?? 1) - 1) . (!empty($statusFilter) ? '&status=' . $statusFilter : '')) ?>" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php else: ?>
                    <span class="pagination-btn" disabled>
                        <i class="fas fa-chevron-left"></i> Previous
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="<?= base_url('itso/borrow-requests?page=' . $i . (!empty($statusFilter) ? '&status=' . $statusFilter : '')) ?>" class="pagination-btn <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="<?= base_url('itso/borrow-requests?page=' . (($currentPage ?? 1) + 1) . (!empty($statusFilter) ? '&status=' . $statusFilter : '')) ?>" class="pagination-btn">
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

    <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: var(--radius-card); width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 1rem; color: var(--forest-green);">Reject Borrow Request</h3>
            <form id="rejectForm" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="borrow_id" id="rejectBorrowId">
                <div style="margin: 1rem 0;">
                    <label for="rejectReason" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Reason for rejection:</label>
                    <textarea id="rejectReason" name="reason" rows="4" style="width: 100%; padding: 0.8rem; border: 1px solid var(--gold-tint); border-radius: var(--radius-input); font-family: inherit;" placeholder="Please provide a reason for rejecting this request..." required></textarea>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="hideRejectModal()" class="btn" style="background: var(--text-muted); color: white;">Cancel</button>
                    <button type="submit" class="btn btn-reject">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                </div>
            </form>
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
        function showRejectModal(borrowId) {
            document.getElementById('rejectBorrowId').value = borrowId;
            document.getElementById('rejectForm').action = '<?= base_url('itso/borrow-requests/reject/') ?>' + borrowId;
            document.getElementById('rejectModal').style.display = 'block';
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectReason').value = '';
        }

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });

        <?php if (($statusFilter ?? '') === 'pending'): ?>
        setInterval(() => {
            console.log('Checking for new pending requests...');
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>