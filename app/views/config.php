<?php
$host = 'localhost';
$dbname = 'loja_game';
$user = 'postgres';
$password = '159357';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>
