<?php 
/**
 * Cadastro de Produto Estilizado
 * Corrige o caminho de inclusão e aplica o estilo do formulário.
 * Adiciona o botão de Voltar.
 */

// 1. Checagem de Autenticação e Sessão
// CORREÇÃO DE CAMINHO: Usando '../../' (dois níveis acima)
require_once __DIR__ . '/../../includes/auth_check.php';

// 2. Dependências
// CORREÇÃO DE CAMINHO: Usando '../../'
require_once __DIR__ . '/../../app/dao/ProdutoDAO.php';

// Define o título da página para o header.php
$pageTitle = "Cadastrar Produto"; 

// Instancia o DAO para buscar as categorias
$produtoDAO = new ProdutoDAO();
$categorias = $produtoDAO->listarCategorias();

// 3. Estrutura da Página
// CORREÇÃO DE CAMINHO: Usando '../../'
require_once __DIR__ . '/../../includes/header.php'; 
?>

<div class="page-header">
    <h1>Cadastrar Novo Produto</h1>
    <!-- Link de voltar no cabeçalho (mantido por consistência) -->
    <a href="listar.php" class="button button-secondary">
        <span class="icon">&#x2190;</span> Voltar para a Listagem
    </a>
</div>

<!-- Aplica a classe .form-card para centralizar e estilizar o formulário -->
<div class="form-card">
    <form action="../../actions/produto_cadastrar.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome do Produto: (Obrigatório)</label>
            <input type="text" id="nome" name="nome" required 
                maxlength="255" value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?= htmlspecialchars($_SESSION['form_data']['descricao'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço (R$): (Obrigatório)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required 
                value="<?= htmlspecialchars($_SESSION['form_data']['preco'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="categoria_id">Categoria: (Obrigatório)</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id']; ?>"
                        <?= (isset($_SESSION['form_data']['categoria_id']) && $_SESSION['form_data']['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Agrupamento de botões -->
        <div class="button-group">
            <button type="submit" class="button button-primary">
                <span class="icon">&#x2713;</span> Salvar Produto
            </button>
            <!-- BOTÃO/LINK DE VOLTAR ADICIONADO AQUI -->
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
