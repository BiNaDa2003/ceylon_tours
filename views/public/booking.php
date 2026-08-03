<?php require_once 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold brand-font text-primary mb-3">Book Your Tour</h1>
                <p class="lead text-muted">Complete the form below to secure your spot for <strong><?php echo htmlspecialchars($this->package->title); ?></strong>.</p>
            </div>
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-4 bg-light p-4 border-end">
                        <h5 class="fw-bold mb-4">Tour Summary</h5>
                        <img src="assets/images/placeholder-tour.jpg" class="img-fluid rounded-3 mb-3 shadow-sm" alt="Tour">
                        <h6 class="fw-bold text-primary"><?php echo htmlspecialchars($this->package->title); ?></h6>
                        <ul class="list-unstyled text-muted small mt-3">
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($this->package->destination); ?></li>
                            <li class="mb-2"><i class="far fa-clock me-2"></i> <?php echo htmlspecialchars($this->package->duration); ?> Days</li>
                            <li class="mb-2"><i class="fas fa-tag me-2"></i> Rs. <?php echo htmlspecialchars($this->package->price); ?> / person</li>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold">Total Estimate:</span>
                            <span class="fw-bold text-success fs-5" id="total-price">Rs. 0.00</span>
                        </div>
                    </div>
                    
                    <div class="col-md-8 p-4 p-md-5">
                        <form action="index.php?route=store_booking" method="POST" id="bookingForm" class="needs-validation" novalidate>
                            <input type="hidden" name="package_id" value="<?php echo $this->package->id; ?>">
                            <input type="hidden" id="package-price" value="<?php echo $this->package->price; ?>">
                            
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Booking Details</h5>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Travel Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control bg-light" name="travel_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                    <div class="invalid-feedback">Please select a valid future travel date.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Number of Travelers <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control bg-light" name="travelers" id="travelers-input" min="1" max="<?php echo $this->package->available_slots; ?>" required value="1">
                                    <div class="invalid-feedback">Please enter a valid number of travelers (Max: <?php echo $this->package->available_slots; ?>).</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Special Requests (Optional)</label>
                                <textarea class="form-control bg-light" name="special_requests" rows="3" placeholder="Dietary requirements, accessibility needs, etc."></textarea>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="termsCheck" required>
                                <label class="form-check-label text-muted small" for="termsCheck">
                                    I agree to the <a href="#" class="text-primary text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-primary text-decoration-none">Cancellation Policy</a>.
                                </label>
                                <div class="invalid-feedback">You must agree before submitting.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">Confirm Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Calculate total price dynamically
    document.addEventListener('DOMContentLoaded', function() {
        const travelersInput = document.getElementById('travelers-input');
        const pricePerPerson = parseFloat(document.getElementById('package-price').value);
        const totalPriceEl = document.getElementById('total-price');
        
        function updateTotal() {
            const num = parseInt(travelersInput.value) || 0;
            const total = num * pricePerPerson;
            totalPriceEl.textContent = 'Rs. ' + total.toFixed(2);
        }
        
        travelersInput.addEventListener('input', updateTotal);
        updateTotal(); // Initial calc
    });
</script>

<?php require_once 'includes/footer.php'; ?>
