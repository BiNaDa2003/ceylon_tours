<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section text-white text-center position-relative" style="background: url('assets/train.jpg') center/cover no-repeat;">
    <div class="hero-overlay"></div>
    <div class="container hero-content py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <span class="hero-badge animate-up"><i class="fas fa-gem text-accent me-2"></i>Explore Sri Lanka</span>
                <h1 class="display-3 fw-bold mb-4 brand-font animate-up delay-1">Discover The Paradise Island</h1>
                <p class="lead mb-5 animate-up delay-2 opacity-90 mx-auto" style="max-width: 700px;">
                    From ancient rock fortresses and sacred temples to misty tea hills and golden beaches. Book unforgettable experiences in Sri Lanka.
                </p>

                <!-- Search Bar Card -->
                <div class="search-card p-4 p-md-4 text-dark text-start animate-up delay-3">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="packages">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1"><i class="fas fa-search me-1 text-primary"></i> Keyword / Tour</label>
                            <input type="text" name="search" class="form-control bg-light" placeholder="e.g. Sigiriya, Ella, Safari...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1"><i class="fas fa-map-marker-alt me-1 text-primary"></i> Destination</label>
                            <select name="destination" class="form-select bg-light">
                                <option value="">All Destinations</option>
                                <option value="Sigiriya">Sigiriya</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Galle">Galle</option>
                                <option value="Ella">Ella</option>
                                <option value="Koggala">Koggala</option>
                                <option value="Pinnawala">Pinnawala</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1"><i class="fas fa-tags me-1 text-primary"></i> Category</label>
                            <select name="category" class="form-select bg-light">
                                <option value="">All Categories</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Wildlife">Wildlife</option>
                                <option value="Beach">Beach</option>
                                <option value="Family">Family</option>
                                <option value="Religious">Religious</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Hero Stats -->
                <div class="row hero-stats justify-content-center text-center animate-up delay-4">
                    <div class="col-4 col-md-3 stat-item">
                        <h3>50+</h3>
                        <p>Unique Destinations</p>
                    </div>
                    <div class="col-4 col-md-3 stat-item">
                        <h3>10k+</h3>
                        <p>Happy Travelers</p>
                    </div>
                    <div class="col-4 col-md-3 stat-item">
                        <h3>4.9 ★</h3>
                        <p>Customer Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tour Packages Section -->
<section class="section-py bg-light">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Handpicked Experiences</span>
            <h2 class="display-5 fw-bold brand-font">Featured Tour Packages</h2>
            <div class="divider"></div>
        </div>

        <div class="row g-4">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card tour-card h-100">
                            <div class="card-img-wrapper">
                                <span class="badge category-badge badge-<?php echo strtolower($pkg['category'] ?? 'cultural'); ?> rounded-pill px-3 py-2">
                                    <?php echo htmlspecialchars($pkg['category'] ?? 'Cultural'); ?>
                                </span>
                                <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $pkg['id']; ?>, this)" title="Add to Favorites">
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
                                        <small class="text-muted d-block fs-7">Starting from</small>
                                        <span class="fw-bold text-primary fs-5">Rs. <?php echo number_format($pkg['price'], 0); ?></span>
                                    </div>
                                    <a href="index.php?route=package_details&id=<?php echo $pkg['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                        View Details <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="index.php?route=packages" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                Explore All Packages <i class="fas fa-compass ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Custom Tour Builder Callout -->
<section class="section-py bg-primary text-white position-relative overflow-hidden">
    <div class="container position-relative z-index-1">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold text-uppercase mb-3">Tailor-Made Trips</span>
                <h2 class="display-5 fw-bold brand-font mb-3">Can't Find Your Perfect Tour? Build Custom Package!</h2>
                <p class="lead opacity-90 mb-4">Select your destinations, preferred activities (safari, hiking, beach relax, cultural visits), duration, and get an instant estimated price quote.</p>
                <a href="index.php?route=custom_package" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fas fa-magic me-2"></i> Start Custom Builder
                </a>
            </div>
            <div class="col-lg-5 text-center">
                <img src="assets/Galle fort.png" alt="Custom Tour" class="img-fluid rounded-4 shadow-lg border border-4 border-white" style="max-height: 320px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations Strip -->
<section class="section-py">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Popular Spots</span>
            <h2 class="display-5 fw-bold brand-font">Top Sri Lankan Destinations</h2>
            <div class="divider"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="index.php?route=packages&destination=Sigiriya" class="text-decoration-none">
                    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height: 250px;">
                        <img src="assets/Sigiriya.png" class="w-100 h-100 object-fit-cover" alt="Sigiriya">
                        <div class="position-absolute inset-0 bg-dark opacity-40"></div>
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold mb-0">Sigiriya Rock</h4>
                            <small>Ancient Fortress & World Heritage</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?route=packages&destination=Kandy" class="text-decoration-none">
                    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height: 250px;">
                        <img src="assets/Temple of the tooth relic Kandy.png" class="w-100 h-100 object-fit-cover" alt="Kandy">
                        <div class="position-absolute inset-0 bg-dark opacity-40"></div>
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold mb-0">Kandy Sacred City</h4>
                            <small>Temple of the Tooth & Cultural Capital</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?route=packages&destination=Ella" class="text-decoration-none">
                    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height: 250px;">
                        <img src="assets/Ella.png" class="w-100 h-100 object-fit-cover" alt="Ella">
                        <div class="position-absolute inset-0 bg-dark opacity-40"></div>
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold mb-0">Ella Highlands</h4>
                            <small>Nine Arch Bridge & Tea Estates</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- AJAX Wishlist Toggle JS Script -->
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
