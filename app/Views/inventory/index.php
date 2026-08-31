<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Inventory Management</h1>
        <div class="page-subtitle">Track stock inventory, warehouse bays, weight & volume capacity</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-secondary" onclick="openModal('adjustStockModal')">
            <i class="ph ph-arrows-left-right"></i> Stock In / Out
        </button>
        <button class="btn btn-primary" onclick="openModal('addItemModal')">
            <i class="ph ph-plus-circle"></i> Add Item
        </button>
    </div>
</div>

<!-- Inventory KPI Cards -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue"><i class="ph ph-cube"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Catalogue Items</div>
                <div class="stat-value"><?= number_format($stats['total_items']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Active in system</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-stack"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Stock Quantity</div>
                <div class="stat-value"><?= number_format($stats['total_stock_qty']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Units in warehouse</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-currency-inr"></i></div>
            <div class="stat-info">
                <div class="stat-label">Stock Valuation</div>
                <div class="stat-value"><?= Helper::formatCurrency($stats['total_valuation']) ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Total asset value</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-warning"></i></div>
            <div class="stat-info">
                <div class="stat-label">Low Stock Alerts</div>
                <div class="stat-value"><?= $stats['low_stock_count'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend <?= $stats['low_stock_count'] > 0 ? 'danger' : 'positive' ?>">
                <?= $stats['low_stock_count'] > 0 ? 'Requires Re-order' : 'Healthy Stock' ?>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Table Card -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ph ph-package"></i> Warehouse Stock Inventory</div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <input type="text" placeholder="Search by SKU, item, bay..." class="form-control table-search-input" data-table="inventoryTable" style="width: 250px; padding: 6px 12px; font-size: 13px;">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>SKU / Code</th>
                        <th>Item Details</th>
                        <th>Category</th>
                        <th>Warehouse Bay</th>
                        <th>Weight / Volume</th>
                        <th>Unit Price</th>
                        <th>Current Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr><td colspan="10" style="text-align: center; padding: 30px;">No inventory items registered yet.</td></tr>
                    <?php else: ?>
                        <?php $sno = 1; foreach ($items as $item): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $sno++ ?></td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 700; color: var(--primary); background: #EFF6FF; padding: 3px 8px; border-radius: 4px;">
                                        <?= htmlspecialchars($item['sku']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($item['name']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);">Min Threshold: <?= $item['min_threshold'] ?> <?= $item['unit'] ?></div>
                                </td>
                                <td><span class="badge badge-secondary"><?= htmlspecialchars($item['category']) ?></span></td>
                                <td><span style="font-weight: 500; color: var(--text-main);"><i class="ph ph-map-pin"></i> <?= htmlspecialchars($item['warehouse_bay'] ?? 'Bay-A') ?></span></td>
                                <td>
                                    <div style="font-size: 12px;"><?= $item['weight_per_unit_kg'] ?> kg / unit</div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= $item['volume_per_unit_cbm'] ?> m³ / unit</div>
                                </td>
                                <td style="font-weight: 600;"><?= Helper::formatCurrency($item['unit_price']) ?></td>
                                <td>
                                    <div style="font-weight: 700; font-size: 14px;"><?= number_format($item['current_stock']) ?> <span style="font-size: 12px; font-weight: 400; color: var(--text-muted);"><?= htmlspecialchars($item['unit']) ?></span></div>
                                </td>
                                <td><?= Helper::getStatusBadge($item['status']) ?></td>
                                <td>
                                    <form action="/inventory/delete" method="POST" onsubmit="return confirm('Delete this inventory item?');" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-secondary btn-icon" title="Delete"><i class="ph ph-trash" style="color: var(--danger);"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/inventory/store" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-plus-circle"></i> Add New Inventory Item</h3>
                <button type="button" class="modal-close" onclick="closeModal('addItemModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SKU Code</label>
                        <input type="text" name="sku" class="form-control" value="PRD-<?= strtoupper(substr(uniqid(), -4)) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option>Construction</option>
                            <option>Metals & Steel</option>
                            <option>Packaging</option>
                            <option>Electronics</option>
                            <option>Automotive</option>
                            <option>FMCG / Retail</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Item Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Heavy Duty Industrial Cable 50m" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Initial Stock Qty</label>
                        <input type="number" name="current_stock" class="form-control" value="100" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min Threshold</label>
                        <input type="number" name="min_threshold" class="form-control" value="20" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit of Measure</label>
                        <select name="unit" class="form-control">
                            <option>Units</option>
                            <option>Bags</option>
                            <option>Boxes</option>
                            <option>Bundles</option>
                            <option>Cans</option>
                            <option>Kits</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Unit Price (₹)</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="500.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (kg/unit)</label>
                        <input type="number" step="0.1" name="weight_per_unit_kg" class="form-control" value="10" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Volume (m³/unit)</label>
                        <input type="number" step="0.001" name="volume_per_unit_cbm" class="form-control" value="0.02" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Warehouse Bay / Location</label>
                    <input type="text" name="warehouse_bay" class="form-control" placeholder="e.g. Bay-A3, Section-4" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addItemModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Stock In / Out Modal -->
<div id="adjustStockModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/inventory/adjust" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-arrows-left-right"></i> Stock In / Stock Out Adjustment</h3>
                <button type="button" class="modal-close" onclick="closeModal('adjustStockModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Item</label>
                    <select name="id" class="form-control" required>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?> (Current: <?= $item['current_stock'] ?> <?= $item['unit'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Operation Type</label>
                        <select name="type" class="form-control">
                            <option value="in">➕ Stock In (Receive Goods)</option>
                            <option value="out">➖ Stock Out (Issue / Dispatch)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="qty" class="form-control" min="1" value="10" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason / Notes</label>
                    <input type="text" name="notes" class="form-control" placeholder="e.g., Shipment PO-980 received from supplier">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('adjustStockModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Submit Adjustment</button>
            </div>
        </form>
    </div>
</div>
