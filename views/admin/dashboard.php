<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Dashboard Overview</h3>
        <p class="text-muted small mb-0">System performance metrics and key statistics</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Print Summary</button>
</div>

<!-- Stat Cards Grid -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card bg-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase opacity-75 mb-1 fs-7 fw-bold">Total Packages</h6>
                    <h2 class="display-6 fw-bold mb-0"><?php echo number_format($stats['total_packages']); ?></h2>
                </div>
                <div class="stat-icon"><i class="fas fa-box"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card bg-success text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase opacity-75 mb-1 fs-7 fw-bold">Total Bookings</h6>
                    <h2 class="display-6 fw-bold mb-0"><?php echo number_format($stats['total_bookings']); ?></h2>
                </div>
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card bg-warning text-dark p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase opacity-75 mb-1 fs-7 fw-bold">Total Customers</h6>
                    <h2 class="display-6 fw-bold mb-0"><?php echo number_format($stats['total_customers']); ?></h2>
                </div>
                <div class="stat-icon bg-dark text-white"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card bg-dark text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase opacity-75 mb-1 fs-7 fw-bold text-accent">Total Revenue</h6>
                    <h3 class="fw-bold mb-0 text-accent">Rs. <?php echo number_format($stats['total_revenue']); ?></h3>
                </div>
                <div class="stat-icon bg-white text-dark"><i class="fas fa-coins"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics & Recent Activity Row -->
<div class="row g-4 mb-4">
    <!-- Chart.js Monthly Bookings -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-chart-bar text-primary me-2"></i>Monthly Booking Trends</h5>
            <div style="height: 280px; position: relative;">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stat Badges & Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="fas fa-tasks text-primary me-2"></i>Action Items</h5>
            <div class="list-group list-group-flush">
                <a href="index.php?route=admin_bookings" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 py-3">
                    <span><i class="fas fa-hourglass-half text-warning me-2"></i>Pending Bookings</span>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold"><?php echo $stats['pending_bookings']; ?></span>
                </a>
                <a href="index.php?route=admin_custom_packages" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 py-3">
                    <span><i class="fas fa-magic text-info me-2"></i>Pending Custom Requests</span>
                    <span class="badge bg-info text-white rounded-pill px-3 py-1 fw-bold"><?php echo $stats['pending_custom']; ?></span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="fas fa-clock text-primary me-2"></i>Recent Reservations</h5>
        <a href="index.php?route=admin_bookings" class="btn btn-sm btn-outline-primary rounded-pill">View All Bookings</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Package Title</th>
                    <th>Travel Date</th>
                    <th>Travelers</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stats['recent_bookings'])): ?>
                    <?php foreach ($stats['recent_bookings'] as $rb): ?>
                        <tr>
                            <td><strong>#SRI-<?php echo str_pad($rb['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo htmlspecialchars($rb['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($rb['package_title'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($rb['travel_date'])); ?></td>
                            <td><?php echo htmlspecialchars($rb['travelers']); ?></td>
                            <td>
                                <?php 
                                $badge = 'bg-warning text-dark';
                                if ($rb['booking_status'] === 'Confirmed') $badge = 'bg-success text-white';
                                if ($rb['booking_status'] === 'Cancelled') $badge = 'bg-danger text-white';
                                ?>
                                <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-1"><?php echo htmlspecialchars($rb['booking_status']); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No bookings record found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('bookingsChart').getContext('2d');
    const labels = <?php echo json_encode($stats['monthly_labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']); ?>;
    const dataCounts = <?php echo json_encode($stats['monthly_counts'] ?? [2, 5, 8, 12, 15, 20]); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Recent Months'],
            datasets: [{
                label: 'Bookings Count',
                data: dataCounts.length ? dataCounts : [1],
                backgroundColor: '#0f7b6c',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
});
</script>

<?php require_once 'includes/admin_footer.php'; ?>
