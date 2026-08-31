<?php
use App\Models\NotificationModel;
$notifModel = new NotificationModel();
$unreadNotifs = $notifModel->getUnreadCount();
?>
<header class="topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" class="sidebar-toggle">
            <i class="ph ph-list"></i>
        </button>
        <div class="topbar-search">
            <i class="ph ph-magnifying-glass"></i>
            <input type="text" placeholder="Search anything..." class="table-search-input" data-table="mainDataTable">
        </div>
    </div>

    <div class="topbar-right">
        <div class="live-clock-badge">
            <i class="ph ph-calendar-blank"></i>
            <span>31 Aug 2026 | Monday</span>
        </div>

        <a href="/notifications" class="notif-bell" title="Payment Notifications">
            <i class="ph ph-bell"></i>
            <?php if ($unreadNotifs > 0): ?>
                <span class="bell-badge"><?= $unreadNotifs ?></span>
            <?php endif; ?>
        </a>

        <div class="user-avatar" style="cursor: pointer;" onclick="window.location.href='/hrms'">
            <i class="ph ph-user"></i>
        </div>
    </div>
</header>
