<?php
session_start();
require_once '../../Model/Database.php';

class ProductController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function addProduct($data, $file) {
        try {
            // Handle image upload
            $fileName = $this->uploadImage($file);
            
            // Insert product data
            $stmt = $this->db->prepare("
                INSERT INTO products (name, description, price, category, stock, image) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category'],
                $data['stock'],
                $fileName
            ]);
            
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateProduct($id, $data, $file = null) {
        try {
            $params = [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category'],
                $data['stock']
            ];

            $sql = "
                UPDATE products 
                SET name = ?, description = ?, price = ?, category = ?, stock = ?
            ";

            // If new image is uploaded
            if ($file && $file['size'] > 0) {
                $fileName = $this->uploadImage($file);
                $sql .= ", image = ?";
                $params[] = $fileName;
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            // Get image name first
            $stmt = $this->db->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            // Delete the image file
            if ($product && $product['image']) {
                $imagePath = "../../assets/images/" . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete from database
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    private function uploadImage($file) {
        $targetDir = "../../assets/images/";
        $fileName = basename($file["name"]);
        $targetPath = $targetDir . $fileName;

        // Ensure filename is unique
        $i = 1;
        while (file_exists($targetPath)) {
            $fileName = pathinfo($file["name"], PATHINFO_FILENAME) 
                     . "($i)." 
                     . pathinfo($file["name"], PATHINFO_EXTENSION);
            $targetPath = $targetDir . $fileName;
            $i++;
        }

        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            return $fileName;
        }
        
        throw new Exception("Failed to upload image");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $controller = new ProductController($db->getConnection());
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        if ($controller->addProduct($_POST, $_FILES['image'])) {
            $_SESSION['success'] = 'Product added successfully';
            header('Location: ../products.php');
            exit;
        }
    }
    
    $_SESSION['error'] = 'Failed to add product';
    header('Location: ../product-add.php');
    exit;
}
?>
