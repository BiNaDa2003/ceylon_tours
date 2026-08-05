<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header text-white text-center" style="background: url('assets/Ella.png') center/cover no-repeat;">
    <div class="position-relative z-index-1">
        <h1 class="display-4 fw-bold brand-font"><i class="fas fa-sliders-h text-accent me-2"></i>My Custom Tour Requests</h1>
        <p class="lead opacity-90">Track status of your tailor-made itinerary requests</p>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Custom Requests History</h4>
        <a href="index.php?route=custom_package" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-plus me-1"></i> New Custom Request
        </a>
    </div>

    <?php if (!empty($custom_packages)): ?>
        <div class="row g-4">
            <?php foreach ($custom_packages as $req): ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <small class="text-muted text-uppercase fw-bold">Request #CP-<?php echo str_pad($req['id'], 5, '0', STR_PAD_LEFT); ?></small>
                                <h5 class="fw-bold text-primary mb-0 mt-1"><?php echo htmlspecialchars($req['destination']); ?></h5>
                            </div>
                            <?php 
                            $badgeClass = 'bg-warning text-dark';
                            if ($req['status'] === 'Approved') $badgeClass = 'bg-success text-white';
                            if ($req['status'] === 'Rejected') $badgeClass = 'bg-danger text-white';
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2 fw-bold">
                                <?php echo htmlspecialchars($req['status']); ?>
                            </span>
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-3 small">
                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted d-block">Duration:</span>
                                    <strong class="text-dark"><?php echo htmlspecialchars($req['duration']); ?> Days</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Estimated Quote:</span>
                                    <strong class="text-primary fs-6">Rs. <?php echo number_format($req['estimated_price'], 0); ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted fw-bold d-block mb-1">Selected Activities:</small>
                            <p class="text-secondary small mb-0"><?php echo htmlspecialchars($req['activities'] ?: 'General sightseeing'); ?></p>
                        </div>

                        <?php if (!empty($req['notes'])): ?>
                            <div class="mb-3">
                                <small class="text-muted fw-bold d-block mb-1">Your Notes:</small>
                                <p class="text-muted small fst-italic mb-0"><?php echo htmlspecialchars($req['notes']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($req['admin_notes'])): ?>
                            <div class="mt-auto pt-3 border-top">
                                <small class="text-primary fw-bold d-block mb-1"><i class="fas fa-comment-dots me-1"></i>Admin Response:</small>
                                <p class="text-dark small mb-0 bg-primary-light p-2 rounded-3"><?php echo htmlspecialchars($req['admin_notes']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fas fa-magic fa-4x text-muted opacity-30 mb-3"></i>
            <h4>No Custom Tour Requests</h4>
            <p class="text-muted mb-4">Design your own customized Sri Lanka tour experience today.</p>
            <a href="index.php?route=custom_package" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                Create Custom Tour
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
