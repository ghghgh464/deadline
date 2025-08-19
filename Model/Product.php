<?php
class Product {
    private $dbConnection; 

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getAllProducts() {
        try {
            $stmt = $this->dbConnection->query("SELECT * FROM products WHERE status = 1 AND featured = 1 ORDER BY id DESC"); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            return []; 
        }
    }

    public function getProductById($id) {
        try {
            $stmt = $this->dbConnection->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null; 
        }
    }

    // New method to search products
    public function searchProducts($searchTerm) {
        try {
            // Using prepared statement with LIKE for safe searching
            $query = "SELECT * FROM products WHERE (name LIKE :searchTerm OR description LIKE :searchTerm) AND status = 1";
            $stmt = $this->dbConnection->prepare($query);
            // Bind the parameter, adding wildcards for LIKE search
            $likeTerm = "%" . $searchTerm . "%";
            $stmt->bindParam(':searchTerm', $likeTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // New method for pagination
    public function getPaginatedProducts($limit, $offset) {
        try {
            // Prepared statement with LIMIT and OFFSET for pagination
            $query = "SELECT * FROM products WHERE status = 1 LIMIT :limit OFFSET :offset";
            $stmt = $this->dbConnection->prepare($query);
            // Bind parameters as integers
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total number of products for pagination links
            $totalQuery = "SELECT COUNT(*) FROM products WHERE status = 1";
            $totalStmt = $this->dbConnection->query($totalQuery);
            $totalProducts = $totalStmt->fetchColumn();

            return ['products' => $products, 'total' => $totalProducts];
        } catch (PDOException $e) {
            return ['products' => [], 'total' => 0];
        }
    }
}
?> 