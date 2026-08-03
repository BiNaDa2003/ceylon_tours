<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Booking Management</h3>
        <p class="text-muted small mb-0">Review and update the status of all customer reservations</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Travel Date</th>
                    <th>Travelers</th>
                    <th>Total (Rs.)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach($bookings as $booking): ?>
                        <tr>
                            <td><strong>#SRI-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($booking['customer_email'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($booking['package_title'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                            <td><?php echo htmlspecialchars($booking['travelers']); ?> pax</td>
                            <td class="fw-bold text-primary">Rs. <?php echo number_format($booking['total_price'] ?? 0, 0); ?></td>
                            <td>
                                <?php
                                $badge = 'bg-secondary text-white';
                                if($booking['booking_status'] == 'Confirmed') $badge = 'bg-success text-white';
                                if($booking['booking_status'] == 'Pending')   $badge = 'bg-warning text-dark';
                                if($booking['booking_status'] == 'Cancelled') $badge = 'bg-danger text-white';
                                ?>
                                <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-1"><?php echo htmlspecialchars($booking['booking_status']); ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editStatusModal<?php echo $booking['id']; ?>" 
                                        title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="index.php?route=admin_delete_booking&id=<?php echo $booking['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-circle" 
                                   onclick="return confirm('Delete this booking permanently?');" 
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Status Modal -->
                        <div class="modal fade" id="editStatusModal<?php echo $booking['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <div class="modal-header border-0 bg-light rounded-top-4">
                                        <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Update Booking Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="index.php?route=admin_update_booking_status" method="POST">
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <p class="text-muted small mb-3">Booking <strong>#SRI-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></strong> by <strong><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></strong></p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Booking Status</label>
                                                <select name="booking_status" class="form-select">
                                                    <option value="Pending"   <?php echo $booking['booking_status'] == 'Pending'   ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Confirmed" <?php echo $booking['booking_status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="Cancelled" <?php echo $booking['booking_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </div>
                                            <?php if (!empty($booking['special_requests'])): ?>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Customer Special Requests</label>
                                                <textarea class="form-control bg-light" readonly rows="2"><?php echo htmlspecialchars($booking['special_requests']); ?></textarea>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 p-4">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
