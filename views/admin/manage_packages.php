<?php require_once 'includes/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-dark">Package Management</h3>
        <p class="text-muted small mb-0">Create, update, and manage Sri Lankan tour packages</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addPackageModal">
        <i class="fas fa-plus me-1"></i> Add New Package
    </button>
</div>

<!-- Packages Table Card -->
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Title & Destination</th>
                    <th>Category</th>
                    <th>Difficulty</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Slots</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $pkg): ?>
                        <tr>
                            <td>
                                <img src="assets/<?php echo !empty($pkg['image']) ? htmlspecialchars($pkg['image']) : 'Sigiriya.png'; ?>" class="rounded-3 object-fit-cover" style="width: 50px; height: 50px;" alt="Image">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($pkg['title']); ?></strong>
                                <small class="d-block text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($pkg['destination']); ?></small>
                            </td>
                            <td><span class="badge category-badge badge-<?php echo strtolower($pkg['category'] ?? 'cultural'); ?> rounded-pill px-2 py-1"><?php echo htmlspecialchars($pkg['category'] ?? 'Cultural'); ?></span></td>
                            <td><span class="badge badge-<?php echo strtolower($pkg['difficulty_level'] ?? 'easy'); ?> rounded-pill px-2 py-1"><?php echo htmlspecialchars($pkg['difficulty_level'] ?? 'Easy'); ?></span></td>
                            <td class="fw-bold text-primary">Rs. <?php echo number_format($pkg['price'], 0); ?></td>
                            <td><?php echo htmlspecialchars($pkg['duration']); ?> Days</td>
                            <td><?php echo htmlspecialchars($pkg['available_slots']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary rounded-circle me-1" onclick='editPackage(<?php echo json_encode($pkg); ?>)' title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="index.php?route=admin_delete_package&id=<?php echo $pkg['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this package?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No tour packages found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Add New Tour Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?route=admin_add_package" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Package Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Sigiriya Rock & Dambulla Cave Tour" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Destination</label>
                            <input type="text" name="destination" class="form-control" placeholder="e.g. Sigiriya, Sri Lanka" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Adventure">Adventure</option>
                                <option value="Cultural" selected>Cultural</option>
                                <option value="Wildlife">Wildlife</option>
                                <option value="Beach">Beach</option>
                                <option value="Family">Family</option>
                                <option value="Religious">Religious</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                            <select name="difficulty_level" class="form-select" required>
                                <option value="Easy" selected>Easy</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Challenging">Challenging</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (Rs.)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="15000" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duration (Days)</label>
                            <input type="number" name="duration" class="form-control" placeholder="3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Available Slots</label>
                            <input type="number" name="available_slots" class="form-control" placeholder="20" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Main Cover Image File</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3" required placeholder="Describe the itinerary highlights..."></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Included Services (Comma separated)</label>
                            <textarea name="includes_services" class="form-control" rows="2" placeholder="Guide, Transport, Entrance tickets..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excluded Services (Comma separated)</label>
                            <textarea name="excluded_services" class="form-control" rows="2" placeholder="Flights, Personal expenses, Tips..."></textarea>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured_check" checked>
                        <label class="form-check-label fw-semibold" for="is_featured_check">Show as Featured on Home Page</label>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Edit Tour Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?route=admin_update_package" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Package Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Destination</label>
                            <input type="text" name="destination" id="edit_destination" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <option value="Adventure">Adventure</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Wildlife">Wildlife</option>
                                <option value="Beach">Beach</option>
                                <option value="Family">Family</option>
                                <option value="Religious">Religious</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                            <select name="difficulty_level" id="edit_difficulty_level" class="form-select" required>
                                <option value="Easy">Easy</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Challenging">Challenging</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (Rs.)</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duration (Days)</label>
                            <input type="number" name="duration" id="edit_duration" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Available Slots</label>
                            <input type="number" name="available_slots" id="edit_available_slots" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Change Image File</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Included Services</label>
                            <textarea name="includes_services" id="edit_includes_services" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excluded Services</label>
                            <textarea name="excluded_services" id="edit_excluded_services" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_is_featured">
                        <label class="form-check-label fw-semibold" for="edit_is_featured">Featured Package</label>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPackage(pkg) {
    document.getElementById('edit_id').value = pkg.id;
    document.getElementById('edit_title').value = pkg.title;
    document.getElementById('edit_destination').value = pkg.destination;
    document.getElementById('edit_category').value = pkg.category || 'Cultural';
    document.getElementById('edit_difficulty_level').value = pkg.difficulty_level || 'Easy';
    document.getElementById('edit_price').value = pkg.price;
    document.getElementById('edit_duration').value = pkg.duration;
    document.getElementById('edit_available_slots').value = pkg.available_slots;
    document.getElementById('edit_description').value = pkg.description;
    document.getElementById('edit_includes_services').value = pkg.includes_services || '';
    document.getElementById('edit_excluded_services').value = pkg.excluded_services || '';
    document.getElementById('edit_is_featured').checked = (pkg.is_featured == 1);

    var modal = new bootstrap.Modal(document.getElementById('editPackageModal'));
    modal.show();
}
</script>

<?php require_once 'includes/admin_footer.php'; ?>
