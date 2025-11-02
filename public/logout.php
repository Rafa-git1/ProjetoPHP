<?php
/**
 * logout.php
 * Encerra a sessão do usuário.
 */
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se for preciso destruir o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir a sessão
session_destroy();

// Define uma mensagem e redireciona para a página de login
$_SESSION['msg_sucesso'] = "Você foi desconectado com sucesso.";
header("Location: login.php");
exit();