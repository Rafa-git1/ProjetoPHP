<?php
/**
 * auth_check.php
 * Script para verificar se o usuário está autenticado. 
 */

// Inicia a sessão se ainda não tiver sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a variável de sessão de login está definida e é verdadeira
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Define uma mensagem de aviso na sessão
    $_SESSION['msg_erro'] = "Você precisa estar logado para acessar esta página.";
    
    // Redireciona para a página de login
    header("Location: /ProjetoPHP/public/login.php");
    exit();
}
