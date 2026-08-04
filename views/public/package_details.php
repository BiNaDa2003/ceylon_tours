<?php require_once 'includes/header.php'; ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?route=home">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?route=packages">Packages</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($this->package->title); ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Title & Meta Header -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge category-badge badge-<?php echo strtolower($this->package->category); ?> rounded-pill px-3 py-2 me-2">
                        <?php echo htmlspecialchars($this->package->category); ?>
                    </span>
                    <span class="badge badge-<?php echo strtolower($this->package->difficulty_level); ?> rounded-pill px-3 py-2">
                        <?php echo htmlspecialchars($this->package->difficulty_level); ?> Difficulty
                    </span>
                    <h1 class="fw-bold display-6 brand-font mt-2"><?php echo htmlspecialchars($this->package->title); ?></h1>
                    <p class="text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($this->package->destination); ?></p>
                </div>
                <button class="btn btn-outline-danger rounded-circle p-2 <?php echo $inWishlist ? 'active bg-danger text-white' : ''; ?>" onclick="toggleWishlist(<?php echo $this->package->id; ?>, this)" title="Add to Wishlist" style="width: 45px; height: 45px;">
                    <i class="fas fa-heart fs-5"></i>
                </button>
            </div>

            <!-- Image Gallery Carousel -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <?php if (!empty($images) && count($images) > 1): ?>
                    <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php foreach ($images as $idx => $img): ?>
                                <button type="button" data-bs-target="#packageCarousel" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner" style="max-height: 450px;">
                            <?php foreach ($images as $idx => $img): ?>
                                <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                                    <img src="assets/<?php echo htmlspecialchars($img['image_path']); ?>" class="d-block w-100 object-fit-cover" alt="Gallery Image" style="height: 450px;">
                                    <?php if (!empty($img['caption'])): ?>
                                        <div class="carousel-caption d-none d-md-block bg-dark opacity-75 rounded-3 p-2">
                                            <p class="m-0 small"><?php echo htmlspecialchars($img['caption']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                <?php else: ?>
                    <img src="assets/<?php echo !empty($this->package->image) ? htmlspecialchars($this->package->image) : 'Sigiriya.png'; ?>" class="img-fluid w-100 object-fit-cover" alt="Main Package Image" style="max-height: 450px;">
                <?php endif; ?>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs detail-tabs mb-4 border-bottom" id="packageTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab"><i class="fas fa-info-circle me-2"></i>Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button" role="tab"><i class="fas fa-map me-2"></i>Day-by-Day Itinerary</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab"><i class="fas fa-concierge-bell me-2"></i>Included / Excluded</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab"><i class="fas fa-star me-2"></i>Reviews (<?php echo count($reviews); ?>)</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="packageTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <h5 class="fw-bold mb-3">About This Tour</h5>
                    <p class="text-secondary leading-relaxed mb-4"><?php echo nl2br(htmlspecialchars($this->package->description)); ?></p>
                    
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-4 text-center">
                                <i class="fas fa-clock text-primary fs-3 mb-2"></i>
                                <small class="d-block text-muted">Duration</small>
                                <span class="fw-bold"><?php echo htmlspecialchars($this->package->duration); ?> Days</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-4 text-center">
                                <i class="fas fa-users text-primary fs-3 mb-2"></i>
                                <small class="d-block text-muted">Available Slots</small>
                                <span class="fw-bold"><?php echo htmlspecialchars($this->package->available_slots); ?> Left</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-4 text-center">
                                <i class="fas fa-star text-warning fs-3 mb-2"></i>
                                <small class="d-block text-muted">Avg Rating</small>
                                <span class="fw-bold"><?php echo number_format($rating_data['avg'], 1); ?> / 5.0</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-4 text-center">
                                <i class="fas fa-mountain text-primary fs-3 mb-2"></i>
                                <small class="d-block text-muted">Level</small>
                                <span class="fw-bold"><?php echo htmlspecialchars($this->package->difficulty_level); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary Tab -->
                <div class="tab-pane fade" id="itinerary" role="tabpanel">
                    <h5 class="fw-bold mb-4">Tour Day-by-Day Schedule</h5>
                    <?php if (!empty($itinerary)): ?>
                        <div class="itinerary-timeline">
                            <?php foreach ($itinerary as $item): ?>
                                <div class="itinerary-day">
                                    <div class="day-badge"><?php echo htmlspecialchars($item['day_number']); ?></div>
                                    <div class="day-content">
                                        <h6>Day <?php echo htmlspecialchars($item['day_number']); ?>: <?php echo htmlspecialchars($item['title']); ?></h6>
                                        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Day-by-day itinerary details will be provided upon booking confirmation.</p>
                    <?php endif; ?>
                </div>

                <!-- Included / Excluded Tab -->
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-success-subtle rounded-4 h-100">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-check-circle me-2"></i>What's Included</h6>
                                <ul class="list-unstyled includes-list m-0">
                                    <?php 
                                    $inc = array_filter(explode(',', $this->package->includes_services));
                                    if (!empty($inc)):
                                        foreach ($inc as $item):
                                    ?>
                                        <li><i class="fas fa-check text-success mt-1"></i> <span><?php echo htmlspecialchars(trim($item)); ?></span></li>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <li><i class="fas fa-check text-success mt-1"></i> <span>Professional English-speaking tour guide</span></li>
                                        <li><i class="fas fa-check text-success mt-1"></i> <span>Air-conditioned transportation</span></li>
                                        <li><i class="fas fa-check text-success mt-1"></i> <span>Entrance tickets to attraction sites</span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-danger-subtle rounded-4 h-100">
                                <h6 class="fw-bold text-danger mb-3"><i class="fas fa-times-circle me-2"></i>What's Excluded</h6>
                                <ul class="list-unstyled excludes-list m-0">
                                    <?php 
                                    $exc = array_filter(explode(',', $this->package->excluded_services));
                                    if (!empty($exc)):
                                        foreach ($exc as $item):
                                    ?>
                                        <li><i class="fas fa-times text-danger mt-1"></i> <span><?php echo htmlspecialchars(trim($item)); ?></span></li>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <li><i class="fas fa-times text-danger mt-1"></i> <span>Personal shopping expenses</span></li>
                                        <li><i class="fas fa-times text-danger mt-1"></i> <span>International airfare</span></li>
                                        <li><i class="fas fa-times text-danger mt-1"></i> <span>Tips and gratuities</span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <h4 class="fw-bold mb-0">Customer Reviews</h4>
                            <span class="stars me-2">
                                <?php
                                $r = round($rating_data['avg']);
                                for ($i=1; $i<=5; $i++) {
                                    echo $i <= $r ? '<i class="fas fa-star"></i>' : '<i class="far fa-star empty"></i>';
                                }
                                ?>
                            </span>
                            <span class="fw-bold"><?php echo number_format($rating_data['avg'], 1); ?></span> out of 5 (<?php echo $rating_data['total']; ?> reviews)
                        </div>
                    </div>

                    <!-- Submit Review Form -->
                    <?php if (isset($_SESSION['customer_id']) && $canReview && !$hasReviewed): ?>
                        <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-pen text-primary me-2"></i>Write a Review</h6>
                            <form action="index.php?route=store_review" method="POST">
                                <input type="hidden" name="package_id" value="<?php echo $this->package->id; ?>">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Your Rating</label>
                                    <div class="rating-input">
                                        <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4" name="rating" value="4"/><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3" name="rating" value="3"/><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2" name="rating" value="2"/><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1" name="rating" value="1"/><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Your Comment</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Submit Review</button>
                            </form>
                        </div>
                    <?php elseif ($hasReviewed): ?>
                        <div class="alert alert-info rounded-3 mb-4"><i class="fas fa-info-circle me-2"></i>You have already submitted a review for this package.</div>
                    <?php endif; ?>

                    <!-- Reviews List -->
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="review-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="reviewer-avatar"><?php echo strtoupper(substr($rev['customer_name'], 0, 1)); ?></div>
                                        <div>
                                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($rev['customer_name']); ?></h6>
                                            <small class="text-muted"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></small>
                                        </div>
                                    </div>
                                    <span class="stars">
                                        <?php
                                        for ($i=1; $i<=5; $i++) {
                                            echo $i <= $rev['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star empty"></i>';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <p class="text-secondary mb-0 small ms-5 ps-2"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No reviews yet for this tour. Be the first to book and review!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Sticky Booking Box Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px;">
                <div class="d-flex justify-content-between align-items-baseline mb-3">
                    <span class="text-muted fs-7">Price per traveler</span>
                    <h3 class="fw-bold text-primary brand-font mb-0">Rs. <?php echo number_format($this->package->price, 0); ?></h3>
                </div>

                <div class="bg-light rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span><i class="fas fa-clock me-1"></i>Duration:</span>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($this->package->duration); ?> Days</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span><i class="fas fa-users me-1"></i>Available Slots:</span>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($this->package->available_slots); ?> slots</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span><i class="fas fa-shield-alt me-1"></i>Free Cancellation:</span>
                        <span class="fw-bold text-success">Up to 48h before</span>
                    </div>
                </div>

                <a href="index.php?route=book&id=<?php echo $this->package->id; ?>" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm mb-3">
                    Book Now <i class="fas fa-arrow-right ms-2"></i>
                </a>

                <div class="text-center text-muted small">
                    <i class="fas fa-lock me-1"></i> Instant confirmation & secure booking
                </div>
            </div>
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
            btn.classList.add('active', 'bg-danger', 'text-white');
        } else {
            btn.classList.remove('active', 'bg-danger', 'text-white');
        }
    })
    .catch(err => console.error(err));
}
</script>

<?php require_once 'includes/footer.php'; ?>
