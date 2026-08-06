<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Custom Package Requests</h3>
        <p class="text-muted small mb-0">Review tailor-made tour requests submitted by customers</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Req ID</th>
                    <th>Customer</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Activities</th>
                    <th>Est. Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($custom_packages)): ?>
                    <?php foreach ($custom_packages as $req): ?>
                        <tr>
                            <td><strong>#CP-<?php echo str_pad($req['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                            <td>
                                <strong><?php echo htmlspecialchars($req['customer_name'] ?? 'N/A'); ?></strong>
                                <small class="d-block text-muted"><?php echo htmlspecialchars($req['customer_email'] ?? ''); ?></small>
                            </td>
                            <td><strong class="text-primary"><?php echo htmlspecialchars($req['destination']); ?></strong></td>
                            <td><?php echo htmlspecialchars($req['duration']); ?> Days</td>
                            <td style="max-width: 200px;"><small class="text-muted"><?php echo htmlspecialchars($req['activities'] ?: 'General'); ?></small></td>
                            <td class="fw-bold text-success">Rs. <?php echo number_format($req['estimated_price'], 0); ?></td>
                            <td>
                                <?php 
                                $badge = 'bg-warning text-dark';
                                if ($req['status'] === 'Approved') $badge = 'bg-success text-white';
                                if ($req['status'] === 'Rejected') $badge = 'bg-danger text-white';
                                ?>
                                <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-1"><?php echo htmlspecialchars($req['status']); ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick='openStatusModal(<?php echo json_encode($req); ?>)'>
                                    Review / Respond
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No custom package requests submitted yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Respond to Custom Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?route=admin_update_custom_package_status" method="POST">
                <input type="hidden" name="request_id" id="modal_req_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" id="modal_status" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Response Notes / Custom Quote Details</label>
                        <textarea name="admin_notes" id="modal_notes" class="form-control" rows="4" placeholder="Provide confirmation details, final price adjustments, driver details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(req) {
    document.getElementById('modal_req_id').value = req.id;
    document.getElementById('modal_status').value = req.status;
    document.getElementById('modal_notes').value = req.admin_notes || '';
    var modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}
</script>

<?php require_once 'includes/admin_footer.php'; ?>
