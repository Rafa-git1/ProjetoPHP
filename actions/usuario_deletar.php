<?php
/**
 * Processa a exclusão de usuário.
 */
session_start();

// Caminho corrigido para dois níveis acima
require_once __DIR__ . '/../app/dao/UsuarioDAO.php';

// Checagem de Nível de Acesso (APENAS ADMIN PODE ACESSAR)
if ($_SESSION['user_nivel'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado.";
    header("Location: ../public/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/usuarios/listar.php");
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['msg_erro'] = "ID do usuário não fornecido para exclusão.";
    header("Location: ../public/usuarios/listar.php");
    exit();
}

// Impedir que um usuário exclua a si mesmo
if ($id == $_SESSION['user_id']) {
    $_SESSION['msg_erro'] = "Você não pode excluir sua própria conta enquanto estiver logado.";
    header("Location: ../public/usuarios/listar.php");
    exit();
}

$usuarioDAO = new UsuarioDAO();

if ($usuarioDAO->deletar($id)) {
    $_SESSION['msg_sucesso'] = "Usuário excluído com sucesso!";
} else {
    $_SESSION['msg_erro'] = "Erro ao tentar excluir o usuário.";
}

header("Location: ../public/usuarios/listar.php");
exit();
