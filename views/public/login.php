<?php require_once 'includes/header.php'; ?>

<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8 col-lg-5">
            <!-- Login card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Login header -->
                <div class="card-header bg-primary text-white text-center py-4 border-0">
                    <div class="mb-2">
                        <i class="fas fa-compass fa-2x text-white opacity-75"></i>
                    </div>
                    <h3 class="fw-bold brand-font mb-1">Welcome Back</h3>
                    <p class="mb-0 small opacity-75">Sign in to your Ceylon Tours account</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <!-- Display messages -->
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger rounded-3 small fw-semibold border-0 py-2">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success rounded-3 small fw-semibold border-0 py-2">
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Login form -->
                    <form action="index.php?route=login" method="POST">

                        <!-- Email input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">
                                Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text"
                                       name="identifier"
                                       class="form-control bg-light border-start-0 py-2"
                                       required
                                       placeholder="malshi@example.com"
                                       autocomplete="username"
                                       value="<?php echo htmlspecialchars($_POST['identifier'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       id="passwordField"
                                       class="form-control bg-light border-start-0 py-2"
                                       required
                                       placeholder="••••••••"
                                       autocomplete="current-password">
                                <button class="input-group-text bg-light border-start-0 text-muted"
                                        type="button"
                                        onclick="togglePwd()"
                                        title="Show/hide password">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>

                        <!-- Registration link -->
                        <div class="text-center text-muted small">
                            Don't have an account?
                            <a href="index.php?route=register" class="fw-bold text-primary text-decoration-none">Sign up now</a>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light text-center py-3 border-0">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-primary me-1"></i>
                        Secure login - your information is protected
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePwd() {
    const f = document.getElementById('passwordField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') {
        f.type = 'text';
        i.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        f.type = 'password';
        i.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>