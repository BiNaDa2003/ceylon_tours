<?php
/**
 * Package Model
 * Manages tour packages including new fields: category, difficulty,
 * includes/excludes services, rating, featured status.
 */
class Package {
    private $conn;
    private $table_name = "tour_packages";

    // Original properties
    public $id;
    public $title;
    public $destination;
    public $description;
    public $price;
    public $duration;
    public $image;
    public $available_slots;
    public $created_at;

    // New properties (Feature 1)
    public $category;
    public $difficulty_level;
    public $includes_services;
    public $excluded_services;
    public $rating;
    public $is_featured;

    public function __construct($db) {
        $this->conn = $db;
    }

    // -------------------------------------------------------
    // READ OPERATIONS
    // -------------------------------------------------------

    /**
     * Read all packages ordered by newest first.
     */
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read featured packages (is_featured = 1).
     */
    public function getFeatured($limit = 6) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE is_featured = 1 ORDER BY rating DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read a single package by ID, populating all properties.
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->title             = $row['title'];
            $this->destination       = $row['destination'];
            $this->description       = $row['description'];
            $this->price             = $row['price'];
            $this->duration          = $row['duration'];
            $this->image             = $row['image'];
            $this->available_slots   = $row['available_slots'];
            $this->category          = $row['category']          ?? 'Cultural';
            $this->difficulty_level  = $row['difficulty_level']  ?? 'Easy';
            $this->includes_services = $row['includes_services'] ?? '';
            $this->excluded_services = $row['excluded_services'] ?? '';
            $this->rating            = $row['rating']            ?? 0;
            $this->is_featured       = $row['is_featured']       ?? 0;
            return true;
        }
        return false;
    }

    /**
     * Get packages grouped by category for navigation.
     */
    public function getCategories() {
        $query = "SELECT category, COUNT(*) as count
                  FROM " . $this->table_name . " GROUP BY category ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get gallery images for a package.
     */
    public function getImages($package_id) {
        $query = "SELECT * FROM package_images WHERE package_id = ? ORDER BY is_primary DESC, created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $package_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------
    // WRITE OPERATIONS
    // -------------------------------------------------------

    /**
     * Create a new package with all fields including new ones.
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET title=:title, destination=:destination, description=:description,
                      price=:price, duration=:duration, image=:image,
                      available_slots=:available_slots, category=:category,
                      difficulty_level=:difficulty_level,
                      includes_services=:includes_services,
                      excluded_services=:excluded_services,
                      is_featured=:is_featured";
        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(':title',             $this->title);
        $stmt->bindParam(':destination',       $this->destination);
        $stmt->bindParam(':description',       $this->description);
        $stmt->bindParam(':price',             $this->price);
        $stmt->bindParam(':duration',          $this->duration);
        $stmt->bindParam(':image',             $this->image);
        $stmt->bindParam(':available_slots',   $this->available_slots);
        $stmt->bindParam(':category',          $this->category);
        $stmt->bindParam(':difficulty_level',  $this->difficulty_level);
        $stmt->bindParam(':includes_services', $this->includes_services);
        $stmt->bindParam(':excluded_services', $this->excluded_services);
        $stmt->bindParam(':is_featured',       $this->is_featured);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Update an existing package.
     */
    public function update() {
        $imageClause = $this->image ? ", image=:image" : "";
        $query = "UPDATE " . $this->table_name . "
                  SET title=:title, destination=:destination, description=:description,
                      price=:price, duration=:duration, available_slots=:available_slots,
                      category=:category, difficulty_level=:difficulty_level,
                      includes_services=:includes_services,
                      excluded_services=:excluded_services,
                      is_featured=:is_featured
                      {$imageClause}
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(':title',             $this->title);
        $stmt->bindParam(':destination',       $this->destination);
        $stmt->bindParam(':description',       $this->description);
        $stmt->bindParam(':price',             $this->price);
        $stmt->bindParam(':duration',          $this->duration);
        $stmt->bindParam(':available_slots',   $this->available_slots);
        $stmt->bindParam(':category',          $this->category);
        $stmt->bindParam(':difficulty_level',  $this->difficulty_level);
        $stmt->bindParam(':includes_services', $this->includes_services);
        $stmt->bindParam(':excluded_services', $this->excluded_services);
        $stmt->bindParam(':is_featured',       $this->is_featured);
        $stmt->bindParam(':id',                $this->id);

        if ($this->image) {
            $stmt->bindParam(':image', $this->image);
        }

        return $stmt->execute();
    }

    /**
     * Delete a package by ID.
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }

    // -------------------------------------------------------
    // SEARCH & FILTER
    // -------------------------------------------------------

    /**
     * Advanced search with category, price range, duration, and rating filters.
     */
    public function search($keyword = '', $destination = '', $max_price = '', $category = '', $min_duration = '', $max_duration = '', $min_rating = '') {
        $conditions = [];
        $params     = [];

        if (!empty($keyword)) {
            $conditions[] = "(title LIKE ? OR destination LIKE ?)";
            $kw = "%" . htmlspecialchars(strip_tags($keyword)) . "%";
            $params[] = $kw;
            $params[] = $kw;
        }
        if (!empty($destination)) {
            $conditions[] = "destination LIKE ?";
            $params[] = "%" . htmlspecialchars(strip_tags($destination)) . "%";
        }
        if (!empty($max_price)) {
            $conditions[] = "price <= ?";
            $params[] = (float)$max_price;
        }
        if (!empty($category)) {
            $conditions[] = "category = ?";
            $params[] = htmlspecialchars(strip_tags($category));
        }
        if (!empty($min_duration)) {
            $conditions[] = "duration >= ?";
            $params[] = (int)$min_duration;
        }
        if (!empty($max_duration)) {
            $conditions[] = "duration <= ?";
            $params[] = (int)$max_duration;
        }
        if (!empty($min_rating)) {
            $conditions[] = "rating >= ?";
            $params[] = (float)$min_rating;
        }

        $where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        $query = "SELECT * FROM " . $this->table_name . " {$where} ORDER BY rating DESC, created_at DESC";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $i => $val) {
            $stmt->bindValue($i + 1, $val);
        }

        $stmt->execute();
        return $stmt;
    }

    // -------------------------------------------------------
    // IMAGE GALLERY
    // -------------------------------------------------------

    /**
     * Add an image to the package gallery.
     */
    public function addImage($package_id, $image_path, $caption = '', $is_primary = 0) {
        $query = "INSERT INTO package_images (package_id, image_path, caption, is_primary)
                  VALUES (:package_id, :image_path, :caption, :is_primary)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':package_id',  $package_id,  PDO::PARAM_INT);
        $stmt->bindParam(':image_path',  $image_path);
        $stmt->bindParam(':caption',     $caption);
        $stmt->bindParam(':is_primary',  $is_primary,  PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Delete a specific image from the gallery.
     */
    public function deleteImage($image_id) {
        // Get the image path first for file deletion
        $q = $this->conn->prepare("SELECT image_path FROM package_images WHERE id = ?");
        $q->bindParam(1, $image_id, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);

        $query = "DELETE FROM package_images WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $image_id, PDO::PARAM_INT);
        if ($stmt->execute() && $row) {
            // Delete the physical file if it exists in uploads/
            $file = 'assets/uploads/' . $row['image_path'];
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        return isset($row['image_path']) ? $row['image_path'] : null;
    }

    // -------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------

    private function sanitize() {
        $this->title             = htmlspecialchars(strip_tags($this->title));
        $this->destination       = htmlspecialchars(strip_tags($this->destination));
        $this->description       = htmlspecialchars(strip_tags($this->description));
        $this->price             = (float)$this->price;
        $this->duration          = (int)$this->duration;
        $this->image             = htmlspecialchars(strip_tags($this->image ?? ''));
        $this->available_slots   = (int)$this->available_slots;
        $this->category          = htmlspecialchars(strip_tags($this->category ?? 'Cultural'));
        $this->difficulty_level  = htmlspecialchars(strip_tags($this->difficulty_level ?? 'Easy'));
        $this->includes_services = htmlspecialchars(strip_tags($this->includes_services ?? ''));
        $this->excluded_services = htmlspecialchars(strip_tags($this->excluded_services ?? ''));
        $this->is_featured       = isset($this->is_featured) ? (int)$this->is_featured : 0;
        $this->id                = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;
    }
}
?>
