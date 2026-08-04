<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Customer Management</h3>
        <p class="text-muted small mb-0">View and manage registered customers</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $cust): ?>
                        <tr>
                            <td><strong>#<?php echo $cust['id']; ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:36px;height:36px;background:linear-gradient(135deg,#0f7b6c,#f59e0b);font-size:.85rem;flex-shrink:0;">
                                        <?php echo strtoupper(substr($cust['name'], 0, 1)); ?>
                                    </div>
                                    <strong><?php echo htmlspecialchars($cust['name']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($cust['email']); ?></td>
                            <td><?php echo htmlspecialchars($cust['phone']); ?></td>
                            <td><small class="text-muted"><?php echo date('M d, Y', strtotime($cust['created_at'])); ?></small></td>
                            <td>
                                <a href="index.php?route=admin_delete_customer&id=<?php echo $cust['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-circle"
                                   onclick="return confirm('Delete customer <?php echo addslashes($cust['name']); ?>? This will also delete all their bookings.')"
                                   title="Delete Customer">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No customers registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
