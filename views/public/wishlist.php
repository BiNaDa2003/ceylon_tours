<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header text-white text-center" style="background: url('assets/Ella.png') center/cover no-repeat;">
    <div class="position-relative z-index-1">
        <h1 class="display-4 fw-bold brand-font"><i class="fas fa-heart text-danger me-2"></i>My Favorite Tours</h1>
        <p class="lead opacity-90">Packages you have saved for your upcoming Sri Lankan journey</p>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($packages)): ?>
        <div class="row g-4">
            <?php foreach ($packages as $pkg): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card tour-card h-100">
                        <div class="card-img-wrapper">
                            <span class="badge category-badge badge-<?php echo strtolower($pkg['category'] ?? 'cultural'); ?> rounded-pill px-3 py-2">
                                <?php echo htmlspecialchars($pkg['category'] ?? 'Cultural'); ?>
                            </span>
                            <form action="index.php?route=toggle_wishlist" method="POST" class="d-inline">
                                <input type="hidden" name="package_id" value="<?php echo $pkg['id']; ?>">
                                <button type="submit" class="wishlist-btn active" title="Remove from Favorites">
                                    <i class="fas fa-heart text-danger"></i>
                                </button>
                            </form>
                            <img src="assets/<?php echo !empty($pkg['image']) ? htmlspecialchars($pkg['image']) : 'Sigiriya.png'; ?>" alt="<?php echo htmlspecialchars($pkg['title']); ?>">
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="stars">
                                    <?php
                                    $r = round($pkg['rating'] ?? 4.5);
                                    for ($i=1; $i<=5; $i++) {
                                        echo $i <= $r ? '<i class="fas fa-star"></i>' : '<i class="far fa-star empty"></i>';
                                    }
                                    ?>
                                    <small class="text-muted ms-1">(<?php echo number_format($pkg['rating'] ?? 4.5, 1); ?>)</small>
                                </span>
                                <small class="text-muted fw-semibold"><i class="fas fa-clock text-primary me-1"></i><?php echo htmlspecialchars($pkg['duration']); ?> Days</small>
                            </div>
                            <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($pkg['title']); ?></h5>
                            <p class="text-muted small mb-3 flex-grow-1"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($pkg['destination']); ?></p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <small class="text-muted d-block fs-7">Price</small>
                                    <span class="fw-bold text-primary fs-5">Rs. <?php echo number_format($pkg['price'], 0); ?></span>
                                </div>
                                <a href="index.php?route=package_details&id=<?php echo $pkg['id']; ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    View Package <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="wishlist-empty bg-white rounded-4 shadow-sm">
            <i class="fas fa-heart-broken text-muted"></i>
            <h4 class="fw-bold">Your Wishlist is Empty</h4>
            <p class="text-muted mb-4">You haven't saved any tour packages to your favorites yet.</p>
            <a href="index.php?route=packages" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                <i class="fas fa-compass me-2"></i> Browse Tour Packages
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
