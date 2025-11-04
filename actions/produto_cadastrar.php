<?php
/**
 * produto_cadastrar.php
 * Processa a submissão do formulário de cadastro de produto.
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Proteção: Apenas usuários autenticados podem acessar
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../app/dao/ProdutoDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Coleta e Sanitização dos Dados
    // Usamos FILTER_SANITIZE_STRING (ou FILTER_DEFAULT) e htmlspecialchars() na saída (view) para proteção XSS
    $nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT);
    $preco_str = filter_input(INPUT_POST, 'preco', FILTER_DEFAULT); // Tratamos o preço como string inicialmente
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

    // Armazena dados no SESSION em caso de erro (para repopular o formulário)
    $_SESSION['form_data'] = [
        'nome' => $nome,
        'descricao' => $descricao,
        'preco' => $preco_str,
        'categoria_id' => $categoria_id
    ];

    // Validação dos Dados
    if (empty($nome) || $categoria_id === false || $categoria_id === null || empty($preco_str)) {
        $_SESSION['msg_erro'] = "Por favor, preencha todos os campos obrigatórios corretamente.";
        header("Location: ../public/produtos/cadastrar.php");
        exit();
    }

    // Validação específica para Preço
    $preco = filter_var($preco_str, FILTER_VALIDATE_FLOAT);
    if ($preco === false || $preco < 0) {
        $_SESSION['msg_erro'] = "Preço inválido. Use um formato numérico.";
        header("Location: ../public/produtos/cadastrar.php");
        exit();
    }
    
    // Execução do DAO
    $produtoDAO = new ProdutoDAO();
    if ($produtoDAO->criar($nome, $descricao, $preco, $categoria_id)) {
        
        // Sucesso: Limpa dados e define mensagem
        unset($_SESSION['form_data']); 
        $_SESSION['msg_sucesso'] = "Produto '{$nome}' cadastrado com sucesso.";
        header("Location: ../public/produtos/listar.php"); // Redireciona para a listagem
        exit();
        
    } else {
        // Erro no Banco
        $_SESSION['msg_erro'] = "Erro interno ao cadastrar o produto no banco de dados.";
        header("Location: ../public/produtos/cadastrar.php"); // Redireciona de volta ao formulário
        exit();
    }

} else {
    // Acesso direto ao script de ação
    $_SESSION['msg_erro'] = "Acesso inválido ao processamento de cadastro.";
    header("Location: ../public/produtos/listar.php");
    exit();
}