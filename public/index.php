<?php
session_start();

// Se o usuário não estiver logado
if (!isset($_SESSION['user'])) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/LOJA-GAME/app/views/login.php');
    exit;
}

require_once __DIR__ . '/../app/config.php';

$user = $_SESSION['user'];

// -------------------------------------------------------------
// 1) PEGAR PONTOS DO USUÁRIO
// -------------------------------------------------------------
$stmt = $conn->prepare("SELECT pontos FROM usuarios WHERE nome = :nome LIMIT 1");
$stmt->bindValue(':nome', $user);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pontos = $row ? (int)$row['pontos'] : 0;

// -------------------------------------------------------------
// 2) PEGAR TIPO DO USUÁRIO (AQUI É ONDE COLOCA!)
// -------------------------------------------------------------
$stmtTipo = $conn->prepare("SELECT tipo FROM usuarios WHERE nome = :nome LIMIT 1");
$stmtTipo->bindValue(':nome', $user);
$stmtTipo->execute();
$tipo = $stmtTipo->fetchColumn();   // <-- agora funciona!

// -------------------------------------------------------------
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loja de Recompensas</title>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
<style>
/* --- estilo original mantido --- */
<?php /* mantive todo seu CSS exatamente igual */ ?>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Quicksand', sans-serif;
}
body {
    background: linear-gradient(135deg, #1b3a60, #4f7eb5);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.container {
    display: flex;
    flex-wrap: wrap;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    max-width: 1200px;
    width: 100%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}
.sidebar {
    flex: 1 1 300px;
    background: #f9f9f9;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    border-right: 1px solid #ddd;
}
.sidebar h2 {
    color: #1b3a60;
    font-size: 1.2rem;
    margin-bottom: 10px;
    text-align: center;
}
.sidebar input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 0.9rem;
    width: 100%;
}
.points-box {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.points-box h3 {
    font-size: 0.9rem;
    color: #555;
}
.points-value {
    font-size: 2rem;
    font-weight: bold;
    color: #1b3a60;
}
.btn {
    padding: 10px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    background: #1b3a60;
    color: white;
    transition: 0.2s;
    width: 100%;
}
.btn:hover {
    opacity: 0.85;
}
.btn-logout {
    background: #b53434;
}
.content {
    flex: 2 1 500px;
    padding: 30px;
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}
.header h1 {
    font-size: 1.5rem;
    color: #1b3a60;
}
.header input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    flex: 1 1 200px;
    font-size: 0.9rem;
}
.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
}
.product {
    background: #fefefe;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    padding: 20px;
    text-align: center;
}
.product img {
    width: 100%;
    max-width: 160px;
    height: auto;
    border-radius: 10px;
    margin-bottom: 10px;
    object-fit: cover;
}
.product h3 {
    color: #1b3a60;
    font-size: 1rem;
    margin-bottom: 6px;
}
.product p {
    color: #777;
    font-size: 0.9rem;
    margin-bottom: 8px;
}
.product .points {
    font-weight: bold;
    color: #4f7eb5;
}
.product button {
    margin-top: 10px;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    background: #1b3a60;
    color: white;
    cursor: pointer;
    font-size: 0.9rem;
}
.product button:hover {
    background: #3c6aa3;
}
</style>
</head>
<body>

<div class="container">

<div class="sidebar">

<?php if ($tipo === 'admin'): ?>
    <a href="../admin/historico.php">
        <button class="btn">Histórico Geral (Admin)</button>
    </a>
<?php endif; ?>

    <h2>Loja de Recompensas</h2>
    <!-- <input type="text" placeholder="Pesquisar usuário"> -->

    <div class="points-box">
      <h3>Pontos disponíveis</h3>
      <div class="points-value"><?= htmlspecialchars($pontos) ?></div>
      <p style="font-size:0.8rem;color:#777;">Pontos de <?= htmlspecialchars($user) ?></p>
    </div>

<!-- BOTOES HISTORICO E RESET DESATIVADOS -->

    <!-- <button class="btn">Histórico</button>
    <button class="btn">Reset</button> -->

    <hr style="width:100%;border:0;border-top:1px solid #ddd;">

    <div style="text-align:center;">
      <p style="color:#1b3a60;font-weight:600;">Usuário logado:</p>
      <p style="color:#333;"><?= htmlspecialchars($user) ?></p>
    </div>

    <form action="logout.php" method="POST" style="width:100%;">
      <button type="submit" class="btn btn-logout">Sair</button>
    </form>
</div>


  <!-- Conteúdo -->
  <div class="content">
    <div class="header">
      <h1>Produtos</h1>
      <input type="text" id="buscarProduto" placeholder="Pesquisar produtos">

    </div>

<?php if (!empty($msg)): ?>
    <?php
        $tipo = $_SESSION['msg_tipo'] ?? 'sucesso';

        if ($tipo === 'erro') {
            // ERRO → texto vermelho, sem fundo
            $estilo = "color:red;font-weight:bold;padding:10px;";
        } else {
            // SUCESSO → fundo verde com sombra (como antes)
            $estilo = "
                color:#155724;
                background:#d4edda;
                padding:10px;
                border-radius:8px;
                box-shadow:0 3px 6px rgba(0,0,0,0.15);
                font-weight:bold;
            ";
        }
    ?>

    <div id="msg" style="margin-bottom:12px; <?= $estilo ?>">
        <?= htmlspecialchars($msg) ?>
    </div>

<?php unset($_SESSION['msg'], $_SESSION['msg_tipo']); ?>
<?php endif; ?>



    <div class="products">
      <?php
      $produtos = [
        ["Caneca personalizada", "Caneca 300ml com logo", 150],
        ["Camiseta", "Camiseta gola careca tamanho M", 350],
        ["Vale-refeição", "R$10 para restaurante", 80],
        ["Fones de ouvido", "Fone intra-auricular Bluetooth", 600],
        ["Chinelo", "Chinelo de borracha com logo", 120],
        ["Agenda 2026", "Agenda de mesa diária", 90],
        ["Mochila executiva", "Mochila 20L resistente", 500],
        ["Boné", "Boné com logo bordado", 200],
        ["Caderno universitário", "100 folhas", 120],
      ];

      $img = "https://i.postimg.cc/mDGQx58J/Chat-GPT-Image-4-de-nov-de-2025-08-45-31.png";

foreach ($produtos as $p) {
    echo "
    <div class='product'>
        <img src='$img' alt='Produto'>
        <h3>{$p[0]}</h3>
        <p>{$p[1]}</p>
        <span class='points'>{$p[2]} pts</span>

        <form action='resgatar.php' method='POST' onsubmit='return confirmarResgate(\"{$p[0]}\", {$p[2]});'>
            <input type='hidden' name='produto' value='{$p[0]}'>
            <input type='hidden' name='custo' value='{$p[2]}'>
            <button type='submit'>Resgatar</button>
        </form>
    </div>
    ";
}

      ?>
    </div>
  </div>

</div>
<script>
function confirmarResgate(produto, custo) {
    return confirm("Tem certeza que deseja resgatar \"" + produto + "\" por " + custo + " pontos?");
}
</script>

<script>
    // Se existir mensagem, ela some automaticamente após 3 segundos
    setTimeout(() => {
        let msg = document.getElementById("msg");
        if (msg) {
            msg.style.transition = "opacity 0.6s";
            msg.style.opacity = "0";

            setTimeout(() => msg.remove(), 600); // remove depois do fade
        }
    }, 3000);
</script>


<script>
// PESQUISA DE PRODUTOS EM TEMPO REAL
document.getElementById("buscarProduto").addEventListener("input", function () {
    let termo = this.value.toLowerCase();
    let produtos = document.querySelectorAll(".product");

    produtos.forEach(prod => {
        let nome = prod.querySelector("h3").innerText.toLowerCase();
        let desc = prod.querySelector("p").innerText.toLowerCase();

        if (nome.includes(termo) || desc.includes(termo)) {
            prod.style.display = "block";
        } else {
            prod.style.display = "none";
        }
    });
});
</script>

</body>
</html>
