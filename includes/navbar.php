<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm py-3 transition-nav" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 brand-font" href="index.php?route=home">
            <i class="fas fa-compass me-2 text-accent"></i>Ceylon Tours
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-2">
                    <a class="nav-link <?= (isset($_GET['route']) && $_GET['route'] == 'home') || !isset($_GET['route']) ? 'active fw-bold' : '' ?>" href="index.php?route=home">Home</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link <?= (isset($_GET['route']) && $_GET['route'] == 'packages') ? 'active fw-bold' : '' ?>" href="index.php?route=packages">Tours</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link <?= (isset($_GET['route']) && $_GET['route'] == 'custom_package') ? 'active fw-bold' : '' ?>" href="index.php?route=custom_package">
                        Custom Tour
                    </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link <?= (isset($_GET['route']) && $_GET['route'] == 'about') ? 'active fw-bold' : '' ?>" href="index.php?route=about">About</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link <?= (isset($_GET['route']) && $_GET['route'] == 'contact') ? 'active fw-bold' : '' ?>" href="index.php?route=contact">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center">
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <!-- Admin is logged in -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-1 text-warning"></i>
                            Hi, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                            <li>
                                <a class="dropdown-item py-2" href="index.php?route=admin_dashboard">
                                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Admin Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="index.php?route=admin_logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php elseif(isset($_SESSION['customer_id'])): ?>
                    <!-- Customer is logged in -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="index.php?route=wishlist" title="Wishlist">
                            <i class="fas fa-heart text-danger fs-5"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Hi, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item py-2" href="index.php?route=my_bookings"><i class="fas fa-suitcase-rolling me-2 text-primary"></i> My Bookings</a></li>
                            <li><a class="dropdown-item py-2" href="index.php?route=wishlist"><i class="fas fa-heart me-2 text-danger"></i> My Favorites</a></li>
                            <li><a class="dropdown-item py-2" href="index.php?route=my_custom_packages"><i class="fas fa-sliders-h me-2 text-warning"></i> My Custom Requests</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="index.php?route=logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Not logged in -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?route=login">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-accent rounded-pill px-4 fw-bold shadow-sm" href="index.php?route=register">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
