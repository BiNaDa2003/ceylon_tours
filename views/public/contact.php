<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="container-fluid bg-dark text-white py-5 page-header text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/Contact.png') center/cover;">
    <h1 class="display-4 fw-bold mt-5 brand-font">Contact Us</h1>
    <p class="lead mb-5">We'd love to hear from you.</p>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-3">Get In Touch</h4>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fas fa-map-marker-alt fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Our Office</h6>
                            <p class="text-muted mb-0 small">No. 45, Galle Road, Colombo 03, Sri Lanka</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fas fa-envelope fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Email Us</h6>
                            <p class="text-muted mb-0 small">info@Ceylon Tourstours.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-5">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fas fa-phone-alt fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Call Us</h6>
                            <p class="text-muted mb-0 small">077 5004567</p>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Follow Us</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="fw-bold mb-4">Send us a message</h3>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger rounded-3"><i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="index.php?route=contact" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Name</label>
                                <input type="text" name="name" class="form-control bg-light" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Email</label>
                                <input type="email" name="email" class="form-control bg-light" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" name="subject" class="form-control bg-light" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea name="message" class="form-control bg-light" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Google Map Placeholder -->
   
</div>

<?php require_once 'includes/footer.php'; ?>
