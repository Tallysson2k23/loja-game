<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Usuário precisa estar logado
if (!isset($_SESSION['user'])) {
    header('Location: /LOJA-GAME/app/views/login.php');
    exit;
}

$user = $_SESSION['user'];

// Verifica se dados vieram do formulário
if (empty($_POST['produto']) || empty($_POST['custo'])) {
    $_SESSION['msg'] = "Requisição inválida.";
    header("Location: loja.php");
    exit;
}

$produto = trim($_POST['produto']);
$custo = (int) $_POST['custo'];

// ----------------------------------------------------------
// 1) Buscar pontos atuais do usuário
// ----------------------------------------------------------
$stmt = $conn->prepare("SELECT pontos FROM usuarios WHERE nome = :nome LIMIT 1");
$stmt->bindValue(':nome', $user);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    $_SESSION['msg'] = "Usuário não encontrado.";
    header("Location: loja.php");
    exit;
}

$pontosAtual = (int) $row['pontos'];

// ----------------------------------------------------------
// 2) Verificar se tem pontos suficientes
// ----------------------------------------------------------
if ($pontosAtual < $custo) {
    $_SESSION['msg'] = "Você não tem pontos suficientes para resgatar este item.";
    header("Location: loja.php");
    exit;
}

// Novo saldo
$novoSaldo = $pontosAtual - $custo;

// ----------------------------------------------------------
// 3) Registrar o histórico ANTES de atualizar os pontos
// ----------------------------------------------------------
$hist = $conn->prepare("
    INSERT INTO historico_resgates (usuario, produto, custo, data_resgate)
    VALUES (:u, :p, :c, NOW())
");
$hist->bindValue(':u', $user);
$hist->bindValue(':p', $produto);
$hist->bindValue(':c', $custo);
$hist->execute();

// ----------------------------------------------------------
// 4) Atualizar pontos
// ----------------------------------------------------------
$update = $conn->prepare("UPDATE usuarios SET pontos = :p WHERE nome = :nome");
$update->bindValue(':p', $novoSaldo);
$update->bindValue(':nome', $user);
$update->execute();

// ----------------------------------------------------------
// 5) Mensagem de retorno
// ----------------------------------------------------------
$_SESSION['msg'] = "🎉 Resgate realizado com sucesso! Você recebeu: $produto";

header("Location: loja.php");
exit;
