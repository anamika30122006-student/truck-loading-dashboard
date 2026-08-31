<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Payment Notifications & Alerts</h1>
        <div class="page-subtitle">Track incoming settlements, outstanding client receivables, overdue alerts, and automated reminders</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <form action="/notifications/mark-all-read" method="POST" style="display: inline;">
            <button type="submit" class="btn btn-secondary"><i class="ph ph-checks"></i> Mark All as Read</button>
        </form>
        <button class="btn btn-primary" onclick="openModal('sendReminderModal')">
            <i class="ph ph-paper-plane-tilt"></i> Send Payment Reminder
        </button>
    </div>
</div>

<!-- Notification Feed -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ph ph-bell"></i> Live Payment Notifications Feed</div>
        <span class="badge badge-info"><?= $unreadCount ?> Unread Alerts</span>
    </div>
    <div class="card-body">
        <div class="activity-list">
            <?php if (empty($notifications)): ?>
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <i class="ph ph-bell-slash" style="font-size: 32px;"></i>
                    <p style="margin-top: 10px;">No payment notifications at this time.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <div class="notif-item" style="padding: 16px 0; <?= empty($n['is_read']) ? 'background: #F8FAFC; padding-left: 12px; padding-right: 12px; border-radius: 8px;' : '' ?>">
                        <div class="notif-left">
                            <div class="notif-icon">
                                <?php if ($n['status'] === 'Received'): ?>
                                    <div class="activity-icon color-green"><i class="ph ph-check-circle"></i></div>
                                <?php elseif ($n['status'] === 'Overdue'): ?>
                                    <div class="activity-icon color-red"><i class="ph ph-warning-circle"></i></div>
                                <?php else: ?>
                                    <div class="activity-icon color-orange"><i class="ph ph-clock"></i></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="notif-title" style="font-size: 14px; font-weight: 700;"><?= htmlspecialchars($n['title']) ?></div>
                                <div class="notif-sub" style="font-size: 12.5px; margin-top: 2px; color: var(--text-muted);"><?= htmlspecialchars($n['subtitle']) ?></div>
                                <div style="font-size: 11px; color: var(--text-light); margin-top: 4px;">
                                    <i class="ph ph-calendar"></i> <?= htmlspecialchars($n['time_str'] ?? 'Today') ?>
                                </div>
                            </div>
                        </div>
                        <div class="notif-right" style="gap: 8px;">
                            <?= Helper::getStatusBadge($n['status']) ?>
                            <?php if (empty($n['is_read'])): ?>
                                <form action="/notifications/mark-read" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                    <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 3px 8px;">Mark Read</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Send Reminder Modal -->
<div id="sendReminderModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/notifications/send-reminder" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-paper-plane-tilt"></i> Send Payment Reminder Alert</h3>
                <button type="button" class="modal-close" onclick="closeModal('sendReminderModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Client / Entity Name *</label>
                    <input type="text" name="target" class="form-control" placeholder="e.g., XYZ Corp. / UltraTech" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Outstanding Amount (₹) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="25000.00" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reminder Subject / Invoice #</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Invoice INV-098 due date reminder" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('sendReminderModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-bell"></i> Send Alert</button>
            </div>
        </form>
    </div>
</div>
