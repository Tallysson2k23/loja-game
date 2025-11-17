<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
echo "<h1>Bem-vindo, " . htmlspecialchars($_SESSION['user']) . "!</h1>";
echo '<a href="logout.php">Sair</a>';
