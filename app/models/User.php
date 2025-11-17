<?php

class User {
    private $id;
    private $username;
    private $password;
    private $points;

    public function __construct($id, $username, $password, $points) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->points = $points;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);  // Verifica a senha criptografada
    }

    public function getPoints() {
        return $this->points;
    }

    public function setPoints($points) {
        $this->points = $points;
    }

    // Método para encontrar o usuário por username (Simulação em memória)
    public static function findByUsername($username) {
        $users = [
            new User(1, 'usuario1', password_hash('senha123', PASSWORD_DEFAULT), 100),
            new User(2, 'usuario2', password_hash('senha456', PASSWORD_DEFAULT), 200)
        ];
        foreach ($users as $user) {
            if ($user->username === $username) {
                return $user;
            }
        }
        return null;
    }
}
?>
