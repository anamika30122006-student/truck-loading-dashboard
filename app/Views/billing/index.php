<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Billing & Invoicing</h1>
        <div class="page-subtitle">Generate GST freight invoices, track accounts receivables, and record payments</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-primary" onclick="openModal('createInvoiceModal')">
            <i class="ph ph-plus-circle"></i> Create New Invoice
        </button>
    </div>
</div>

<!-- Financial Summary KPIs -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue"><i class="ph ph-receipt"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Invoiced Billing</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['total_billing']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive"><?= $stats['total_invoices'] ?> Total Invoices</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Received & Paid</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['paid_amount']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Settled Collections</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-clock"></i></div>
            <div class="stat-info">
                <div class="stat-label">Pending Receivables</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['pending_amount']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">Due for payment</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-warning-circle" style="color: var(--danger);"></i></div>
            <div class="stat-info">
                <div class="stat-label">Overdue Bills</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['overdue_amount']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend danger">Requires Follow-up</div>
        </div>
    </div>
</div>

<!-- Invoices Data Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ph ph-file-text"></i> Invoices & Accounts Receivable</div>
        <input type="text" placeholder="Search customer, invoice #..." class="form-control table-search-input" data-table="billingTable" style="width: 250px; padding: 6px 12px; font-size: 13px;">
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table" id="billingTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Invoice #</th>
                        <th>Client / Consignee</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Subtotal / GST</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sno = 1; foreach ($invoices as $inv): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--text-muted);"><?= $sno++ ?></td>
                            <td>
                                <span style="font-weight: 700; color: var(--primary); font-family: monospace;">
                                    <?= htmlspecialchars($inv['invoice_no']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?= htmlspecialchars($inv['customer_name']) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);">GST: <?= htmlspecialchars($inv['customer_gst'] ?? 'N/A') ?></div>
                            </td>
                            <td><?= Helper::formatDate($inv['invoice_date']) ?></td>
                            <td><?= Helper::formatDate($inv['due_date']) ?></td>
                            <td>
                                <div style="font-size: 12px;"><?= Helper::formatCurrency($inv['subtotal']) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted);">GST (<?= $inv['tax_rate_pct'] ?? 18 ?>%): <?= Helper::formatCurrency($inv['tax_amount'] ?? 0) ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 800; font-size: 14px;"><?= Helper::formatCurrency($inv['total_amount']) ?></div>
                            </td>
                            <td><?= Helper::getStatusBadge($inv['status']) ?></td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <?php if ($inv['status'] !== 'Paid'): ?>
                                        <form action="/billing/mark-paid" method="POST" style="display: inline;">
                                            <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm" title="Mark as Paid"><i class="ph ph-check"></i> Paid</button>
                                        </form>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary btn-sm" onclick="printInvoice(<?= htmlspecialchars(json_encode($inv)) ?>)"><i class="ph ph-printer"></i> Print</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div id="createInvoiceModal" class="modal-backdrop">
    <div class="modal-box modal-lg">
        <form action="/billing/store" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-receipt"></i> Generate GST Freight Invoice</h3>
                <button type="button" class="modal-close" onclick="closeModal('createInvoiceModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client / Customer Name *</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="e.g., Tata Projects Ltd." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client GSTIN</label>
                        <input type="text" name="customer_gst" class="form-control" placeholder="e.g., 27AAAAA0000A1Z5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client Email</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="billing@client.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-control">
                            <option>NEFT / RTGS</option>
                            <option>UPI / Net Banking</option>
                            <option>Cheque</option>
                            <option>Cash</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 10px; margin-bottom: 12px; font-weight: 700;">Invoice Line Items</div>
                <div id="invoiceLinesContainer">
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="form-group" style="flex: 3; margin-bottom: 0;">
                            <input type="text" name="item_desc[]" class="form-control" placeholder="Item / Haulage Description" value="Interstate Cargo Freight Haulage (TRK-101)" required>
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <input type="number" name="item_qty[]" class="form-control" placeholder="Qty" value="1" min="1" required>
                        </div>
                        <div class="form-group" style="flex: 1.5; margin-bottom: 0;">
                            <input type="number" step="0.01" name="item_rate[]" class="form-control" placeholder="Rate (₹)" value="45000" required>
                        </div>
                    </div>
                </div>

                <div class="form-row" style="margin-top: 16px;">
                    <div class="form-group">
                        <label class="form-label">GST Tax Rate (%)</label>
                        <select name="tax_rate_pct" class="form-control">
                            <option value="18">18% GST (Standard)</option>
                            <option value="12">12% GST</option>
                            <option value="5">5% GST (RCM / Freight)</option>
                            <option value="0">0% (Exempt)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Discount (₹)</label>
                        <input type="number" name="discount" class="form-control" value="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createInvoiceModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Generate & Issue Invoice</button>
            </div>
        </form>
    </div>
</div>

<!-- Printable Invoice Preview Modal -->
<div id="printableInvoiceModal" class="modal-backdrop">
    <div class="modal-box modal-lg" style="max-width: 800px;">
        <div class="modal-header no-print">
            <h3 class="modal-title"><i class="ph ph-printer"></i> Tax Invoice Preview</h3>
            <div>
                <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="ph ph-printer"></i> Print / Download PDF</button>
                <button type="button" class="modal-close" onclick="closeModal('printableInvoiceModal')" style="margin-left: 10px;">&times;</button>
            </div>
        </div>
        <div class="modal-body" id="printInvoiceArea" style="background: #fff; padding: 32px; font-size: 13px;">
            <!-- Rendered by JS -->
        </div>
    </div>
</div>

<script>
function printInvoice(inv) {
    const area = document.getElementById('printInvoiceArea');
    let itemsHtml = '';
    (inv.items || []).forEach((item, idx) => {
        itemsHtml += `
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #E2E8F0;">${idx + 1}</td>
                <td style="padding: 10px; border-bottom: 1px solid #E2E8F0; font-weight: 600;">${item.description}</td>
                <td style="padding: 10px; border-bottom: 1px solid #E2E8F0; text-align: center;">${item.qty}</td>
                <td style="padding: 10px; border-bottom: 1px solid #E2E8F0; text-align: right;">₹${parseFloat(item.rate).toLocaleString()}</td>
                <td style="padding: 10px; border-bottom: 1px solid #E2E8F0; text-align: right; font-weight: 700;">₹${parseFloat(item.amount).toLocaleString()}</td>
            </tr>
        `;
    });

    area.innerHTML = `
        <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #2563EB; padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <img src="/assets/images/logo-dark.svg" alt="Akashcoke Industries Pvt Ltd" style="height: 36px; margin-bottom: 6px;">
                <div style="color: #64748B; font-size: 12px;">Hard Coke, Coal Logistics & Infrastructure Haulage<br>GSTIN: 27AABCA1234K1Z5</div>
            </div>
            <div style="text-align: right;">
                <h3 style="font-size: 20px; color: #1E293B;">TAX INVOICE</h3>
                <div style="font-weight: 700; color: #2563EB;">#${inv.invoice_no}</div>
                <div style="font-size: 12px; color: #64748B;">Date: ${inv.invoice_date}</div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748B; font-weight: 700;">Billed To:</div>
                <div style="font-size: 15px; font-weight: 700; color: #1E293B;">${inv.customer_name}</div>
                <div style="color: #64748B;">GSTIN: ${inv.customer_gst || 'N/A'}</div>
                <div style="color: #64748B;">${inv.customer_email || ''}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; text-transform: uppercase; color: #64748B; font-weight: 700;">Payment Status:</div>
                <div style="font-size: 14px; font-weight: 700; color: ${inv.status === 'Paid' ? '#10B981' : '#F59E0B'};">${inv.status.toUpperCase()}</div>
                <div style="color: #64748B; font-size: 12px;">Due Date: ${inv.due_date}</div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
            <thead>
                <tr style="background: #F8FAFC;">
                    <th style="padding: 10px; border-bottom: 2px solid #CBD5E1; text-align: left;">#</th>
                    <th style="padding: 10px; border-bottom: 2px solid #CBD5E1; text-align: left;">Description</th>
                    <th style="padding: 10px; border-bottom: 2px solid #CBD5E1; text-align: center;">Qty</th>
                    <th style="padding: 10px; border-bottom: 2px solid #CBD5E1; text-align: right;">Rate</th>
                    <th style="padding: 10px; border-bottom: 2px solid #CBD5E1; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>${itemsHtml}</tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 280px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                    <span>Subtotal:</span>
                    <span>₹${parseFloat(inv.subtotal).toLocaleString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                    <span>GST (${inv.tax_rate_pct}%):</span>
                    <span>₹${parseFloat(inv.tax_amount).toLocaleString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-top: 2px solid #1E293B; font-size: 16px; font-weight: 800; color: #2563EB;">
                    <span>Total Amount:</span>
                    <span>₹${parseFloat(inv.total_amount).toLocaleString()}</span>
                </div>
            </div>
        </div>
    `;

    openModal('printableInvoiceModal');
}
</script>
