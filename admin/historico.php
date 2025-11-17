<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// verifica login
if (!isset($_SESSION['user'])) {
    header("Location: ../app/views/login.php");
    exit;
}

// verifica se é admin
$usuarioLogado = $_SESSION['user'];
$stmt = $conn->prepare("SELECT tipo FROM usuarios WHERE nome = :nome LIMIT 1");
$stmt->bindValue(':nome', $usuarioLogado);
$stmt->execute();
$tipo = $stmt->fetchColumn();

if ($tipo !== 'admin') {
    echo "<h2 style='color:red;text-align:center;margin-top:50px;'>ACESSO NEGADO</h2>";
    exit;
}

// pega histórico
$sql = $conn->query("SELECT * FROM historico_resgates ORDER BY data_resgate DESC");
$dados = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Histórico de Resgates</title>
<style>
    body {
        background: #f5f5f5;
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #1b3a60;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ccc;
        text-align: center;
    }
    th {
        background: #1b3a60;
        color: white;
    }
    .back {
        text-align:center;
        margin-top:20px;
    }
    .back a {
        text-decoration:none;
        padding:10px 20px;
        background:#1b3a60;
        color:white;
        border-radius:8px;
    }
</style>
</head>
<body>

<h1>Histórico de Resgates</h1>

<table>
    <tr>
        <th>Usuário</th>
        <th>Produto</th>
        <th>Custo</th>
        <th>Data e Hora</th>
    </tr>

    <?php foreach ($dados as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['usuario']) ?></td>
            <td><?= htmlspecialchars($item['produto']) ?></td>
            <td><?= htmlspecialchars($item['custo']) ?> pts</td>
            <td><?= date('d/m/Y H:i:s', strtotime($item['data_resgate'])) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div class="back">
    <a href="../public/loja.php">Voltar</a>
</div>

</body>
</html>
