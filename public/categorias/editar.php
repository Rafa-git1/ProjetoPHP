<?php
/**
 * editar.php
 * Exibe o formulário de edição de categorias e carrega os dados atuais.
 */
// Define a raiz do projeto de forma robusta.
// __DIR__ aponta para /PROJETO-PHP/public/categorias
// dirname(dirname(__DIR__)) aponta para /PROJETO-PHP (a raiz do projeto)
$projectRoot = dirname(dirname(__DIR__));

// 1. Checagem de Autenticação e Nível de Acesso (Usando caminho robusto)
require_once $projectRoot . '/includes/auth_check.php';

// // Verifica se o usuário tem permissão de administrador
// if ($_SESSION['nivel_acesso'] !== 'admin') {
//     $_SESSION['msg_erro'] = "Acesso negado. Apenas administradores podem gerenciar categorias.";
//     header("Location: ../../index.php");
//     exit;
// }

// 2. Verifica se o ID foi passado via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg_erro'] = "ID da categoria inválido.";
    header("Location: listar.php");
    exit;
}

$id = (int)$_GET['id'];

// 3. Inclui o DAO e busca a categoria (Usando caminho robusto)
require_once $projectRoot . '/app/dao/CategoriaDAO.php';
$categoriaDAO = new CategoriaDAO();

// Inicializa variáveis para o formulário
$nome = '';
$categoria = $categoriaDAO->lerPorId($id);

// 4. Verifica se a categoria existe
if (!$categoria) {
    $_SESSION['msg_erro'] = "Categoria não encontrada.";
    header("Location: listar.php");
    exit;
}

// 5. Preenche os campos com os dados existentes ou dados de sessão (após erro de validação)
if (isset($_SESSION['form_data'])) {
    $nome = htmlspecialchars($_SESSION['form_data']['nome'] ?? $categoria['nome']);
    unset($_SESSION['form_data']); // Limpa os dados
} else {
    $nome = htmlspecialchars($categoria['nome']);
}

// 6. Inclui o Header (Abre o HTML e <body>, inclui o CSS e exibe o título)
require_once $projectRoot . '/includes/header.php';
$pageTitle = 'Editar Categoria: ' . $nome;
?>

<h1>Editar Categoria: <?php echo $nome; ?></h1>

<!-- O action aponta para o arquivo de processamento de edição. Usando caminho absoluto é mais seguro. -->
<!-- Você pode ajustar o /ProjetoPHP se for diferente. -->
<form action="/ProjetoPHP/actions/categoria_editar.php" method="POST" class="form-standard">
    
    <!-- Campo oculto para enviar o ID -->
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label for="nome">Nome da Categoria:</label>
    <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>

    <div class="form-actions">
        <a href="listar.php" class="button btn-secondary">Voltar</a>
        <button type="submit" class="button btn-primary">Salvar Alterações</button>
    </div>
</form>

<?php 
// 7. Rodapé (fecha </body> e </html>)
require_once $projectRoot . '/includes/footer.php'; 
?>
