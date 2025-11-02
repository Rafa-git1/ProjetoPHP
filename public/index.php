<?php
/**
 * public/index.php
 * Ponto de entrada principal do sistema. 
 * Redireciona para o login se não estiver autenticado ou para o dashboard se estiver.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define a raiz da URL do projeto para redirecionamento
// Base URL: /PROJETO-PHP/public/
$base_url_public = '/PROJETOPHP/public/';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Usuário logado: Redireciona para o dashboard
    header("Location: {$base_url_public}dashboard.php");
    exit();
} else {
    // Usuário não logado: Redireciona para a página de login
    header("Location: {$base_url_public}login.php");
    exit();
}

// Nada mais é exibido aqui, o script termina no redirecionamento.
?>
