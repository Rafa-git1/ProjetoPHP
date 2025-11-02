<?php 
/**
 * Edição de Produto Estilizada
 * Corrige o caminho de inclusão e aplica o estilo do formulário.
 */

// 1. Checagem de Autenticação
// CORREÇÃO DE CAMINHO: Mudado de '../../../' para '../../'
require_once __DIR__ . '/../../includes/auth_check.php';

// 2. Dependências
// CORREÇÃO DE CAMINHO: Mudado de '../../../' para '../../'
require_once __DIR__ . '/../../app/dao/ProdutoDAO.php';

// Define o título da página para o header.php
$pageTitle = "Editar Produto"; 

$produtoDAO = new ProdutoDAO();
$produto = null;
$categorias = $produtoDAO->listarCategorias();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// 3. Busca dos Dados Existentes ou Dados de Erro
if ($id) {
    // Tenta buscar o produto no banco
    // Usando o método que você indicou: lerPorId
    $produto = $produtoDAO->lerPorId($id);
    
    if (!$produto) {
        $_SESSION['msg_erro'] = "Produto não encontrado para edição.";
        header("Location: listar.php");
        exit();
    }
} else {
    // Se não houver ID na URL
    $_SESSION['msg_erro'] = "ID do produto não fornecido.";
    header("Location: listar.php");
    exit();
}

// 4. Preencher com dados do produto ou com dados de erro (se o formulário falhou no POST)
$dados_form = $_SESSION['form_data'] ?? [
    'id' => $produto['id'],
    'nome' => $produto['nome'],
    'descricao' => $produto['descricao'],
    // Formatando preço para o input number, se for o caso
    'preco' => number_format($produto['preco'], 2, '.', ''), 
    'categoria_id' => $produto['categoria_id']
];

// Inclusão do header.php (CORREÇÃO DE CAMINHO)
require_once __DIR__ . '/../../includes/header.php'; 
?>

<div class="page-header">
    <h1>Editar Produto: <?= htmlspecialchars($produto['nome']); ?></h1>
    <a href="listar.php" class="button button-secondary">
        <span class="icon">&#x2190;</span> Voltar para a Listagem
    </a>
</div>

<!-- APLICAÇÃO DO ESTILO DE CARD DE FORMULÁRIO -->
<div class="form-card">
    <form action="../../actions/produto_editar.php" method="POST">
        <!-- O action foi corrigido para o caminho relativo correto: '../../actions/produto_editar.php' -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($dados_form['id']); ?>">
        
        <div class="form-group">
            <label for="nome">Nome do Produto: (Obrigatório)</label>
            <input type="text" id="nome" name="nome" required 
                maxlength="255" value="<?= htmlspecialchars($dados_form['nome']); ?>">
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?= htmlspecialchars($dados_form['descricao']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço (R$): (Obrigatório)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required 
                value="<?= htmlspecialchars($dados_form['preco']); ?>">
        </div>
        
        <div class="form-group">
            <label for="categoria_id">Categoria: (Obrigatório)</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id']; ?>"
                        <?= ($dados_form['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="button-group">
            <button type="submit" class="button button-primary">
                <span class="icon">&#x2713;</span> Salvar Alterações
            </button>
            <!-- NOVO BOTÃO DE VOLTAR -->
            <a href="listar.php" class="button button-secondary">
                <span class="icon">&#x2190;</span> Cancelar e Voltar
            </a>
        </div>
    </form>
</div>

<?php 
// Limpa dados de formulário após exibição
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
// Inclusão do footer.php (CORREÇÃO DE CAMINHO)
require_once __DIR__ . '/../../includes/footer.php'; 
?>
