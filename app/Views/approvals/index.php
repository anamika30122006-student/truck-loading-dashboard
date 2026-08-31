<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Project Approvals Center</h1>
        <div class="page-subtitle">Centralized 1-Time authorization engine for Purchase Orders, Gate Passes, Expense Claims, Leaves, and Payroll</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-primary" onclick="openModal('newApprovalModal')">
            <i class="ph ph-plus-circle"></i> Create Approval Request
        </button>
    </div>
</div>

<!-- Approval Stats Cards -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-clock"></i></div>
            <div class="stat-info">
                <div class="stat-label">Pending 1-Time Approvals</div>
                <div class="stat-value"><?= $stats['pending'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">Action required</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Approved Requests</div>
                <div class="stat-value"><?= $stats['approved'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Locked & Authorized</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon red"><i class="ph ph-x-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Rejected Requests</div>
                <div class="stat-value"><?= $stats['rejected'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend danger">Dismissed</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-shield-check"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Audit Trail</div>
                <div class="stat-value"><?= $stats['total'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Strict 1-Time Rule</div>
        </div>
    </div>
</div>

<!-- Approvals Master Table Card -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ph ph-lock-key"></i> 1-Time Approval Audit Queue</div>
        <div style="display: flex; gap: 12px;">
            <input type="text" placeholder="Search by ref #, requester, type..." class="form-control table-search-input" data-table="approvalsMasterTable" style="width: 260px; padding: 4px 10px; font-size: 12px;">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table" id="approvalsMasterTable">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>Request Type / Ref</th>
                        <th>Requester & Dept</th>
                        <th>Details / Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions / Decision Log</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($approvals)): ?>
                        <tr><td colspan="8" style="text-align: center; padding: 30px;">No approval records found.</td></tr>
                    <?php else: ?>
                        <?php $sno = 1; foreach ($approvals as $appr): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $sno++ ?></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($appr['request_type']) ?></div>
                                    <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary); background: #EFF6FF; padding: 2px 6px; border-radius: 4px;">
                                        <?= htmlspecialchars($appr['reference_no']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($appr['requested_by']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($appr['department']) ?></div>
                                </td>
                                <td style="max-width: 280px;">
                                    <div style="font-size: 12.5px; color: var(--text-main);"><?= htmlspecialchars($appr['description']) ?></div>
                                    <?php if (!empty($appr['admin_remarks'])): ?>
                                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px; font-style: italic;">
                                            <i class="ph ph-chat-text"></i> "<?= htmlspecialchars($appr['admin_remarks']) ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 700;">
                                    <?= $appr['amount'] ? Helper::formatCurrency($appr['amount']) : '—' ?>
                                </td>
                                <td><?= htmlspecialchars($appr['date']) ?></td>
                                <td><?= Helper::getStatusBadge($appr['status']) ?></td>
                                <td>
                                    <?php if (strtolower($appr['status']) === 'pending'): ?>
                                        <div class="action-buttons">
                                            <button class="btn btn-success btn-sm" onclick="openApproveModal(<?= $appr['id'] ?>, '<?= htmlspecialchars($appr['reference_no']) ?>', '<?= htmlspecialchars($appr['request_type']) ?>', '<?= htmlspecialchars($appr['requested_by']) ?>')">
                                                <i class="ph ph-check"></i> Approve
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="openRejectModal(<?= $appr['id'] ?>, '<?= htmlspecialchars($appr['reference_no']) ?>', '<?= htmlspecialchars($appr['request_type']) ?>', '<?= htmlspecialchars($appr['requested_by']) ?>')">
                                                <i class="ph ph-x"></i> Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div style="font-size: 11.5px; color: var(--text-muted);">
                                            Decided by <strong><?= htmlspecialchars($appr['approved_by'] ?? 'Admin') ?></strong>
                                            <div style="font-size: 10.5px; color: var(--text-light);"><?= Helper::formatDate($appr['approved_at'] ?? '', 'd M Y, h:i A') ?></div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create New Approval Request Modal -->
<div id="newApprovalModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/approvals/store" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-plus-circle"></i> Create New 1-Time Approval Request</h3>
                <button type="button" class="modal-close" onclick="closeModal('newApprovalModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Request Type *</label>
                        <select name="request_type" class="form-control" required>
                            <option>Purchase Order</option>
                            <option>Expense Claim</option>
                            <option>New Vendor</option>
                            <option>Truck Gate Pass</option>
                            <option>Leave Request</option>
                            <option>Payroll Disbursal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference #</label>
                        <input type="text" name="reference_no" class="form-control" value="REQ-<?= rand(1000, 9999) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Requested By</label>
                        <input type="text" name="requested_by" class="form-control" value="Admin User" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-control">
                            <option>Operations & Fleet</option>
                            <option>Store & Warehouse</option>
                            <option>Finance & Accounts</option>
                            <option>Purchase</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Amount (₹) (If applicable)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 15000.00">
                </div>

                <div class="form-group">
                    <label class="form-label">Description / Purpose *</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Provide complete request details for 1-time verification..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('newApprovalModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-paper-plane-tilt"></i> Submit Request</button>
            </div>
        </form>
    </div>
</div>
