<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>HRMS & Workforce Management</h1>
        <div class="page-subtitle">Manage employee directory, drivers, warehouse staff, daily attendance, and leave requests</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-secondary" onclick="openModal('applyLeaveModal')">
            <i class="ph ph-calendar-plus"></i> Apply Leave
        </button>
        <button class="btn btn-primary" onclick="openModal('addEmployeeModal')">
            <i class="ph ph-user-plus"></i> Add Employee
        </button>
    </div>
</div>

<!-- HRMS KPIs -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-users"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Workforce</div>
                <div class="stat-value"><?= $stats['total'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Across all departments</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Active & On Duty</div>
                <div class="stat-value"><?= $stats['active'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Present Today</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-steering-wheel"></i></div>
            <div class="stat-info">
                <div class="stat-label">Fleet Drivers</div>
                <div class="stat-value"><?= $stats['drivers'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Certified Heavy Haulers</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue"><i class="ph ph-calendar-x"></i></div>
            <div class="stat-info">
                <div class="stat-label">On Leave Today</div>
                <div class="stat-value"><?= $stats['on_leave'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">Authorized absences</div>
        </div>
    </div>
</div>

<!-- Employee Directory & Attendance Grid -->
<div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 20px;">
    <!-- Employee Directory Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ph ph-address-book"></i> Employee Directory</div>
            <input type="text" placeholder="Search employee, designation..." class="form-control table-search-input" data-table="empTable" style="width: 200px; padding: 4px 10px; font-size: 12px;">
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table" id="empTable">
                    <thead>
                        <tr>
                            <th style="width: 45px;">#</th>
                            <th>Employee</th>
                            <th>Designation / Dept</th>
                            <th>Contact</th>
                            <th>Base Salary</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; foreach ($employees as $emp): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $sno++ ?></td>
                                <td>
                                    <div style="font-weight: 700;"><?= htmlspecialchars($emp['name']) ?></div>
                                    <div style="font-size: 11px; color: var(--primary); font-family: monospace;"><?= htmlspecialchars($emp['emp_code'] ?? 'EMP') ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($emp['designation']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($emp['department']) ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 12px;"><?= htmlspecialchars($emp['phone']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($emp['email']) ?></div>
                                </td>
                                <td style="font-weight: 600;"><?= Helper::formatCurrency($emp['basic_salary'] ?? 25000) ?></td>
                                <td><?= Helper::getStatusBadge($emp['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Tracker -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ph ph-clock"></i> Today's Attendance (31 Aug 2026)</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 45px;">#</th>
                            <th>Staff Name</th>
                            <th>In / Out</th>
                            <th>Overtime</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $attSno = 1; foreach ($attendance as $att): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $attSno++ ?></td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($att['emp_name']) ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 12px;"><?= htmlspecialchars($att['check_in']) ?> - <?= htmlspecialchars($att['check_out']) ?></div>
                                </td>
                                <td>
                                    <span style="font-size: 11px; font-weight: 600;"><?= $att['overtime_hrs'] ?> hrs</span>
                                </td>
                                <td><?= Helper::getStatusBadge($att['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/hrms/store-employee" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-user-plus"></i> Onboard New Employee</h3>
                <button type="button" class="modal-close" onclick="closeModal('addEmployeeModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Sunil Gaikwad" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Designation / Role *</label>
                        <input type="text" name="designation" class="form-control" placeholder="e.g., Heavy Vehicle Driver" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-control">
                            <option>Fleet Operations</option>
                            <option>Warehouse Management</option>
                            <option>Finance & Accounts</option>
                            <option>Purchase & Logistics</option>
                            <option>Administration</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@logistics.pro">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91 98765 00000" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Basic Salary (₹/month) *</label>
                        <input type="number" name="basic_salary" class="form-control" value="28000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allowances (₹/month)</label>
                        <input type="number" name="allowances" class="form-control" value="4000" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addEmployeeModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Register Employee</button>
            </div>
        </form>
    </div>
</div>

<!-- Apply Leave Modal -->
<div id="applyLeaveModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/hrms/apply-leave" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-calendar-plus"></i> Submit Leave Request</h3>
                <button type="button" class="modal-close" onclick="closeModal('applyLeaveModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Employee</label>
                    <select name="emp_id" class="form-control" required>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['designation']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Leave Type</label>
                        <select name="leave_type" class="form-control">
                            <option>Casual Leave</option>
                            <option>Medical Leave</option>
                            <option>Earned / Annual Leave</option>
                            <option>Emergency Leave</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Days</label>
                        <input type="number" name="total_days" class="form-control" value="2" min="1" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="<?= date('Y-m-d', strtotime('+2 days')) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason for Leave *</label>
                    <textarea name="reason" class="form-control" rows="2" placeholder="Describe the reason..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('applyLeaveModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-paper-plane-tilt"></i> Submit for Approval</button>
            </div>
        </form>
    </div>
</div>
