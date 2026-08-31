<?php
use App\Core\Helper;
?>

<!-- Dashboard Header -->
<div class="page-header">
    <div class="page-title-wrap">
        <h1>Dashboard Overview</h1>
        <div class="page-subtitle">Welcome back, Admin User! 👋</div>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal('quickActionModal')">
            <span>Quick Actions</span>
            <i class="ph ph-caret-down"></i>
        </button>
    </div>
</div>

<!-- Top 4 KPI Metric Cards -->
<div class="stats-grid-4">
    <!-- Total Inventory Items -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <i class="ph ph-cube"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Inventory Items</div>
                <div class="stat-value">1,250</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">
                <i class="ph ph-trend-up"></i> +12 this week
            </div>
            <svg width="70" height="24" viewBox="0 0 70 24" fill="none">
                <path d="M2 18L18 14L34 20L50 6L68 2" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <!-- Trucks Today -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange">
                <i class="ph ph-truck"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Trucks Today</div>
                <div class="stat-value">25</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">
                18 Loaded
            </div>
            <svg width="70" height="24" viewBox="0 0 70 24" fill="none">
                <path d="M2 20L20 18L38 10L52 14L68 6" stroke="#F97316" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <!-- Today's Billing -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <i class="ph ph-currency-inr"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Today's Billing</div>
                <div class="stat-value">₹2,50,000</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">
                <i class="ph ph-trend-up"></i> +8.5% vs yesterday
            </div>
            <svg width="70" height="24" viewBox="0 0 70 24" fill="none">
                <path d="M2 22L16 16L32 18L48 8L68 4" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <!-- Total Employees -->
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <i class="ph ph-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Employees</div>
                <div class="stat-value">150</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">
                10 On Leave
            </div>
            <svg width="70" height="24" viewBox="0 0 70 24" fill="none">
                <path d="M2 16L20 18L36 12L52 16L68 4" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
</div>

<!-- 6 Module Navigation Cards -->
<div class="modules-grid-6">
    <a href="/inventory" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #2563EB;">
                <i class="ph ph-cube"></i>
            </div>
            <div class="module-card-title">Inventory</div>
            <div class="module-card-desc">Manage stock, items, in/out and reports.</div>
        </div>
        <div class="module-card-link">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>

    <a href="/truck-loading" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #F97316;">
                <i class="ph ph-truck"></i>
            </div>
            <div class="module-card-title">Truck Loading</div>
            <div class="module-card-desc">Manage trucks, loading, unloading and status.</div>
        </div>
        <div class="module-card-link" style="color: #F97316;">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>

    <a href="/billing" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #10B981;">
                <i class="ph ph-file-text"></i>
            </div>
            <div class="module-card-title">Billing</div>
            <div class="module-card-desc">Create invoices, manage bills and payments.</div>
        </div>
        <div class="module-card-link" style="color: #10B981;">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>

    <a href="/hrms" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #8B5CF6;">
                <i class="ph ph-users"></i>
            </div>
            <div class="module-card-title">HRMS</div>
            <div class="module-card-desc">Manage employees, leave, attendance and more.</div>
        </div>
        <div class="module-card-link" style="color: #8B5CF6;">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>

    <a href="/payroll" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #06B6D4;">
                <i class="ph ph-currency-inr"></i>
            </div>
            <div class="module-card-title">Payroll</div>
            <div class="module-card-desc">Manage salaries, bonuses, deductions and payslips.</div>
        </div>
        <div class="module-card-link" style="color: #06B6D4;">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>

    <a href="/notifications" class="module-card">
        <div>
            <div class="module-card-icon" style="background: #EF4444;">
                <i class="ph ph-bell-ringing"></i>
            </div>
            <div class="module-card-title">Payment Notification</div>
            <div class="module-card-desc">Track payments, notifications and reminders.</div>
        </div>
        <div class="module-card-link" style="color: #EF4444;">
            <span>Open</span>
            <i class="ph ph-arrow-right"></i>
        </div>
    </a>
</div>

<!-- Dashboard 3 Columns Section -->
<div class="dashboard-columns">
    <!-- Column 1: Business Overview Chart & Net Profit -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Business Overview</div>
            <select class="form-control" style="width: auto; padding: 4px 10px; font-size: 12px;">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Quarter</option>
            </select>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 16px; font-size: 12px; font-weight: 600; margin-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="display: inline-block; width: 14px; height: 3px; background: #2563EB; border-radius: 2px;"></span>
                    <span style="color: var(--text-muted);">Billing (₹)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="display: inline-block; width: 14px; height: 3px; background: #EF4444; border-radius: 2px;"></span>
                    <span style="color: var(--text-muted);">Expenses (₹)</span>
                </div>
            </div>
            
            <div style="position: relative; width: 100%;">
                <canvas id="businessChart"></canvas>
            </div>

            <!-- Bottom 3 Financial KPI Pills -->
            <div class="biz-stats-pills">
                <div class="biz-pill">
                    <div class="biz-pill-label">Total Billing</div>
                    <div class="biz-pill-val">₹18,50,000</div>
                    <div class="biz-pill-growth"><i class="ph ph-trend-up"></i> +15.3%</div>
                </div>
                <div class="biz-pill">
                    <div class="biz-pill-label">Total Expenses</div>
                    <div class="biz-pill-val">₹8,20,000</div>
                    <div class="biz-pill-growth" style="color: var(--warning);"><i class="ph ph-trend-up"></i> +10.2%</div>
                </div>
                <div class="biz-pill">
                    <div class="biz-pill-label">Net Profit</div>
                    <div class="biz-pill-val">₹10,30,000</div>
                    <div class="biz-pill-growth"><i class="ph ph-trend-up"></i> +18.6%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column 2: Recent Activities -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Activities</div>
            <a href="/inventory" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <div class="activity-list">
                <?php foreach ($recentActivities as $act): ?>
                    <div class="activity-item">
                        <div class="activity-icon <?= htmlspecialchars($act['color'] ?? 'color-green') ?>">
                            <i class="ph <?= htmlspecialchars($act['icon'] ?? 'ph-check') ?>"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title"><?= htmlspecialchars($act['title']) ?></div>
                            <?php if (!empty($act['description'])): ?>
                                <div class="activity-desc"><?= htmlspecialchars($act['description']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="activity-time"><?= htmlspecialchars($act['time_label']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Column 3: Pending Approvals & Payment Notifications -->
    <div>
        <!-- Pending Approvals (1-Time Approval) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pending Approvals <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">(1-Time Approval)</span></div>
                <a href="/approvals" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Requested By</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendingApprovals)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                        <i class="ph ph-check-circle" style="color: var(--success); font-size: 20px;"></i> All requests approved!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($pendingApprovals, 0, 3) as $appr): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;"><?= htmlspecialchars($appr['request_type']) ?></div>
                                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($appr['reference_no']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 500;"><?= htmlspecialchars($appr['requested_by']) ?></div>
                                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($appr['department']) ?></div>
                                        </td>
                                        <td>
                                            <?= $appr['amount'] ? Helper::formatCurrency($appr['amount']) : '—' ?>
                                        </td>
                                        <td><?= htmlspecialchars($appr['date']) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action-approve" title="Approve" onclick="openApproveModal(<?= $appr['id'] ?>, '<?= htmlspecialchars($appr['reference_no']) ?>', '<?= htmlspecialchars($appr['request_type']) ?>', '<?= htmlspecialchars($appr['requested_by']) ?>')">
                                                    <i class="ph ph-check"></i>
                                                </button>
                                                <button class="btn-action-reject" title="Reject" onclick="openRejectModal(<?= $appr['id'] ?>, '<?= htmlspecialchars($appr['reference_no']) ?>', '<?= htmlspecialchars($appr['request_type']) ?>', '<?= htmlspecialchars($appr['requested_by']) ?>')">
                                                    <i class="ph ph-x"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div style="padding: 12px 18px; font-size: 11.5px; color: var(--primary); background: #EFF6FF; border-top: 1px solid #DBEAFE; display: flex; align-items: center; gap: 6px;">
                    <i class="ph ph-info"></i> 1-time approval: Once approved/rejected, request cannot be edited.
                </div>
            </div>
        </div>

        <!-- Payment Notifications Widget -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Payment Notifications</div>
                <a href="/notifications" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body">
                <?php foreach ($notifications as $notif): ?>
                    <div class="notif-item">
                        <div class="notif-left">
                            <div class="notif-icon">
                                <?php if ($notif['status'] === 'Received'): ?>
                                    <i class="ph ph-check-circle" style="color: var(--success);"></i>
                                <?php elseif ($notif['status'] === 'Overdue'): ?>
                                    <i class="ph ph-warning-circle" style="color: var(--danger);"></i>
                                <?php else: ?>
                                    <i class="ph ph-clock" style="color: var(--warning);"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="notif-title"><?= htmlspecialchars($notif['title']) ?></div>
                                <div class="notif-sub"><?= htmlspecialchars($notif['subtitle']) ?></div>
                            </div>
                        </div>
                        <div class="notif-right">
                            <div class="notif-time"><?= htmlspecialchars($notif['time_str'] ?? 'Today') ?></div>
                            <?= Helper::getStatusBadge($notif['status']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Modal -->
<div id="quickActionModal" class="modal-backdrop">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title"><i class="ph ph-lightning"></i> Quick Actions</h3>
            <button type="button" class="modal-close" onclick="closeModal('quickActionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <a href="/truck-loading" class="btn btn-secondary" style="justify-content: flex-start; padding: 14px;">
                    <i class="ph ph-truck" style="font-size: 20px; color: #F97316;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 700;">Load New Truck</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Create Gate Pass</div>
                    </div>
                </a>
                <a href="/billing" class="btn btn-secondary" style="justify-content: flex-start; padding: 14px;">
                    <i class="ph ph-receipt" style="font-size: 20px; color: #10B981;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 700;">Create Invoice</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Generate GST Bill</div>
                    </div>
                </a>
                <a href="/inventory" class="btn btn-secondary" style="justify-content: flex-start; padding: 14px;">
                    <i class="ph ph-cube" style="font-size: 20px; color: #2563EB;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 700;">Add Inventory Item</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Stock In / Out</div>
                    </div>
                </a>
                <a href="/approvals" class="btn btn-secondary" style="justify-content: flex-start; padding: 14px;">
                    <i class="ph ph-lock-key" style="font-size: 20px; color: #8B5CF6;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 700;">1-Time Approvals</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Review Requests</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
