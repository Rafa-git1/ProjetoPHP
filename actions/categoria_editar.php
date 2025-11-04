<?php
/**
 * categoria_editar.php
 * Processa a submissão do formulário de edição de categoria.
 */
// Define a raiz do projeto (Subindo 1 nível: de /actions para /ProjetoPHP)
$projectRoot = dirname(__DIR__);

// Checagem de Autenticação e Sessão
require_once $projectRoot . '/includes/auth_check.php';

// Proteção Adicional: Apenas administradores podem acessar
if ($_SESSION['nivel_acesso'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado. Apenas administradores podem editar categorias.";
    header("Location: /ProjetoPHP/public/categorias/listar.php"); // Redireciona para listagem
    exit();
}

// Dependências
require_once $projectRoot . '/app/dao/CategoriaDAO.php';
$categoriaDAO = new CategoriaDAO(); // Instância criada aqui para validação

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Coleta e Sanitização dos Dados
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);

    // Armazena dados no SESSION em caso de erro (para repopular o formulário de edição)
    $_SESSION['form_data'] = ['nome' => $nome];

    // Define o caminho de volta em caso de erro
    $locationOnError = "/ProjetoPHP/public/categorias/editar.php?id={$id}";

    // Validação dos Dados
    if ($id === false || $id === null || empty($nome)) {
        $_SESSION['msg_erro'] = "Dados inválidos ou incompletos.";
        header("Location: " . $locationOnError);
        exit();
    }
    
    // Validação de Unicidade (NOVA ETAPA)
    // Chama o método nomeExiste() do DAO, ignorando o ID atual
    if ($categoriaDAO->nomeExiste($nome, $id)) {
        $_SESSION['msg_erro'] = "O nome da categoria '{$nome}' já existe.";
        header("Location: " . $locationOnError);
        exit();
    }
    
    // Execução do DAO
    if ($categoriaDAO->atualizar($id, $nome)) {
        
        // Sucesso: Limpa dados e define mensagem
        unset($_SESSION['form_data']); 
        $_SESSION['msg_sucesso'] = "Categoria '{$nome}' atualizada com sucesso.";
        header("Location: /ProjetoPHP/public/categorias/listar.php"); // Redireciona para a listagem
        exit();
        
    } else {
        // Erro no Banco
        // Aqui, capturamos erros de banco de dados reais (ex: falha de conexão)
        $_SESSION['msg_erro'] = "Erro interno ao atualizar a categoria. Tente novamente.";
        header("Location: " . $locationOnError); // Redireciona de volta ao formulário
        exit();
    }

} else {
    // Acesso direto ao script de ação
    $_SESSION['msg_erro'] = "Acesso inválido ao processamento de edição.";
    header("Location: /ProjetoPHP/public/categorias/listar.php");
    exit();
}
