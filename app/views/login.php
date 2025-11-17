<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = strtoupper(trim($_POST['nome']));
    $senha = trim($_POST['senha']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nome = :nome");
    $stmt->bindValue(':nome', $nome);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user'] = $user['nome'];
        header("Location: http://10.34.0.76/LOJA-GAME/public/index.php");

        exit();
    } else {
        $msg = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Login - Loja de Recompensas</title>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-1:#1b3a60;
    --bg-2:#4f7eb5;
    --accent:#1b3a60;
    --muted:#777;
  }
  *{box-sizing:border-box}
  body{
    font-family:'Quicksand',sans-serif;
    background:linear-gradient(135deg,var(--bg-1),var(--bg-2));
    min-height:100vh;
    margin:0;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:28px;
  }
  .login-card{
    width:100%;
    max-width:420px;
    background:white;
    border-radius:16px;
    padding:36px 32px 28px;
    box-shadow:0 18px 40px rgba(0,0,0,0.18);
    display:flex;
    flex-direction:column;
    gap:20px;
    align-items:center;
    position:relative;
  }
  .logo{
    width:180px;
    height:auto;
    display:block;
    margin:0 auto 10px;
  }
  h1{
    margin:0;
    font-size:1.7rem;
    color:var(--accent);
    text-align:center;
  }
  label{
    align-self:flex-start;
    font-weight:600;
    color:#333;
    margin:10px 0 6px;
    font-size:0.95rem;
  }
  input{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1.2px solid #ddd;
    font-size:0.95rem;
  }
  input:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 5px rgba(27,58,96,0.07);
  }
  .btn{
    padding:12px;
    border-radius:10px;
    border:none;
    font-weight:700;
    cursor:pointer;
    font-size:1rem;
    width:100%;
    transition:all 0.25s ease;
  }
  .btn-primary{
    background:linear-gradient(135deg,var(--bg-1),var(--bg-2));
    color:#fff;
  }
  .btn-primary:hover{
    opacity:0.9;
  }
  .btn-secondary{
    background:#e0e0e0;
    color:#222;
  }
  .btn-secondary:hover{
    background:#d4d4d4;
  }
  .login-footer{
    text-align:center;
    font-size:0.85rem;
    color:var(--muted);
    margin-top:8px;
  }
  .msg{
    color:red;
    font-size:0.9rem;
    text-align:center;
  }
</style>
</head>
<body>

<div class="login-card">
  <img src="https://i.postimg.cc/BvJvQKBk/image-removebg-preview.png" alt="Redecom Telecom" class="logo">
  <h1>Login</h1>

  <?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
  <?php endif; ?>

  <form method="POST" style="width:100%;">
    <label for="loginNome">Nome de usuário</label>
    <input type="text" name="nome" id="loginNome" placeholder="Digite seu nome" required>

    <label for="loginSenha">Senha</label>
    <input type="password" name="senha" id="loginSenha" placeholder="Digite sua senha" required>

    <button class="btn btn-primary" type="submit">Entrar</button>
  </form>

  <form action="/LOJA-GAME/app/views/cadastro.php" method="GET" style="width:100%;">
    <button class="btn btn-secondary" type="submit">Cadastrar-se</button>
  </form>

  <div class="login-footer">Tallysson Luis © 2025</div>
</div>

</body>
</html>
