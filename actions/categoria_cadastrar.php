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

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/categorias/listar.php");
    exit;
}

// 1. Captura e Sanitiza os dados
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);

// 2. Validação Básica
if (empty($nome)) {
    $_SESSION['msg_erro'] = "O nome da categoria é obrigatório.";
    $_SESSION['form_data'] = $_POST; // Salva os dados para preencher o form novamente
    header("Location: ../public/categorias/cadastrar.php");
    exit;
}

// 3. Inclui o DAO e inicializa
require_once __DIR__ . '/../app/dao/CategoriaDAO.php';
$categoriaDAO = new CategoriaDAO();

// 4. Verifica se o nome já existe
if ($categoriaDAO->nomeExiste($nome)) {
    $_SESSION['msg_erro'] = "O nome da categoria '{$nome}' já existe.";
    $_SESSION['form_data'] = $_POST;
    header("Location: ../public/categorias/cadastrar.php");
    exit;
}

// 5. Cadastra no banco
if ($categoriaDAO->cadastrar($nome)) {
    $_SESSION['msg_sucesso'] = "Categoria '{$nome}' cadastrada com sucesso!";
    header("Location: ../public/categorias/listar.php");
} else {
    $_SESSION['msg_erro'] = "Erro ao cadastrar a categoria. Tente novamente.";
    $_SESSION['form_data'] = $_POST;
    header("Location: ../public/categorias/cadastrar.php");
}
exit;
