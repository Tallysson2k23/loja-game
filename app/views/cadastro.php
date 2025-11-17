<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = strtoupper(trim($_POST['nome']));
    $senha = trim($_POST['senha']);

    if (strlen($senha) < 4) {
        $msg = "A senha precisa ter pelo menos 4 caracteres.";
    } else {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE nome = :nome");
        $check->bindValue(':nome', $nome);
        $check->execute();

        if ($check->fetch()) {
            $msg = "Usuário já cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $insert = $conn->prepare("INSERT INTO usuarios (nome, senha) VALUES (:nome, :senha)");
            $insert->bindValue(':nome', $nome);
            $insert->bindValue(':senha', $hash);
            $insert->execute();
            header('Location: login.php?msg=sucesso');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Cadastro - Loja de Recompensas</title>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
<style>
  body{font-family:'Quicksand',sans-serif;background:linear-gradient(135deg,#1b3a60,#4f7eb5);min-height:100vh;margin:0;display:flex;align-items:center;justify-content:center;padding:28px}
  .card{width:100%;max-width:400px;background:white;border-radius:16px;padding:32px;box-shadow:0 18px 40px rgba(0,0,0,0.18);display:flex;flex-direction:column;gap:20px;align-items:center}
  h1{margin:0;font-size:1.8rem;color:#1b3a60;text-align:center}
  label{align-self:flex-start;font-weight:600;color:#333;margin:10px 0 6px;font-size:0.95rem}
  input{width:100%;padding:12px 14px;border-radius:10px;border:1.2px solid #ddd;font-size:0.95rem}
  input:focus{outline:none;border-color:#1b3a60;box-shadow:0 0 0 6px rgba(27,58,96,0.06)}
  .btn{padding:12px;border-radius:10px;border:none;font-weight:700;cursor:pointer;font-size:1rem;width:100%}
  .btn-primary{background:linear-gradient(135deg,#1b3a60,#4f7eb5);color:#fff}
  .btn-secondary{background:#ccc;color:#000}
  .msg{color:red;font-size:0.9rem;text-align:center}
</style>
</head>
<body>

<div class="card">
  <h1>Cadastrar Usuário</h1>

  <?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="nome">Nome de usuário</label>
    <input type="text" name="nome" id="nome" required>

    <label for="senha">Senha</label>
    <input type="password" name="senha" id="senha" required>

    <button class="btn btn-primary" type="submit">Cadastrar</button>
  </form>

  <form action="login.php" method="GET" style="width:100%;">
    <button class="btn btn-secondary" type="submit">Voltar ao Login</button>
  </form>
</div>

</body>
</html>
