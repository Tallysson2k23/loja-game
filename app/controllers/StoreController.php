<?php

require_once __DIR__ . '/../models/Product.php'; // garante acesso ao modelo

class StoreController {

    // Mostra detalhes de um produto específico
    public function show($id) {
        echo "<h1>Produto ID: $id</h1>";
        // Aqui você pode buscar o produto pelo ID:
        // $product = Product::findById($id);
        // include __DIR__ . '/../views/product.php';
    }

    // Página principal da loja
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $products = Product::getAll();

        include __DIR__ . '/../views/store.php';
    }

    // Resgatar produto com pontos
    public function redeem($productId) {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $products = Product::getAll();
        $product = null;

        foreach ($products as $p) {
            if ($p->getId() == $productId) {
                $product = $p;
                break;
            }
        }

        if ($product && $user->getPoints() >= $product->getPointsRequired()) {
            $user->setPoints($user->getPoints() - $product->getPointsRequired());
            header('Location: /store');
            exit;
        } else {
            echo "Pontos insuficientes ou produto não encontrado.";
        }
    }
}
