<?php
/**
 * produto_editar.php
 * Processa a submissão do formulário de edição de produto.
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Proteção: Apenas usuários autenticados podem acessar
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../app/dao/ProdutoDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. Coleta e Sanitização dos Dados (incluindo o ID)
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT);
    $preco_str = filter_input(INPUT_POST, 'preco', FILTER_DEFAULT); 
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

    // Salva os dados no SESSION em caso de erro para repopular o formulário de edição
    $_SESSION['form_data'] = [
        'id' => $id,
        'nome' => $nome,
        'descricao' => $descricao,
        'preco' => $preco_str,
        'categoria_id' => $categoria_id
    ];

    $redirect_url_erro = "../public/produtos/editar.php?id=" . $id;

    // 2. Validação dos Dados
    if ($id === false || $id === null) {
        $_SESSION['msg_erro'] = "ID do produto inválido. Não foi possível atualizar.";
        header("Location: ../public/produtos/listar.php");
        exit();
    }
    if (empty($nome) || $categoria_id === false || $categoria_id === null || empty($preco_str)) {
        $_SESSION['msg_erro'] = "Por favor, preencha todos os campos obrigatórios corretamente.";
        header("Location: " . $redirect_url_erro);
        exit();
    }

    $preco = filter_var($preco_str, FILTER_VALIDATE_FLOAT);
    if ($preco === false || $preco < 0) {
        $_SESSION['msg_erro'] = "Preço inválido. Use um formato numérico.";
        header("Location: " . $redirect_url_erro);
        exit();
    }
    
    // 3. Execução do DAO
    $produtoDAO = new ProdutoDAO();
    if ($produtoDAO->atualizar($id, $nome, $descricao, $preco, $categoria_id)) {
        
        // 4. Sucesso: Limpa dados e define mensagem
        unset($_SESSION['form_data']); 
        $_SESSION['msg_sucesso'] = "Produto '{$nome}' atualizado com sucesso.";
        header("Location: ../public/produtos/listar.php"); // Redireciona para a listagem
        exit();
        
    } else {
        // 5. Erro no Banco (ou nenhum registro afetado)
        // Você pode refinar a mensagem se o erro for por falta de alteração de dados
        $_SESSION['msg_erro'] = "Erro interno ao atualizar o produto ou nenhuma alteração foi detectada.";
        header("Location: " . $redirect_url_erro); 
        exit();
    }

} else {
    // Acesso direto ao script de ação
    $_SESSION['msg_erro'] = "Acesso inválido ao processamento de edição.";
    header("Location: ../public/produtos/listar.php");
    exit();
}