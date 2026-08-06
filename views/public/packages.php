<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header text-white text-center" style="background: url('assets/Galle fort.png') center/cover no-repeat;">
    <div class="position-relative z-index-1">
        <h1 class="display-4 fw-bold brand-font">Explore Tour Packages</h1>
        <p class="lead opacity-90">Find your ideal Sri Lankan adventure with advanced filters</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="filter-sidebar border">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0"><i class="fas fa-filter text-primary me-2"></i>Filters</h5>
                    <a href="index.php?route=packages" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">Reset All</a>
                </div>
                
                <form action="index.php" method="GET" id="filterForm">
                    <input type="hidden" name="route" value="packages">

                    <!-- Search Input -->
                    <div class="mb-4">
                        <h6>Search Keyword</h6>
                        <input type="text" name="search" class="form-control" placeholder="Search title..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6>Category</h6>
                        <?php 
                        $cats = ['Adventure', 'Cultural', 'Wildlife', 'Beach', 'Family', 'Religious'];
                        $selected_cat = $_GET['category'] ?? '';
                        foreach ($cats as $cat):
                        ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" value="<?php echo $cat; ?>" id="cat_<?php echo $cat; ?>" <?php echo $selected_cat === $cat ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label" for="cat_<?php echo $cat; ?>">
                                    <?php echo $cat; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <h6>Max Price</h6>
                            <span class="text-primary fw-bold small" id="priceDisplay">Rs. <?php echo number_format($_GET['max_price'] ?? 50000); ?></span>
                        </div>
                        <input type="range" class="w-100" name="max_price" min="5000" max="50000" step="2500" value="<?php echo htmlspecialchars($_GET['max_price'] ?? 50000); ?>" oninput="document.getElementById('priceDisplay').innerText = 'Rs. ' + Number(this.value).toLocaleString()">
                    </div>

                    <!-- Duration Filter -->
                    <div class="mb-4">
                        <h6>Duration (Days)</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_duration" class="form-control form-control-sm" placeholder="Min" value="<?php echo htmlspecialchars($_GET['min_duration'] ?? ''); ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_duration" class="form-control form-control-sm" placeholder="Max" value="<?php echo htmlspecialchars($_GET['max_duration'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="mb-4">
                        <h6>Minimum Rating</h6>
                        <select name="min_rating" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Any Rating</option>
                            <option value="4.5" <?php echo ($_GET['min_rating'] ?? '') === '4.5' ? 'selected' : ''; ?>>4.5 ★ & above</option>
                            <option value="4.0" <?php echo ($_GET['min_rating'] ?? '') === '4.0' ? 'selected' : ''; ?>>4.0 ★ & above</option>
                            <option value="3.5" <?php echo ($_GET['min_rating'] ?? '') === '3.5' ? 'selected' : ''; ?>>3.5 ★ & above</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                        Apply Filters <i class="fas fa-check ms-1"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Package Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0 fw-semibold">
                    Showing <span class="text-primary font-bold"><?php echo count($packages); ?></span> tour package(s)
                </p>
            </div>

            <?php if (!empty($packages)): ?>
                <div class="row g-4">
                    <?php foreach ($packages as $pkg): 
                        $inWish = in_array($pkg['id'], $wishlistIds ?? []);
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card tour-card h-100">
                                <div class="card-img-wrapper">
                                    <span class="badge category-badge badge-<?php echo strtolower($pkg['category'] ?? 'cultural'); ?> rounded-pill px-3 py-2">
                                        <?php echo htmlspecialchars($pkg['category'] ?? 'Cultural'); ?>
                                    </span>
                                    <button class="wishlist-btn <?php echo $inWish ? 'active' : ''; ?>" onclick="toggleWishlist(<?php echo $pkg['id']; ?>, this)" title="Add to Favorites">
                                        <i class="fas fa-heart"></i>
                                    </button>
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
                                        <a href="index.php?route=package_details&id=<?php echo $pkg['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                            Details <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="fas fa-search fa-4x text-muted mb-3 opacity-30"></i>
                    <h4>No Packages Found</h4>
                    <p class="text-muted">Try adjusting your filters or search keywords.</p>
                    <a href="index.php?route=packages" class="btn btn-primary rounded-pill px-4">Reset Filters</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleWishlist(packageId, btn) {
    fetch('index.php?route=toggle_wishlist', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'package_id=' + packageId
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            window.location.href = data.redirect || 'index.php?route=login';
            return;
        }
        if (data.inWishlist) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    })
    .catch(err => console.error(err));
}
</script>

<?php require_once 'includes/footer.php'; ?>
