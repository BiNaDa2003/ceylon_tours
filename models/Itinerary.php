<?php
/**
 * Itinerary Model
 * Manages day-by-day tour itinerary entries for each package.
 */
class Itinerary {
    private $conn;
    private $table_name = "itineraries";

    public $id;
    public $package_id;
    public $day_number;
    public $title;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new itinerary day entry.
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET package_id=:package_id, day_number=:day_number,
                      title=:title, description=:description";
        $stmt = $this->conn->prepare($query);

        $this->package_id  = (int)$this->package_id;
        $this->day_number  = (int)$this->day_number;
        $this->title       = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(':package_id',  $this->package_id,  PDO::PARAM_INT);
        $stmt->bindParam(':day_number',  $this->day_number,  PDO::PARAM_INT);
        $stmt->bindParam(':title',       $this->title);
        $stmt->bindParam(':description', $this->description);

        return $stmt->execute();
    }

    /**
     * Read all itinerary days for a package, ordered by day number.
     */
    public function readByPackage() {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE package_id = :package_id ORDER BY day_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':package_id', $this->package_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read a single itinerary entry.
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->package_id  = $row['package_id'];
            $this->day_number  = $row['day_number'];
            $this->title       = $row['title'];
            $this->description = $row['description'];
            return true;
        }
        return false;
    }

    /**
     * Update an itinerary entry.
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET day_number=:day_number, title=:title, description=:description
                  WHERE id=:id AND package_id=:package_id";
        $stmt = $this->conn->prepare($query);

        $this->day_number  = (int)$this->day_number;
        $this->title       = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(':day_number',  $this->day_number,  PDO::PARAM_INT);
        $stmt->bindParam(':title',       $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id',          $this->id,          PDO::PARAM_INT);
        $stmt->bindParam(':package_id',  $this->package_id,  PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Delete an itinerary entry.
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Delete all itinerary days for a package (used when package is deleted).
     */
    public function deleteByPackage() {
        $query = "DELETE FROM " . $this->table_name . " WHERE package_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->package_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
