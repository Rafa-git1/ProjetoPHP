<?php
// Inicia a sessão para usar as mensagens de feedback e variáveis de sessão
session_start();

// Verifica autenticação e permissão
require_once __DIR__ . '/../app/includes/auth_check.php';
if ($_SESSION['nivel_acesso'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado. Apenas administradores podem gerenciar categorias.";
    header("Location: ../public/categorias/listar.php");
    exit;
}

// Verifica se a requisição é POST e se o ID é válido
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['msg_erro'] = "ID da categoria inválido para exclusão.";
    header("Location: ../public/categorias/listar.php");
    exit;
}

$id = (int)$_POST['id'];

// Inclui o DAO e inicializa
require_once __DIR__ . '/../app/dao/CategoriaDAO.php';
$categoriaDAO = new CategoriaDAO();

// Tenta deletar
if ($categoriaDAO->deletar($id)) {
    $_SESSION['msg_sucesso'] = "Categoria excluída com sucesso!";
    // IMPORTANTE: Se houver produtos associados, o ON DELETE SET NULL na tabela 'produtos' cuidará dos IDs.
    header("Location: ../public/categorias/listar.php");
} else {
    $_SESSION['msg_erro'] = "Erro ao excluir a categoria. Verifique se há restrições de chave estrangeira (ex: produtos associados).";
    header("Location: ../public/categorias/listar.php");
}
exit;
