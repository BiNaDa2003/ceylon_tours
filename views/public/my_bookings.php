<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header text-white text-center" style="background: url('assets/Sigiriya.png') center/cover no-repeat;">
    <div class="position-relative z-index-1">
        <h1 class="display-4 fw-bold brand-font"><i class="fas fa-suitcase-rolling me-2 text-accent"></i>My Bookings</h1>
        <p class="lead opacity-90">Manage your tour reservations and view confirmation receipts</p>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($bookings)): ?>
        <div class="row g-4">
            <?php foreach ($bookings as $b): ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                        <div class="row g-0 h-100">
                            <div class="col-4">
                                <img src="assets/<?php echo !empty($b['image']) ? htmlspecialchars($b['image']) : 'Sigiriya.png'; ?>" class="w-100 h-100 object-fit-cover" alt="Package Image" style="min-height: 180px;">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4 d-flex flex-column h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <small class="text-muted text-uppercase fw-bold fs-7">#SRI-<?php echo str_pad($b['id'], 6, '0', STR_PAD_LEFT); ?></small>
                                        <?php 
                                        $statusBadge = 'bg-warning text-dark';
                                        if ($b['booking_status'] === 'Confirmed') $statusBadge = 'bg-success text-white';
                                        if ($b['booking_status'] === 'Cancelled') $statusBadge = 'bg-danger text-white';
                                        ?>
                                        <span class="badge <?php echo $statusBadge; ?> rounded-pill px-3 py-1 fw-bold">
                                            <?php echo htmlspecialchars($b['booking_status']); ?>
                                        </span>
                                    </div>
                                    
                                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($b['package_title']); ?></h5>
                                    <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($b['destination']); ?></p>

                                    <div class="bg-light rounded-3 p-2 mb-3 small">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Travel Date:</span>
                                            <strong><?php echo date('M d, Y', strtotime($b['travel_date'])); ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Travelers:</span>
                                            <strong><?php echo htmlspecialchars($b['travelers']); ?> Person(s)</strong>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                        <a href="index.php?route=booking_confirmation&id=<?php echo $b['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            Receipt <i class="fas fa-receipt ms-1"></i>
                                        </a>

                                        <?php if ($b['booking_status'] === 'Pending'): ?>
                                            <a href="index.php?route=cancel_booking&id=<?php echo $b['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                Cancel
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fas fa-calendar-times fa-4x text-muted opacity-30 mb-3"></i>
            <h4>No Bookings Found</h4>
            <p class="text-muted mb-4">You have not booked any tour packages yet.</p>
            <a href="index.php?route=packages" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                Explore Packages
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
