<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Payroll & Salary Management</h1>
        <div class="page-subtitle">Process monthly salaries, PF & tax deductions, generate payslips and manage approvals</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <form action="/payroll/generate-batch" method="POST" style="display: inline;">
            <input type="hidden" name="month" value="<?= htmlspecialchars($selectedMonth) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="ph ph-lightning"></i> Process Batch Payroll (<?= date('M Y', strtotime($selectedMonth . '-01')) ?>)
            </button>
        </form>
    </div>
</div>

<!-- Payroll KPI Metrics -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon cyan"><i class="ph ph-currency-inr"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Disbursed Payroll</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['total_disbursed']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Paid to employees</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-clock"></i></div>
            <div class="stat-info">
                <div class="stat-label">Pending Salary Disbursals</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['total_pending']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">Awaiting 1-Time approval</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-users"></i></div>
            <div class="stat-info">
                <div class="stat-label">Processed Payslips</div>
                <div class="stat-value"><?= $stats['processed_count'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Generated this cycle</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-shield-check"></i></div>
            <div class="stat-info">
                <div class="stat-label">Statutory Compliance</div>
                <div class="stat-value">100%</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">PF & ESIC Compliant</div>
        </div>
    </div>
</div>

<!-- Payroll Table Card -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ph ph-file-text"></i> Salary Sheet & Payslips (<?= date('F Y', strtotime($selectedMonth . '-01')) ?>)</div>
        <div style="display: flex; gap: 12px;">
            <input type="text" placeholder="Search staff name..." class="form-control table-search-input" data-table="payrollTable" style="width: 220px; padding: 4px 10px; font-size: 12px;">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table" id="payrollTable">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>Payslip #</th>
                        <th>Employee</th>
                        <th>Designation</th>
                        <th>Basic + HRA</th>
                        <th>Gross Pay</th>
                        <th>Deductions (PF/Tax)</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payrollList)): ?>
                        <tr><td colspan="10" style="text-align: center; padding: 30px;">No payroll records for this month yet. Click "Process Batch Payroll" above.</td></tr>
                    <?php else: ?>
                        <?php $sno = 1; foreach ($payrollList as $p): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $sno++ ?></td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 700; color: var(--primary);">
                                        <?= htmlspecialchars($p['payslip_no']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 700;"><?= htmlspecialchars($p['emp_name']) ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 12px;"><?= htmlspecialchars($p['designation']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($p['department']) ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 12px;"><?= Helper::formatCurrency($p['basic_salary']) ?> + <?= Helper::formatCurrency($p['hra']) ?></div>
                                </td>
                                <td style="font-weight: 600;"><?= Helper::formatCurrency($p['gross_salary']) ?></td>
                                <td>
                                    <div style="color: var(--danger); font-weight: 600; font-size: 12px;">-<?= Helper::formatCurrency($p['total_deductions']) ?></div>
                                    <div style="font-size: 10px; color: var(--text-muted);">PF: <?= Helper::formatCurrency($p['pf_deduction']) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; font-size: 14px; color: #10B981;"><?= Helper::formatCurrency($p['net_salary']) ?></div>
                                </td>
                                <td><?= Helper::getStatusBadge($p['status']) ?></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <?php if ($p['status'] !== 'Paid'): ?>
                                            <form action="/payroll/mark-paid" method="POST" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm" title="Disburse Salary"><i class="ph ph-check"></i> Disburse</button>
                                            </form>
                                        <?php endif; ?>
                                        <button class="btn btn-secondary btn-sm" onclick="printPayslip(<?= htmlspecialchars(json_encode($p)) ?>)"><i class="ph ph-printer"></i> Slip</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Printable Payslip Preview Modal -->
<div id="printablePayslipModal" class="modal-backdrop">
    <div class="modal-box modal-lg" style="max-width: 750px;">
        <div class="modal-header no-print">
            <h3 class="modal-title"><i class="ph ph-receipt"></i> Official Payslip Preview</h3>
            <div>
                <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="ph ph-printer"></i> Print / Download PDF</button>
                <button type="button" class="modal-close" onclick="closeModal('printablePayslipModal')" style="margin-left: 10px;">&times;</button>
            </div>
        </div>
        <div class="modal-body" id="printPayslipArea" style="background: #fff; padding: 32px; font-size: 13px;">
            <!-- Rendered by JS -->
        </div>
    </div>
</div>

<script>
function printPayslip(p) {
    const area = document.getElementById('printPayslipArea');
    area.innerHTML = `
        <div style="border: 2px solid #E2E8F0; padding: 24px; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #2563EB; padding-bottom: 12px; margin-bottom: 18px;">
                <div>
                    <h2 style="color: #2563EB; margin-bottom: 4px;">Logistics Pro Pvt. Ltd.</h2>
                    <div style="color: #64748B; font-size: 11px;">Express Logistics & Supply Chain Operations</div>
                </div>
                <div style="text-align: right;">
                    <h3 style="font-size: 16px; color: #1E293B;">SALARY PAYSLIP</h3>
                    <div style="font-weight: 700; color: #2563EB;">${p.payslip_no}</div>
                    <div style="font-size: 12px; color: #64748B;">Month: ${p.payroll_month}</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; background: #F8FAFC; padding: 14px; border-radius: 6px; margin-bottom: 20px; font-size: 12.5px;">
                <div>
                    <div><strong>Employee Name:</strong> ${p.emp_name}</div>
                    <div><strong>Designation:</strong> ${p.designation}</div>
                </div>
                <div>
                    <div><strong>Department:</strong> ${p.department}</div>
                    <div><strong>Status:</strong> ${p.status}</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Earnings -->
                <div style="border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px;">
                    <div style="font-weight: 700; color: #10B981; border-bottom: 1px solid #E2E8F0; padding-bottom: 6px; margin-bottom: 8px;">EARNINGS</div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Basic Salary:</span> <span>₹${parseFloat(p.basic_salary).toLocaleString()}</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>HRA (20%):</span> <span>₹${parseFloat(p.hra).toLocaleString()}</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Special Allowances:</span> <span>₹${parseFloat(p.allowances).toLocaleString()}</span></div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px dashed #CBD5E1; padding-top: 6px; font-weight: 700;"><span>Gross Salary:</span> <span>₹${parseFloat(p.gross_salary).toLocaleString()}</span></div>
                </div>

                <!-- Deductions -->
                <div style="border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px;">
                    <div style="font-weight: 700; color: #EF4444; border-bottom: 1px solid #E2E8F0; padding-bottom: 6px; margin-bottom: 8px;">DEDUCTIONS</div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Provident Fund (PF):</span> <span>₹${parseFloat(p.pf_deduction).toLocaleString()}</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>TDS / Professional Tax:</span> <span>₹${parseFloat(p.tax_deduction).toLocaleString()}</span></div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px dashed #CBD5E1; padding-top: 6px; font-weight: 700;"><span>Total Deductions:</span> <span>₹${parseFloat(p.total_deductions).toLocaleString()}</span></div>
                </div>
            </div>

            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 14px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; font-size: 15px; color: #1E3A8A;">Net Take-Home Salary:</span>
                <span style="font-weight: 800; font-size: 20px; color: #2563EB;">₹${parseFloat(p.net_salary).toLocaleString()}</span>
            </div>
        </div>
    `;

    openModal('printablePayslipModal');
}
</script>
