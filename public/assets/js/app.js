// Logistics Pro - Interactive Core Client Script

document.addEventListener('DOMContentLoaded', () => {
    // Live Search Filter for tables
    const searchInputs = document.querySelectorAll('.table-search-input');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', (e) => {
            const query = e.target.value.toLowerCase();
            const targetTableId = input.getAttribute('data-table');
            const table = document.getElementById(targetTableId);
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });

    // Mobile Sidebar Toggle and Backdrop Handling
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (backdrop) backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (backdrop) backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    // Close mobile sidebar when clicking a nav link
    document.querySelectorAll('.sidebar .nav-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                closeSidebar();
            }
        });
    });

    // Modal background close
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
});

// Modal Helpers
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// 1-Time Approval Handlers
function openApproveModal(id, refNo, reqType, requestedBy) {
    document.getElementById('approve_id').value = id;
    document.getElementById('approve_ref_text').innerText = `${reqType} (${refNo})`;
    document.getElementById('approve_requester_text').innerText = requestedBy;
    openModal('approveModal');
}

function openRejectModal(id, refNo, reqType, requestedBy) {
    document.getElementById('reject_id').value = id;
    document.getElementById('reject_ref_text').innerText = `${reqType} (${refNo})`;
    document.getElementById('reject_requester_text').innerText = requestedBy;
    openModal('rejectModal');
}

// Toast notification helper
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.position = 'fixed';
    toast.style.bottom = '24px';
    toast.style.right = '24px';
    toast.style.zIndex = '9999';
    toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
    toast.innerHTML = `<i class="ph ph-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 4000);
}
