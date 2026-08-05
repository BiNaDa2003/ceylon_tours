<?php require_once 'includes/header.php'; ?>

<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white text-center py-4 border-0">
                    <h3 class="fw-bold brand-font mb-0">Create an Account</h3>
                    <p class="mb-0 small opacity-75">Join Ceylon Tours and start exploring</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger rounded-3 text-center small fw-semibold"><i class="fas fa-exclamation-circle me-1"></i><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="index.php?route=register" method="POST" id="registerForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 py-2" required placeholder="Malshi Navodya">
                            </div>
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 py-2" required placeholder="malshi@example.com">
                            </div>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="phone" class="form-control bg-light border-start-0 py-2" required placeholder="1234567890" pattern="[0-9]{10,15}">
                            </div>
                            <div class="invalid-feedback">Please enter a valid phone number.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="reg-password" class="form-control bg-light border-start-0 py-2" required placeholder="••••••••" minlength="6">
                            </div>
                            <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                            <label class="form-check-label text-muted small" for="termsCheck">
                                I agree to the <a href="#" class="text-primary text-decoration-none">Terms of Service</a> and <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>
                            </label>
                            <div class="invalid-feedback">You must agree to the terms to register.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mb-4">Create Account</button>
                        
                        <div class="text-center text-muted small">
                            Already have an account? <a href="index.php?route=login" class="fw-bold text-primary text-decoration-none">Sign in here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
