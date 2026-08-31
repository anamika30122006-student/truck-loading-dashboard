<?php
use App\Models\ApprovalModel;
$approvalModel = new ApprovalModel();
$pendingCount = count($approvalModel->getPending());
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="ph-fill ph-truck"></i>
        </div>
        <div class="brand-info">
            <h2>Logistics Pro</h2>
            <span>Management System</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="/" class="nav-item <?= ($activePage === 'dashboard') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-house"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <a href="/inventory" class="nav-item <?= ($activePage === 'inventory') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-cube"></i>
                <span>Inventory</span>
            </div>
        </a>

        <a href="/truck-loading" class="nav-item <?= ($activePage === 'truck_loading') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-truck"></i>
                <span>Truck Loading</span>
            </div>
        </a>

        <a href="/billing" class="nav-item <?= ($activePage === 'billing') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-receipt"></i>
                <span>Billing</span>
            </div>
        </a>

        <a href="/hrms" class="nav-item <?= ($activePage === 'hrms') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-users"></i>
                <span>HRMS</span>
            </div>
        </a>

        <a href="/payroll" class="nav-item <?= ($activePage === 'payroll') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-currency-inr"></i>
                <span>Payroll</span>
            </div>
        </a>

        <a href="/notifications" class="nav-item <?= ($activePage === 'notifications') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-bell-ringing"></i>
                <span>Payment Notification</span>
            </div>
        </a>

        <a href="/approvals" class="nav-item <?= ($activePage === 'approvals') ? 'active' : '' ?>">
            <div class="nav-link-content">
                <i class="ph ph-lock-key"></i>
                <span>Approvals</span>
            </div>
            <?php if ($pendingCount > 0): ?>
                <span class="nav-badge"><?= $pendingCount ?></span>
            <?php endif; ?>
        </a>

        <a href="/billing" class="nav-item">
            <div class="nav-link-content">
                <i class="ph ph-chart-bar"></i>
                <span>Reports</span>
            </div>
        </a>

    </nav>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="ph ph-user"></i>
            </div>
            <div class="user-meta">
                <div class="name">Admin User</div>
                <div class="role">Administrator</div>
            </div>
            <i class="ph ph-caret-down text-light" style="font-size: 14px;"></i>
        </div>

        <a href="/" class="logout-btn">
            <i class="ph ph-sign-out"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
