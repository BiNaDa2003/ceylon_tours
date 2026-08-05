<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Customer Reviews Management</h3>
        <p class="text-muted small mb-0">Monitor and moderate ratings & comments left by travelers</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Tour Package</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <td>#<?php echo $rev['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($rev['customer_name'] ?? 'Anonymous'); ?></strong></td>
                            <td><?php echo htmlspecialchars($rev['package_title'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="stars text-warning fs-7">
                                    <?php 
                                    for ($i=1; $i<=5; $i++) {
                                        echo $i <= $rev['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star empty"></i>';
                                    }
                                    ?>
                                </span>
                                <strong class="ms-1"><?php echo $rev['rating']; ?>.0</strong>
                            </td>
                            <td style="max-width: 300px;"><small class="text-secondary"><?php echo htmlspecialchars($rev['comment']); ?></small></td>
                            <td><small class="text-muted"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></small></td>
                            <td>
                                <a href="index.php?route=delete_review&id=<?php echo $rev['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Delete this customer review?')" title="Delete Review">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No reviews found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
