<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/app/views/login.php');
    exit;
}

require_once __DIR__ . '/../app/config.php';

$user = $_SESSION['user'];
$produto = $_POST['produto'] ?? '';
$custo = isset($_POST['custo']) ? (int)$_POST['custo'] : 0;

if (!$produto || $custo <= 0) {
    $_SESSION['msg'] = "Dados do produto inválidos.";
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/public/index.php');
    exit;
}

// pega pontos atuais
$stmt = $conn->prepare("SELECT pontos FROM usuarios WHERE nome = :nome LIMIT 1");
$stmt->bindValue(':nome', $user);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pontos = $row ? (int)$row['pontos'] : 0;

if ($pontos < $custo) {
    $_SESSION['msg'] = "Pontos insuficientes para resgatar '{$produto}'.";
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/public/index.php');
    exit;
}

// deduz pontos (transação segura)
try {
    $conn->beginTransaction();
    $upd = $conn->prepare("UPDATE usuarios SET pontos = pontos - :custo WHERE nome = :nome");
    $upd->bindValue(':custo', $custo, PDO::PARAM_INT);
    $upd->bindValue(':nome', $user);
    $upd->execute();

    // opcional: gravar histórico (crie tabela se quiser)
    // $hist = $conn->prepare("INSERT INTO resgates (usuario, produto, custo) VALUES (:u, :p, :c)");
    // $hist->execute([...]);

    $conn->commit();
    $_SESSION['msg'] = "Resgate de '{$produto}' realizado! (-{$custo} pts)";
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['msg'] = "Erro ao resgatar: " . $e->getMessage();
}

header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/public/index.php');
exit;
