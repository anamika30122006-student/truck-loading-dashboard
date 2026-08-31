// Logistics Pro - Interactive Truck Cargo Bay Simulator

document.addEventListener('DOMContentLoaded', () => {
    const truckSelect = document.getElementById('manifestTruckSelect');
    if (!truckSelect) return;

    truckSelect.addEventListener('change', updateCapacityBars);
    
    // Add cargo row button
    const addRowBtn = document.getElementById('addCargoRowBtn');
    if (addRowBtn) {
        addRowBtn.addEventListener('click', addCargoRow);
    }

    // Attach listeners on dynamic item selects and quantity inputs
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('cargo-item-select') || e.target.classList.contains('cargo-qty-input')) {
            recalculateLoading();
        }
    });

    updateCapacityBars();
});

function addCargoRow() {
    const container = document.getElementById('cargoItemsContainer');
    if (!container) return;

    // Clone template or create elements
    const template = document.getElementById('cargoRowTemplate');
    if (template) {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }
}

function removeCargoRow(btn) {
    const row = btn.closest('.cargo-item-row');
    if (row) {
        row.remove();
        recalculateLoading();
    }
}

function updateCapacityBars() {
    const truckSelect = document.getElementById('manifestTruckSelect');
    if (!truckSelect) return;

    const selectedOption = truckSelect.options[truckSelect.selectedIndex];
    if (!selectedOption) return;

    const maxWeight = parseFloat(selectedOption.getAttribute('data-max-weight')) || 25000;
    const maxVolume = parseFloat(selectedOption.getAttribute('data-max-volume')) || 48;

    document.getElementById('displayMaxWeight').innerText = maxWeight.toLocaleString() + ' kg';
    document.getElementById('displayMaxVolume').innerText = maxVolume.toLocaleString() + ' m³';

    recalculateLoading();
}

function recalculateLoading() {
    const truckSelect = document.getElementById('manifestTruckSelect');
    if (!truckSelect) return;

    const selectedOption = truckSelect.options[truckSelect.selectedIndex];
    const maxWeight = parseFloat(selectedOption ? selectedOption.getAttribute('data-max-weight') : 25000) || 25000;
    const maxVolume = parseFloat(selectedOption ? selectedOption.getAttribute('data-max-volume') : 48) || 48;

    let totalWeight = 0;
    let totalVolume = 0;
    const visualGrid = document.getElementById('bayCargoVisual');
    if (visualGrid) visualGrid.innerHTML = '';

    const rows = document.querySelectorAll('.cargo-item-row');
    rows.forEach(row => {
        const itemSelect = row.querySelector('.cargo-item-select');
        const qtyInput = row.querySelector('.cargo-qty-input');

        if (itemSelect && qtyInput) {
            const opt = itemSelect.options[itemSelect.selectedIndex];
            const qty = parseInt(qtyInput.value) || 0;

            if (opt && opt.value && qty > 0) {
                const unitWeight = parseFloat(opt.getAttribute('data-weight')) || 1;
                const unitVolume = parseFloat(opt.getAttribute('data-volume')) || 0.01;
                const itemName = opt.getAttribute('data-name') || 'Cargo Item';

                const rowWeight = unitWeight * qty;
                const rowVolume = unitVolume * qty;

                totalWeight += rowWeight;
                totalVolume += rowVolume;

                // Add visual box into isometric loading bay
                if (visualGrid) {
                    const box = document.createElement('div');
                    box.className = 'cargo-box';
                    box.innerHTML = `<i class="ph ph-package"></i> <span>${itemName} (${qty})</span>`;
                    visualGrid.appendChild(box);
                }
            }
        }
    });

    const weightPct = Math.min(100, Math.round((totalWeight / maxWeight) * 100));
    const volumePct = Math.min(100, Math.round((totalVolume / maxVolume) * 100));

    // Update weight bar & text
    const weightBar = document.getElementById('weightProgressBar');
    if (weightBar) {
        weightBar.style.width = weightPct + '%';
        weightBar.style.background = totalWeight > maxWeight ? '#EF4444' : 'linear-gradient(90deg, #10B981, #F59E0B)';
    }
    const weightText = document.getElementById('weightUtilText');
    if (weightText) weightText.innerText = `${totalWeight.toLocaleString()} kg (${weightPct}%)`;

    // Update volume bar & text
    const volBar = document.getElementById('volumeProgressBar');
    if (volBar) {
        volBar.style.width = volumePct + '%';
        volBar.style.background = totalVolume > maxVolume ? '#EF4444' : 'linear-gradient(90deg, #3B82F6, #8B5CF6)';
    }
    const volText = document.getElementById('volumeUtilText');
    if (volText) volText.innerText = `${totalVolume.toFixed(2)} m³ (${volumePct}%)`;

    // Overload Warning
    const warnEl = document.getElementById('overloadWarning');
    if (warnEl) {
        if (totalWeight > maxWeight || totalVolume > maxVolume) {
            warnEl.style.display = 'block';
        } else {
            warnEl.style.display = 'none';
        }
    }
}
