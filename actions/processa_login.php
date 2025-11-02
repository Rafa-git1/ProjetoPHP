<?php
/**
 * processa_login.php
 * Recebe e valida as credenciais do login.
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Incluir a conexão com o banco e a classe DAO
require_once __DIR__ . '/../app/config/Conexao.php';
require_once __DIR__ . '/../app/dao/UsuarioDAO.php'; // Vamos criar essa classe

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Sanitizar e obter dados
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    if (empty($email) || empty($senha)) {
        $_SESSION['msg_erro'] = "Preencha todos os campos.";
        header("Location: /ProjetoPHP/public/dashboard.php");
        exit();
    }

    $dao = new UsuarioDAO();
    $usuario = $dao->buscarPorEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
        // 2. Login bem-sucedido: Armazenar dados na sessão
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nome'] = $usuario['nome'];
        $_SESSION['user_nivel'] = $usuario['nivel_acesso'];
        
        // 3. Redirecionar para a área protegida
        $_SESSION['msg_sucesso'] = "Bem-vindo(a), " . $usuario['nome'] . "!";
        header("Location: /ProjetoPHP/public/dashboard.php");
        exit();

    } else {
        // 4. Falha no login
        $_SESSION['msg_erro'] = "E-mail ou senha inválidos.";
        header("Location: /ProjetoPHP/public/login.php");
        exit();
    }
} else {
    // Tentativa de acesso direto
    header("Location: /ProjetoPHP/public/dashboard.php");
    exit();
}