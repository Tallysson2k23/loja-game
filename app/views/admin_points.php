<?php
session_start();
require_once __DIR__ . '/../config.php';

// simples proteção: só permitir se usuário for 'ADMIN' (ajuste conforme quiser)
if (!isset($_SESSION['user'])) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/app/views/login.php');
    exit;
}

$mensagem = '';

// pega lista de usuários
$usersStmt = $conn->query("SELECT id, nome, pontos FROM usuarios ORDER BY nome");
$usuarios = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = (int)($_POST['userid'] ?? 0);
    $novos = (int)($_POST['pontos'] ?? 0);
    if ($userid > 0) {
        $u = $conn->prepare("UPDATE usuarios SET pontos = :p WHERE id = :id");
        $u->bindValue(':p', $novos, PDO::PARAM_INT);
        $u->bindValue(':id', $userid, PDO::PARAM_INT);
        $u->execute();
        $mensagem = 'Pontos atualizados.';
        // recarrega lista
        $usersStmt = $conn->query("SELECT id, nome, pontos FROM usuarios ORDER BY nome");
        $usuarios = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin - Ajustar Pontos</title>
</head>
<body>
<h2>Admin — Ajustar Pontos</h2>
<?php if ($mensagem): ?><p style="color:green"><?=htmlspecialchars($mensagem)?></p><?php endif; ?>

<form method="POST">
  <label>Usuário:
    <select name="userid" required>
      <option value="">-- selecione --</option>
      <?php foreach($usuarios as $u): ?>
        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?> (<?= $u['pontos'] ?> pts)</option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>NOVOS PONTOS:
    <input type="number" name="pontos" min="0" required>
  </label>
  <button type="submit">Salvar</button>
</form>

<p><a href="/LOJA-GAME/public/index.php">Voltar</a></p>
</body>
</html>
