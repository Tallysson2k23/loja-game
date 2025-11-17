<?php
// Captura a URI sem query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Detecta automaticamente o nome da pasta base
$base = '/loja-game/public';
$uri = str_ireplace($base, '', $uri); // ignora diferença entre maiúsculas/minúsculas

// Remove possíveis barras duplicadas
$uri = rtrim($uri, '/');

// Debug opcional (descomente se quiser ver o valor exato da URI)
// echo "<pre>URI atual: $uri</pre>";

// ---------------------- ROTAS ----------------------

// Rota principal (página inicial)
if ($uri === '' || $uri === '/' || $uri === '/index') {
    require_once __DIR__ . '/../views/login.php';
    exit;
}

// Rota de produto ex: /produto/15
if (preg_match('/^\/?produto\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    require_once __DIR__ . '/../controllers/StoreController.php';
    $controller = new StoreController();
    $controller->show($id);
    exit;
}

// Página não encontrada
http_response_code(404);
echo "<h1>Página não encontrada</h1>";
