<?php
/**
 * produto_deletar.php
 * Processa a requisição de exclusão de um produto.
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Proteção: Apenas usuários autenticados podem acessar
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../app/dao/ProdutoDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitização e obtenção do ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id === false || $id === null) {
        $_SESSION['msg_erro'] = "ID do produto inválido.";
        header("Location: ../public/produtos/listar.php");
        exit();
    }

    $produtoDAO = new ProdutoDAO();
    
    // Execução da deleção
    if ($produtoDAO->deletar($id)) {
        $_SESSION['msg_sucesso'] = "Produto deletado com sucesso.";
    } else {
        $_SESSION['msg_erro'] = "Erro ao deletar o produto ou produto não encontrado.";
    }

} else {
    $_SESSION['msg_erro'] = "Acesso inválido.";
}

// Redirecionamento de volta para a listagem
header("Location: ../public/produtos/listar.php");
exit();