<?php
require_once __DIR__ . '/../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);

    $stmt = $conn->prepare("INSERT INTO livros (titulo, autor, imagem) VALUES (:titulo, :autor, :imagem)");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB);
    $stmt->execute();

    echo "Livro inserido com sucesso!";
}
?>
<form method="POST" enctype="multipart/form-data">
  <input type="text" name="titulo" placeholder="Título" required><br>
  <input type="text" name="autor" placeholder="Autor" required><br>
  <input type="file" name="imagem" accept="image/*" required><br>
  <button type="submit">Enviar</button>
</form>
