<?php require_once 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card confirmation-card">
                <div class="confirmation-hero">
                    <div class="check-icon">
                        <i class="fas fa-check fa-2x text-white"></i>
                    </div>
                    <h2 class="fw-bold brand-font mb-2">Booking Submitted!</h2>
                    <p class="opacity-90 mb-0">Thank you for booking with Ceylon Tours Tours. Your reservation details are below.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Booking Reference Box -->
                    <div class="text-center mb-4">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Booking Reference ID</small>
                        <div class="booking-ref d-inline-block">#SRI-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></div>
                    </div>

                    <div class="row g-4 mb-4 border-top pt-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted small text-uppercase mb-3">Tour Details</h6>
                            <div class="d-flex align-items-center mb-3">
                                <img src="assets/<?php echo !empty($booking['image']) ? htmlspecialchars($booking['image']) : 'Sigiriya.png'; ?>" class="rounded-3 me-3 object-fit-cover" style="width: 70px; height: 70px;" alt="Tour Image">
                                <div>
                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($booking['package_title']); ?></h6>
                                    <small class="text-muted d-block"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($booking['destination']); ?></small>
                                    <small class="text-muted"><i class="fas fa-clock text-primary me-1"></i><?php echo htmlspecialchars($booking['duration']); ?> Days</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted small text-uppercase mb-3">Traveler Details</h6>
                            <p class="mb-1 small"><strong>Name:</strong> <?php echo htmlspecialchars($booking['customer_name']); ?></p>
                            <p class="mb-1 small"><strong>Email:</strong> <?php echo htmlspecialchars($booking['customer_email']); ?></p>
                            <p class="mb-1 small"><strong>Travel Date:</strong> <?php echo date('F d, Y', strtotime($booking['travel_date'])); ?></p>
                            <p class="mb-1 small"><strong>Travelers:</strong> <?php echo htmlspecialchars($booking['travelers']); ?> Person(s)</p>
                        </div>
                    </div>

                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Package Price per Traveler</span>
                            <span>Rs. <?php echo number_format($booking['price'], 0); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Number of Travelers</span>
                            <span>× <?php echo htmlspecialchars($booking['travelers']); ?></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center fw-bold fs-5 text-primary">
                            <span>Total Estimated Amount</span>
                            <span>Rs. <?php echo number_format($booking['total_price'] > 0 ? $booking['total_price'] : ($booking['travelers'] * $booking['price']), 0); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted small">Booking Status</span>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1"><?php echo htmlspecialchars($booking['booking_status']); ?></span>
                        </div>
                    </div>

                    <?php if (!empty($booking['special_requests'])): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold small text-muted text-uppercase mb-2">Special Requests</h6>
                            <p class="text-secondary small bg-light p-3 rounded-3 mb-0"><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center pt-3 border-top">
                        <a href="index.php?route=my_bookings" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-suitcase-rolling me-2"></i> View My Bookings
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                            <i class="fas fa-print me-2"></i> Print Confirmation
                        </button>
                        <a href="index.php?route=home" class="btn btn-light rounded-pill px-4">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
