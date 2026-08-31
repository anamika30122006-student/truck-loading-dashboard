<?php
use App\Core\Helper;
?>

<div class="page-header">
    <div class="page-title-wrap">
        <h1>Truck Loading & Cargo Simulator</h1>
        <div class="page-subtitle">Real-time container visualizer, weight/volume balancing, and Gate Pass generation</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-secondary" onclick="openModal('addTruckModal')">
            <i class="ph ph-plus-circle"></i> Add Truck
        </button>
        <button class="btn btn-primary" onclick="openModal('newManifestModal')">
            <i class="ph ph-truck"></i> Load Truck & Create Gate Pass
        </button>
    </div>
</div>

<!-- Fleet Status KPIs -->
<div class="stats-grid-4">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange"><i class="ph ph-truck"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Fleet Size</div>
                <div class="stat-value"><?= $stats['total'] ?> Trucks</div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Active in Fleet</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green"><i class="ph ph-package"></i></div>
            <div class="stat-info">
                <div class="stat-label">Fully Loaded & Ready</div>
                <div class="stat-value"><?= $stats['loaded'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">Ready for Dispatch</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue"><i class="ph ph-hourglass-high"></i></div>
            <div class="stat-info">
                <div class="stat-label">Currently in Loading Bay</div>
                <div class="stat-value"><?= $stats['loading'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend warning">Bay Active</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple"><i class="ph ph-navigation-arrow"></i></div>
            <div class="stat-info">
                <div class="stat-label">In-Transit On Highway</div>
                <div class="stat-value"><?= $stats['in_transit'] ?></div>
            </div>
        </div>
        <div class="stat-sparkline">
            <div class="stat-trend positive">En route to destination</div>
        </div>
    </div>
</div>

<!-- Interactive 2D/3D Container Bay Visualizer Demo Area -->
<div class="truck-bay-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; box-shadow: 0 0 10px #10B981;"></div>
            <h3 style="color: #fff; font-size: 16px;">Live Loading Bay Simulation (TRK-101 Tata Signa)</h3>
        </div>
        <div style="font-size: 12px; color: #94A3B8;">
            Bay #01 • Auto-Balancing Enabled
        </div>
    </div>

    <div class="truck-isometric-view">
        <div style="position: absolute; top: 12px; left: 16px; font-size: 12px; color: #94A3B8;">
            <i class="ph ph-cube"></i> Container Interior Cargo Space [Max: 25,000 kg | 48.0 m³]
        </div>
        <div class="cargo-load-grid" id="bayCargoVisualDemo">
            <div class="cargo-box"><i class="ph ph-package"></i> Industrial Cement (350 Bags)</div>
            <div class="cargo-box"><i class="ph ph-package"></i> Heavy Steel Rebar (53 Bundles)</div>
            <div class="cargo-box" style="background: linear-gradient(135deg, #10B981, #059669);"><i class="ph ph-shield-check"></i> Weight Distributed [Axle Safe]</div>
        </div>
    </div>

    <div class="capacity-bars-wrap">
        <div class="cap-bar-box">
            <div class="cap-bar-header">
                <span><i class="ph ph-scales"></i> Weight Load Capacity</span>
                <span style="color: #fff; font-weight: 700;">21,500 / 25,000 kg (86%)</span>
            </div>
            <div class="cap-bar-progress">
                <div class="cap-bar-fill fill-weight" style="width: 86%;"></div>
            </div>
        </div>

        <div class="cap-bar-box">
            <div class="cap-bar-header">
                <span><i class="ph ph-cube"></i> Volume Space Utilization</span>
                <span style="color: #fff; font-weight: 700;">18.24 / 48.0 m³ (38%)</span>
            </div>
            <div class="cap-bar-progress">
                <div class="cap-bar-fill fill-volume" style="width: 38%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Fleet & Gate Passes Table Grid -->
<div class="two-col-grid">
    <!-- Active Fleet Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ph ph-truck"></i> Truck Fleet Directory</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 45px;">#</th>
                            <th>Truck No</th>
                            <th>Model / Driver</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $truckSno = 1; foreach ($trucks as $truck): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $truckSno++ ?></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--primary);"><?= htmlspecialchars($truck['truck_no']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($truck['assigned_route']) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($truck['model']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><i class="ph ph-user"></i> <?= htmlspecialchars($truck['driver_name']) ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 12px; font-weight: 600;"><?= number_format($truck['max_weight_capacity_kg']) ?> kg</div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= $truck['max_volume_capacity_cbm'] ?> m³</div>
                                </td>
                                <td><?= Helper::getStatusBadge($truck['status']) ?></td>
                                <td>
                                    <form action="/truck-loading/update-status" method="POST" style="display: inline;">
                                        <input type="hidden" name="truck_id" value="<?= $truck['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto; padding: 4px 8px; font-size: 11.5px;">
                                            <option value="Available" <?= $truck['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                                            <option value="Loading" <?= $truck['status'] === 'Loading' ? 'selected' : '' ?>>Loading</option>
                                            <option value="Loaded" <?= $truck['status'] === 'Loaded' ? 'selected' : '' ?>>Loaded</option>
                                            <option value="In-Transit" <?= $truck['status'] === 'In-Transit' ? 'selected' : '' ?>>In-Transit</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Gate Passes / Manifests -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ph ph-identification-card"></i> Gate Passes & Manifests</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 45px;">#</th>
                            <th>Gate Pass #</th>
                            <th>Truck / Driver</th>
                            <th>Load Weight</th>
                            <th>Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $manSno = 1; foreach ($manifests as $manifest): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);"><?= $manSno++ ?></td>
                                <td>
                                    <div style="font-weight: 700;"><?= htmlspecialchars($manifest['manifest_no']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($manifest['destination']) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($manifest['truck_no']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($manifest['driver_name']) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= number_format($manifest['total_weight_kg']) ?> kg</div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= $manifest['weight_utilization_pct'] ?>% Cap</div>
                                </td>
                                <td><?= Helper::getStatusBadge($manifest['approval_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Truck Modal -->
<div id="addTruckModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/truck-loading/store-truck" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-truck"></i> Register New Truck to Fleet</h3>
                <button type="button" class="modal-close" onclick="closeModal('addTruckModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Truck Registration Number *</label>
                        <input type="text" name="truck_no" class="form-control" placeholder="e.g., MH-12-AB-9842" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vehicle Model & Make</label>
                        <input type="text" name="model" class="form-control" placeholder="e.g., Tata Signa 4825.TK" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Assigned Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" placeholder="e.g., Rajesh Kumar" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Driver Phone Number</label>
                        <input type="text" name="driver_phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Max Weight Capacity (kg) *</label>
                        <input type="number" name="max_weight_capacity_kg" class="form-control" value="25000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Volume Capacity (m³) *</label>
                        <input type="number" step="0.1" name="max_volume_capacity_cbm" class="form-control" value="48.0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Assigned Route / Operating Hub</label>
                    <input type="text" name="assigned_route" class="form-control" placeholder="e.g., Mumbai Central to Pune Depot" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addTruckModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Register Truck</button>
            </div>
        </form>
    </div>
</div>

<!-- Load Truck & Create Gate Pass Modal -->
<div id="newManifestModal" class="modal-backdrop">
    <div class="modal-box modal-lg">
        <form action="/truck-loading/create-manifest" method="POST">
            <div class="modal-header">
                <h3 class="modal-title"><i class="ph ph-scales"></i> Interactive Truck Cargo Loading Simulator</h3>
                <button type="button" class="modal-close" onclick="closeModal('newManifestModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Truck</label>
                        <select name="truck_id" id="manifestTruckSelect" class="form-control" required>
                            <?php foreach ($trucks as $t): ?>
                                <option value="<?= $t['id'] ?>" data-max-weight="<?= $t['max_weight_capacity_kg'] ?>" data-max-volume="<?= $t['max_volume_capacity_cbm'] ?>">
                                    <?= htmlspecialchars($t['truck_no']) ?> (<?= htmlspecialchars($t['model']) ?>) - Max <?= number_format($t['max_weight_capacity_kg']) ?> kg
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Source Bay</label>
                        <input type="text" name="source" class="form-control" value="Central Hub Bay-1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Destination Depot</label>
                        <input type="text" name="destination" class="form-control" placeholder="e.g., Surat Port Depot" required>
                    </div>
                </div>

                <!-- Real-Time Capacity Simulator Gauges -->
                <div style="background: #0F172A; color: #fff; padding: 16px; border-radius: var(--radius-md); margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                        <span><strong>Truck Max Limits:</strong> Weight: <span id="displayMaxWeight">25,000 kg</span> | Volume: <span id="displayMaxVolume">48 m³</span></span>
                        <span id="overloadWarning" style="color: #EF4444; font-weight: 700; display: none;"><i class="ph ph-warning"></i> OVERLOAD DETECTED!</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; color: #94A3B8;">
                                <span>Weight Utilization</span>
                                <span id="weightUtilText">0 kg (0%)</span>
                            </div>
                            <div class="cap-bar-progress">
                                <div id="weightProgressBar" class="cap-bar-fill fill-weight" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; color: #94A3B8;">
                                <span>Volume Space</span>
                                <span id="volumeUtilText">0 m³ (0%)</span>
                            </div>
                            <div class="cap-bar-progress">
                                <div id="volumeProgressBar" class="cap-bar-fill fill-volume" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cargo Items Section -->
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <label class="form-label" style="margin-bottom: 0;">Cargo Items to Load into Container</label>
                    <button type="button" id="addCargoRowBtn" class="btn btn-secondary btn-sm"><i class="ph ph-plus"></i> Add Item Row</button>
                </div>

                <div id="cargoItemsContainer">
                    <div class="form-row cargo-item-row" style="margin-bottom: 10px; align-items: flex-end;">
                        <div class="form-group" style="margin-bottom: 0; flex: 2;">
                            <label class="form-label" style="font-size: 11px;">Select Stock Item</label>
                            <select name="items[]" class="form-control cargo-item-select" required>
                                <option value="">-- Choose Item from Inventory --</option>
                                <?php foreach ($inventoryItems as $item): ?>
                                    <option value="<?= $item['id'] ?>" data-weight="<?= $item['weight_per_unit_kg'] ?>" data-volume="<?= $item['volume_per_unit_cbm'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>">
                                        <?= htmlspecialchars($item['name']) ?> (Stock: <?= $item['current_stock'] ?> | <?= $item['weight_per_unit_kg'] ?> kg/unit)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0; flex: 1;">
                            <label class="form-label" style="font-size: 11px;">Quantity</label>
                            <input type="number" name="quantities[]" class="form-control cargo-qty-input" value="50" min="1" required>
                        </div>
                        <button type="button" class="btn btn-danger btn-icon" onclick="removeCargoRow(this)" style="height: 42px;"><i class="ph ph-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('newManifestModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check-circle"></i> Generate Gate Pass & Submit Approval</button>
            </div>
        </form>
    </div>
</div>

<!-- Template for adding dynamic cargo row -->
<template id="cargoRowTemplate">
    <div class="form-row cargo-item-row" style="margin-bottom: 10px; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0; flex: 2;">
            <select name="items[]" class="form-control cargo-item-select" required>
                <option value="">-- Choose Item from Inventory --</option>
                <?php foreach ($inventoryItems as $item): ?>
                    <option value="<?= $item['id'] ?>" data-weight="<?= $item['weight_per_unit_kg'] ?>" data-volume="<?= $item['volume_per_unit_cbm'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>">
                        <?= htmlspecialchars($item['name']) ?> (Stock: <?= $item['current_stock'] ?> | <?= $item['weight_per_unit_kg'] ?> kg/unit)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <input type="number" name="quantities[]" class="form-control cargo-qty-input" value="10" min="1" required>
        </div>
        <button type="button" class="btn btn-danger btn-icon" onclick="removeCargoRow(this)" style="height: 42px;"><i class="ph ph-trash"></i></button>
    </div>
</template>
