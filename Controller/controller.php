<?php
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/Database.php';

class PageController {
    private $productModel;
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->productModel = new Product($this->dbConnection);
    }

    public function renderPage($page) {
        // Chỉ include header nếu không phải trang login/register (vì chúng có layout riêng)
        if ($page !== 'login' && $page !== 'register') {
            include __DIR__ . '/../Views/header.php';
        }

        switch ($page) {
            case 'login':
                include __DIR__ . '/../Views/login.php';
                break;
            case 'register':
                include __DIR__ . '/../Views/register.php';
                break;
            case 'product':
                $itemsPerPage = 10; 
                $currentPage = $_GET['page_num'] ?? 1;
                $offset = ($currentPage - 1) * $itemsPerPage;

                $paginationData = $this->productModel->getPaginatedProducts($itemsPerPage, $offset);
                $products = $paginationData['products'];
                $totalProducts = $paginationData['total'];
                $totalPages = ceil($totalProducts / $itemsPerPage);

                include __DIR__ . '/../Views/product.php';
                break;
            case 'cart':
                include __DIR__ . '/../Views/cart.php';
                break;
            case 'checkout':
                include __DIR__ . '/../Views/checkout.php';
                break;
            case 'search':
                $searchTerm = $_GET['term'] ?? '';
                $products = $this->productModel->searchProducts($searchTerm);
                
                $totalProducts = null;
                $totalPages = null;
                include __DIR__ . '/../Views/product.php';
                break;
            default:
                $products = $this->productModel->getAllProducts();
                 
                $totalProducts = null;
                $totalPages = null;
                include __DIR__ . '/../Views/home.php';
                break;
        }

        // Chỉ include footer nếu không phải trang login/register (vì chúng có layout riêng)
        if ($page !== 'login' && $page !== 'register') {
            include __DIR__ . '/../Views/footer.php';
        }
    }
}
?>
