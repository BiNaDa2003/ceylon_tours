<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Ceylon Tours Tours</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin-body">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar min-vh-100">
                <div class="sidebar-brand">
                   
                    <div>
                        <div class="fw-bold lh-1">Ceylon Tours</div>
                        <small class="text-white-50 fs-7 fw-normal">Admin Panel</small>
                    </div>
                </div>
                <div class="px-2 pt-3">
                    <a href="index.php?route=admin_dashboard" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_dashboard') ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="index.php?route=admin_packages" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_packages') ? 'active' : '' ?>">
                        <i class="fas fa-box me-2"></i> Packages
                    </a>
                    <a href="index.php?route=admin_bookings" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_bookings') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-check me-2"></i> Bookings
                    </a>
                    <a href="index.php?route=admin_customers" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_customers') ? 'active' : '' ?>">
                        <i class="fas fa-users me-2"></i> Customers
                    </a>
                    <a href="index.php?route=admin_reviews" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_reviews') ? 'active' : '' ?>">
                        <i class="fas fa-star me-2"></i> Reviews
                    </a>
                    <a href="index.php?route=admin_custom_packages" class="sidebar-link <?= (isset($_GET['route']) && $_GET['route'] == 'admin_custom_packages') ? 'active' : '' ?>">
                        <i class="fas fa-magic me-2"></i> Custom Requests
                    </a>
                    <a href="index.php?route=admin_logout" class="sidebar-link mt-5 text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-wrapper p-4">
                <!-- Top Navbar -->
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-cog text-primary me-2"></i>Control Panel</h5>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small"><i class="fas fa-user-shield me-1"></i> <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                        <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill" target="_blank"><i class="fas fa-external-link-alt me-1"></i> View Site</a>
                    </div>
                </div>

                <!-- Toast Notifications for Admin -->
                <?php if(isset($_SESSION['success_msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error_msg'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
