<?php

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Simulando a busca de usuário
            $user = User::findByUsername($username);

            if ($user && $user->verifyPassword($password)) {
                $_SESSION['user'] = $user;  // Armazenando o usuário na sessão
                header('Location: /store');
                exit;
            } else {
                echo "Usuário ou senha inválidos";
            }
        }
        include 'views/login.php';
    }
}
?>
