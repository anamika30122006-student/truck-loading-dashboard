<!-- Global Approve Modal (1-Time Approval) -->
<div id="approveModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/approvals/approve" method="POST">
            <div class="modal-header">
                <h3 class="modal-title" style="color: var(--success);"><i class="ph ph-check-circle"></i> Confirm 1-Time Approval</h3>
                <button type="button" class="modal-close" onclick="closeModal('approveModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="approve_id">
                <p style="margin-bottom: 12px; font-size: 14px;">
                    Are you sure you want to approve <strong id="approve_ref_text">-</strong>?
                </p>
                <div style="background: var(--bg-subtle); padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; margin-bottom: 16px; border: 1px solid var(--border-light);">
                    <div><strong>Requested By:</strong> <span id="approve_requester_text">-</span></div>
                    <div style="color: var(--text-muted); font-size: 12px; margin-top: 4px;">
                        <i class="ph ph-info"></i> Notice: Once approved, this decision cannot be undone or re-edited.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Approval Remarks (Optional)</label>
                    <input type="text" name="remarks" class="form-control" placeholder="e.g., Verified documents and authorized.">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('approveModal')">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="ph ph-check"></i> Authorize & Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Global Reject Modal (1-Time Approval) -->
<div id="rejectModal" class="modal-backdrop">
    <div class="modal-box">
        <form action="/approvals/reject" method="POST">
            <div class="modal-header">
                <h3 class="modal-title" style="color: var(--danger);"><i class="ph ph-x-circle"></i> Reject Request</h3>
                <button type="button" class="modal-close" onclick="closeModal('rejectModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="reject_id">
                <p style="margin-bottom: 12px; font-size: 14px;">
                    Are you sure you want to reject <strong id="reject_ref_text">-</strong>?
                </p>
                <div style="background: var(--danger-light); color: #991B1B; padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; margin-bottom: 16px; border: 1px solid #FECACA;">
                    <div><strong>Requested By:</strong> <span id="reject_requester_text">-</span></div>
                    <div style="font-size: 12px; margin-top: 4px;">
                        <i class="ph ph-warning"></i> Warning: This request will be permanently rejected.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Rejection Reason *</label>
                    <input type="text" name="remarks" class="form-control" placeholder="e.g., Incomplete documentation / Budget exceeded" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="ph ph-x"></i> Reject Request</button>
            </div>
        </form>
    </div>
</div>

<!-- Core Scripts -->
<script src="/assets/js/app.js"></script>
<script src="/assets/js/charts.js"></script>
<script src="/assets/js/truck-loader.js"></script>
</body>
</html>
